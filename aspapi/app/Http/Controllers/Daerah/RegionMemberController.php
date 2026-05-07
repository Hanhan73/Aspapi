<?php

namespace App\Http\Controllers\Daerah;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionMemberController extends Controller
{
    /**
     * Dashboard ASPAPI Daerah
     */
    public function index()
    {
        $region = auth()->user()->region;
        abort_unless($region, 403, 'Akun ini tidak terhubung ke ASPAPI Daerah manapun.');

        $stats = [
            'total_members'  => Member::where('registered_by_region_id', $region->id)->count(),
            'active_members' => Member::where('registered_by_region_id', $region->id)->where('status', 'active')->count(),
            'pending'        => Member::where('registered_by_region_id', $region->id)->where('status', 'pending')->count(),
        ];

        $recentMembers = Member::where('registered_by_region_id', $region->id)
            ->latest()->take(5)->get();

        return view('daerah.dashboard', compact('region', 'stats', 'recentMembers'));
    }

    /**
     * Daftar anggota di wilayah ini
     */
    public function members(Request $request)
    {
        $region  = auth()->user()->region;
        abort_unless($region, 403);

        $members = Member::where('registered_by_region_id', $region->id)
            ->when($request->q, fn($q) => $q->where('full_name', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(20);

        return view('daerah.members', compact('region', 'members'));
    }

    /**
     * Form pendaftaran batch
     */
    public function batchForm()
    {
        $region = auth()->user()->region;
        abort_unless($region, 403);

        return view('daerah.batch-form', compact('region'));
    }

    /**
     * Simpan pendaftaran batch (dari Excel)
     * TODO: implementasi lengkap dengan import Excel
     */
    public function batchStore(Request $request)
    {
        // Placeholder — akan diimplementasi bersama fitur batch
        return back()->with('info', 'Fitur batch sedang dalam pengembangan.');
    }

    /**
     * Form pembayaran kolektif
     */
    public function payBatchForm()
    {
        $region = auth()->user()->region;
        abort_unless($region, 403);

        $members = Member::where('registered_by_region_id', $region->id)
            ->where('status', 'active')
            ->whereDoesntHave('payments', fn($q) =>
                $q->where('type', 'iuran_tahunan')
                  ->where('status', 'verified')
                  ->where('payment_year', now()->year)
            )
            ->get();

        return view('daerah.pay-batch', compact('region', 'members'));
    }

    /**
     * Simpan pembayaran kolektif
     * TODO: implementasi lengkap dengan PaymentBatch
     */
    public function payBatchStore(Request $request)
    {
        // Placeholder — akan diimplementasi bersama fitur pembayaran kolektif
        return back()->with('info', 'Fitur pembayaran kolektif sedang dalam pengembangan.');
    }
}