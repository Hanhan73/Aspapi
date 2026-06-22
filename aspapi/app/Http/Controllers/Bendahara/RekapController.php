<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Member;
use App\Models\Region;
use App\Exports\RekapTransaksiExport;
use App\Exports\IuranAnggotaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    public function rekap(Request $request)
    {
        $year     = $request->filled('year') ? (int) $request->year : now()->year;
        $regionId = $request->filled('region') ? (int) $request->region : null;
        $regions  = Region::orderBy('name')->get();

        $basePayment = Payment::where('status', 'verified')
            ->whereYear('verified_at', $year)
            ->when($regionId, fn($q) => $q->whereHas('member', fn($sub) =>
                $sub->where('registered_by_region_id', $regionId)
            ));

        $totalTahun  = (clone $basePayment)->sum('amount');
        $totalPangkal = (clone $basePayment)->where('type', 'uang_pangkal')->sum('amount');
        $totalIuran   = (clone $basePayment)->where('type', 'iuran_tahunan')->sum('amount');

        $perBulan = [];
        for ($m = 1; $m <= 12; $m++) {
            $base = Payment::where('status', 'verified')
                ->whereYear('verified_at', $year)
                ->whereMonth('verified_at', $m)
                ->when($regionId, fn($q) => $q->whereHas('member', fn($sub) =>
                    $sub->where('registered_by_region_id', $regionId)
                ));

            $perBulan[$m] = [
                'bulan'         => $m,
                'nama_bulan'    => \Carbon\Carbon::create()->month($m)->translatedFormat('F'),
                'uang_pangkal'  => (clone $base)->where('type', 'uang_pangkal')->sum('amount'),
                'iuran_tahunan' => (clone $base)->where('type', 'iuran_tahunan')->sum('amount'),
            ];
            $perBulan[$m]['total'] = $perBulan[$m]['uang_pangkal'] + $perBulan[$m]['iuran_tahunan'];
        }

        $transaksi = Payment::with(['member.region', 'verifier'])
            ->where('status', 'verified')
            ->whereYear('verified_at', $year)
            ->when($regionId, fn($q) => $q->whereHas('member', fn($sub) =>
                $sub->where('registered_by_region_id', $regionId)
            ))
            ->when($request->filled('month'),  fn($q) => $q->whereMonth('verified_at', $request->month))
            ->when($request->filled('type'),   fn($q) => $q->where('type', $request->type))
            ->when($request->filled('search'), fn($q) =>
                $q->whereHas('member', fn($sub) =>
                    $sub->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                )
            )
            ->latest('verified_at')
            ->paginate(20)
            ->withQueryString();

        return view('bendahara.rekap', compact(
            'year', 'regionId', 'regions',
            'totalTahun', 'totalPangkal', 'totalIuran',
            'perBulan', 'transaksi'
        ));
    }

    public function rekapExport(Request $request)
    {
        $year     = $request->filled('year') ? (int) $request->year : now()->year;
        $regionId = $request->filled('region') ? (int) $request->region : null;
        $region   = $regionId ? Region::find($regionId) : null;
        $suffix   = $region ? '_' . \Illuminate\Support\Str::slug($region->name) : '';

        return Excel::download(
            new RekapTransaksiExport(
                year:     $year,
                month:    $request->filled('month') ? (int) $request->month : null,
                type:     $request->input('type'),
                search:   $request->input('search'),
                regionId: $regionId,
            ),
            "rekap_transaksi_{$year}{$suffix}.xlsx"
        );
    }

    public function iuran(Request $request)
    {
        $regionId = $request->filled('region') ? (int) $request->region : null;
        $regions  = Region::orderBy('name')->get();
        $filterStatus = $request->input('status_iuran');

        $aktifIds = Member::where('status', 'active')
            ->where('biodata_status', 'verified')
            ->whereNotNull('active_until')
            ->where('active_until', '>', now())
            ->when($regionId, fn($q) => $q->where('registered_by_region_id', $regionId))
            ->pluck('id')->toArray();

        $kadaluarsaIds = Member::where('status', 'active')
            ->where('biodata_status', 'verified')
            ->where(fn($q) => $q->whereNull('active_until')->orWhere('active_until', '<=', now()))
            ->when($regionId, fn($q) => $q->where('registered_by_region_id', $regionId))
            ->pluck('id')->toArray();

        $members = Member::with(['payments' => fn($q) =>
                $q->where('type', 'iuran_tahunan')
                  ->where('status', 'verified')
                  ->latest('verified_at')
                  ->limit(1)
            ])
            ->where('biodata_status', 'verified')
            ->whereIn('status', ['active', 'pending'])
            ->when($regionId, fn($q) => $q->where('registered_by_region_id', $regionId))
            ->when($filterStatus === 'aktif',       fn($q) => $q->whereIn('id', $aktifIds))
            ->when($filterStatus === 'kadaluarsa',  fn($q) => $q->whereIn('id', $kadaluarsaIds))
            ->when($filterStatus === 'belum_aktif', fn($q) => $q->where('status', 'pending'))
            ->when($request->filled('search'), fn($q) =>
                $q->where(fn($sub) =>
                    $sub->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('member_number', 'like', '%' . $request->search . '%')
                )
            )
            ->orderByRaw("FIELD(status, 'active', 'pending')")
            ->orderBy('full_name')
            ->paginate(25)
            ->withQueryString();

        // Summary scoped ke region yang dipilih (atau semua)
        $scopedBase = Member::where('biodata_status', 'verified')
            ->when($regionId, fn($q) => $q->where('registered_by_region_id', $regionId));

        $totalAktifMember = (clone $scopedBase)->where('status', 'active')->count();
        $totalMember      = (clone $scopedBase)->count();
        $totalIuranAktif  = count($aktifIds);
        $totalKadaluarsa  = count($kadaluarsaIds);
        $totalBelumAktif  = (clone $scopedBase)->where('status', 'pending')->count();

        return view('bendahara.iuran', compact(
            'members', 'aktifIds', 'kadaluarsaIds',
            'totalAktifMember', 'totalMember',
            'totalIuranAktif', 'totalKadaluarsa', 'totalBelumAktif',
            'filterStatus', 'regions', 'regionId'
        ));
    }

    public function iuranExport(Request $request)
    {
        $regionId = $request->filled('region') ? (int) $request->region : null;
        $region   = $regionId ? Region::find($regionId) : null;
        $suffix   = $region ? '_' . \Illuminate\Support\Str::slug($region->name) : '';

        $aktifIds = Member::where('status', 'active')
            ->where('biodata_status', 'verified')
            ->whereNotNull('active_until')
            ->where('active_until', '>', now())
            ->when($regionId, fn($q) => $q->where('registered_by_region_id', $regionId))
            ->pluck('id')->toArray();

        $kadaluarsaIds = Member::where('status', 'active')
            ->where('biodata_status', 'verified')
            ->where(fn($q) => $q->whereNull('active_until')->orWhere('active_until', '<=', now()))
            ->when($regionId, fn($q) => $q->where('registered_by_region_id', $regionId))
            ->pluck('id')->toArray();

        return Excel::download(
            new IuranAnggotaExport(
                filterStatus:  $request->input('status_iuran'),
                search:        $request->input('search'),
                regionId:      $regionId,
                aktifIds:      $aktifIds,
                kadaluarsaIds: $kadaluarsaIds,
            ),
            "iuran_anggota{$suffix}.xlsx"
        );
    }
}