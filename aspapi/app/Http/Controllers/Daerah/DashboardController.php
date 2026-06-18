<?php
// app/Http/Controllers/Daerah/DashboardController.php

namespace App\Http\Controllers\Daerah;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $region = $this->region();

    $stats = [
        'total_members'  => Member::where('registered_by_region_id', $region->id)->count(),
        'active_members' => Member::where('registered_by_region_id', $region->id)->where('status', 'active')->count(),
        'pending'        => Member::where('registered_by_region_id', $region->id)->where('status', 'pending')->count(),
    ];

    $recentMembers = Member::where('registered_by_region_id', $region->id)
        ->latest()->take(5)->get();

    // Chart: pertumbuhan anggota per bulan (12 bulan terakhir)
    $growthRaw = Member::where('registered_by_region_id', $region->id)
        ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
        ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
        ->groupBy('year', 'month')
        ->orderBy('year')->orderBy('month')
        ->get()
        ->keyBy(fn($r) => $r->year . '-' . str_pad($r->month, 2, '0', STR_PAD_LEFT));

    $chartLabels = [];
    $chartData   = [];
    for ($i = 11; $i >= 0; $i--) {
        $dt            = now()->subMonths($i);
        $key           = $dt->format('Y-m');
        $chartLabels[] = $dt->locale('id')->isoFormat('MMM YY');
        $chartData[]   = $growthRaw->get($key)?->total ?? 0;
    }

    // Breakdown status
    $statusBreakdown = [
        'active'   => Member::where('registered_by_region_id', $region->id)->where('status', 'active')->count(),
        'pending'  => Member::where('registered_by_region_id', $region->id)->where('status', 'pending')->count(),
        'inactive' => Member::where('registered_by_region_id', $region->id)->where('status', 'inactive')->count(),
        'rejected' => Member::where('registered_by_region_id', $region->id)->where('status', 'rejected')->count(),
    ];

    // Iuran
    $duesBreakdown = [
        'lunas' => Member::where('registered_by_region_id', $region->id)->where('dues_paid', true)->count(),
        'belum' => Member::where('registered_by_region_id', $region->id)->where('dues_paid', false)->count(),
    ];

    return view('daerah.dashboard', compact(
        'region', 'stats', 'recentMembers',
        'chartLabels', 'chartData',
        'statusBreakdown', 'duesBreakdown',
    ));
}
}