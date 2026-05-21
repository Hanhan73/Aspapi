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
     * Aturan baru: iuran berbasis active_until (bukan per tahun kalender).
     * "Sudah bayar" = active_until masih di masa depan.
     * "Belum bayar / kadaluarsa" = active_until null atau sudah lewat.
     */
    public function iuran(Request $request)
    {
        // Filter status — tidak lagi pakai $year sebagai basis hitungan
        // tapi tetap ada untuk keperluan filter tanggal bayar jika dibutuhkan
        $filterStatus = $request->input('status_iuran'); // 'aktif' | 'kadaluarsa' | ''

        // IDs anggota aktif yang iurannya masih berlaku (active_until > sekarang)
        $aktifIds = Member::where('status', 'active')
            ->where('biodata_status', 'verified')
            ->whereNotNull('active_until')
            ->where('active_until', '>', now())
            ->pluck('id')
            ->toArray();

        $members = Member::with(['payments' => fn($q) =>
                $q->where('type', 'iuran_tahunan')
                  ->where('status', 'verified')
                  ->latest('verified_at')
                  ->limit(1)
            ])
            ->where('status', 'active')
            ->where('biodata_status', 'verified')
            ->when($filterStatus === 'aktif', fn($q) =>
                $q->whereIn('id', $aktifIds)
            )
            ->when($filterStatus === 'kadaluarsa', fn($q) =>
                $q->whereNotIn('id', $aktifIds)
            )
            ->when($request->filled('search'), fn($q) =>
                $q->where(fn($sub) =>
                    $sub->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('member_number', 'like', '%' . $request->search . '%')
                )
            )
            ->orderBy('full_name')
            ->paginate(25)
            ->withQueryString();

        $totalAktifMember  = Member::where('status', 'active')->where('biodata_status', 'verified')->count();
        $totalIuranAktif   = count($aktifIds);
        $totalIuranExpired = $totalAktifMember - $totalIuranAktif;

        return view('bendahara.iuran', compact(
            'members',
            'aktifIds',
            'totalAktifMember',
            'totalIuranAktif',
            'totalIuranExpired',
            'filterStatus'
        ));
    }
}