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
     *
     * Menampilkan total pemasukan per bulan/tahun,
     * breakdown per jenis pembayaran, dan daftar transaksi verified.
     */
    public function rekap(Request $request)
    {
        $year = $request->filled('year') ? (int) $request->year : now()->year;

        // ── Total keseluruhan tahun ini ──────────────────────────────────────
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

        // ── Per bulan (untuk chart / tabel) ──────────────────────────────────
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

        // ── Daftar transaksi verified (paginated, filter bulan/type) ─────────
        $transaksi = Payment::with(['member', 'verifier'])
            ->where('status', 'verified')
            ->whereYear('verified_at', $year)
            ->when($request->filled('month'), fn($q) => $q->whereMonth('verified_at', $request->month))
            ->when($request->filled('type'),  fn($q) => $q->where('type', $request->type))
            ->when(
                $request->filled('search'),
                fn($q) =>
                $q->whereHas(
                    'member',
                    fn($sub) =>
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
     * Menampilkan daftar anggota beserta status iuran tahunan,
     * siapa sudah/belum bayar, dan info due date.
     */
    public function iuran(Request $request)
    {
        $year = $request->filled('year') ? (int) $request->year : now()->year;

        // "Sudah bayar" = active_until masih future (bukan berdasarkan payment_year)
        $sudahBayarIds = Member::where('status', 'active')
            ->where('biodata_status', 'verified')
            ->whereNotNull('active_until')
            ->where('active_until', '>', now())
            ->pluck('id')
            ->toArray();

        $members = Member::with([
            'payments' => fn($q) =>
            $q->where('type', 'iuran_tahunan')
                ->where('status', 'verified')
                ->latest('verified_at')
                ->limit(1)
        ])
            ->where('status', 'active')
            ->where('biodata_status', 'verified')
            ->when($request->filled('status_iuran'), function ($q) use ($request, $sudahBayarIds) {
                if ($request->status_iuran === 'sudah') {
                    $q->whereIn('id', $sudahBayarIds);
                } elseif ($request->status_iuran === 'belum') {
                    $q->whereNotIn('id', $sudahBayarIds);
                }
            })
            ->when(
                $request->filled('search'),
                fn($q) =>
                $q->where(
                    fn($sub) =>
                    $sub->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('member_number', 'like', '%' . $request->search . '%')
                )
            )
            ->orderBy('full_name')
            ->paginate(25)
            ->withQueryString();

        $totalAktif      = Member::where('status', 'active')->where('biodata_status', 'verified')->count();
        $totalSudahBayar = count($sudahBayarIds);
        $totalBelumBayar = $totalAktif - $totalSudahBayar;

        return view('bendahara.iuran', compact(
            'year',
            'members',
            'sudahBayarIds',
            'totalAktif',
            'totalSudahBayar',
            'totalBelumBayar'
        ));
    }
}
