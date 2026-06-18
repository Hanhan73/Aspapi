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
        $region = auth()->user()->region;

        abort_unless($region, 403);

        $stats = [
            'total_members'  => $region->members()->count(),
            'active_members' => $region->members()->where('status', 'active')->count(),
            'pending'        => $region->members()->where('status', 'pending')->count(),
        ];

        $recentMembers = $region->members()->latest()->take(5)->get();

        // ── Data untuk chart: pertumbuhan anggota per bulan (12 bulan terakhir)
        $growthRaw = $region->members()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . str_pad($r->month, 2, '0', STR_PAD_LEFT));

        // Bangun array 12 bulan terakhir lengkap (isi 0 kalau tidak ada data)
        $chartLabels = [];
        $chartData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $dt    = now()->subMonths($i);
            $key   = $dt->format('Y-m');
            $label = $dt->locale('id')->isoFormat('MMM YY');

            $chartLabels[] = $label;
            $chartData[]   = $growthRaw->get($key)?->total ?? 0;
        }

        // ── Breakdown status untuk donut chart
        $statusBreakdown = [
            'active'   => $region->members()->where('status', 'active')->count(),
            'pending'  => $region->members()->where('status', 'pending')->count(),
            'inactive' => $region->members()->where('status', 'inactive')->count(),
            'rejected' => $region->members()->where('status', 'rejected')->count(),
        ];

        // ── Iuran: lunas vs belum
        $duesBreakdown = [
            'lunas' => $region->members()->where('dues_paid', true)->count(),
            'belum' => $region->members()->where('dues_paid', false)->count(),
        ];

        return view('daerah.dashboard', compact(
            'region',
            'stats',
            'recentMembers',
            'chartLabels',
            'chartData',
            'statusBreakdown',
            'duesBreakdown',
        ));
    }
}