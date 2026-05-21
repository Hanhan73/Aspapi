<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Member;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    /**
     * #9 — Rekap Pemasukan
     * Route: GET /bendahara/rekap → name: bendahara.rekap
     */
    public function rekap(Request $request)
    {
        $year = $request->filled('year') ? (int) $request->year : now()->year;

        $totalTahun = Payment::where('status', 'verified')
            ->whereYear('verified_at', $year)
            ->sum('amount');

        $totalPangkal = Payment::where('status', 'verified')
            ->where('type', 'uang_pangkal')
            ->whereYear('verified_at', $year)
            ->sum('amount');

        $totalIuran = Payment::where('status', 'verified')
            ->where('type', 'iuran_tahunan')
            ->whereYear('verified_at', $year)
            ->sum('amount');

        $perBulan = [];
        for ($m = 1; $m <= 12; $m++) {
            $perBulan[$m] = [
                'bulan'        => $m,
                'nama_bulan'   => \Carbon\Carbon::create()->month($m)->translatedFormat('F'),
                'uang_pangkal' => Payment::where('status', 'verified')
                    ->where('type', 'uang_pangkal')
                    ->whereYear('verified_at', $year)
                    ->whereMonth('verified_at', $m)
                    ->sum('amount'),
                'iuran_tahunan' => Payment::where('status', 'verified')
                    ->where('type', 'iuran_tahunan')
                    ->whereYear('verified_at', $year)
                    ->whereMonth('verified_at', $m)
                    ->sum('amount'),
            ];
            $perBulan[$m]['total'] = $perBulan[$m]['uang_pangkal'] + $perBulan[$m]['iuran_tahunan'];
        }

        $transaksi = Payment::with(['member', 'verifier'])
            ->where('status', 'verified')
            ->whereYear('verified_at', $year)
            ->when($request->filled('month'), fn($q) => $q->whereMonth('verified_at', $request->month))
            ->when($request->filled('type'),  fn($q) => $q->where('type', $request->type))
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
            'year',
            'totalTahun',
            'totalPangkal',
            'totalIuran',
            'perBulan',
            'transaksi'
        ));
    }

    /**
     * #10 — Status Iuran Anggota
     * Route: GET /bendahara/iuran → name: bendahara.iuran
     *
     * Scope anggota yang ditampilkan:
     *   A) status = 'active'  + biodata_status = 'verified'  → anggota penuh
     *   B) status = 'pending' + biodata_status = 'verified'  → biodata sudah disetujui,
     *      belum bayar / belum diaktifkan (segmen "Belum Aktif")
     *
     * Status iuran berbasis active_until (bukan tahun kalender):
     *   - Iuran Aktif       : active_until > now()
     *   - Kadaluarsa        : active_until <= now() (pernah bayar, sudah lewat)
     *   - Belum Aktif       : status = 'pending' (biodata verified, belum pernah bayar)
     */
    public function iuran(Request $request)
    {
        $filterStatus = $request->input('status_iuran'); // 'aktif'|'kadaluarsa'|'belum_aktif'|''

        // IDs anggota active yang iurannya masih berlaku
        $aktifIds = Member::where('status', 'active')
            ->where('biodata_status', 'verified')
            ->whereNotNull('active_until')
            ->where('active_until', '>', now())
            ->pluck('id')
            ->toArray();

        // IDs anggota active yang iurannya sudah kadaluarsa (pernah bayar tapi expired)
        $kadaluarsaIds = Member::where('status', 'active')
            ->where('biodata_status', 'verified')
            ->where(fn($q) =>
                $q->whereNull('active_until')
                  ->orWhere('active_until', '<=', now())
            )
            ->pluck('id')
            ->toArray();

        // Query utama: gabungkan active + pending(biodata verified)
        $members = Member::with(['payments' => fn($q) =>
                $q->where('type', 'iuran_tahunan')
                  ->where('status', 'verified')
                  ->latest('verified_at')
                  ->limit(1)
            ])
            ->where('biodata_status', 'verified')
            ->whereIn('status', ['active', 'pending'])
            ->when($filterStatus === 'aktif', fn($q) =>
                $q->whereIn('id', $aktifIds)
            )
            ->when($filterStatus === 'kadaluarsa', fn($q) =>
                $q->whereIn('id', $kadaluarsaIds)
            )
            ->when($filterStatus === 'belum_aktif', fn($q) =>
                $q->where('status', 'pending')
            )
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

        // Summary counts
        $totalAktifMember  = Member::where('status', 'active')->where('biodata_status', 'verified')->count();
        $totalIuranAktif   = count($aktifIds);
        $totalKadaluarsa   = count($kadaluarsaIds);
        $totalBelumAktif   = Member::where('status', 'pending')->where('biodata_status', 'verified')->count();

        return view('bendahara.iuran', compact(
            'members',
            'aktifIds',
            'kadaluarsaIds',
            'totalAktifMember',
            'totalIuranAktif',
            'totalKadaluarsa',
            'totalBelumAktif',
            'filterStatus'
        ));
    }
}