<?php
// app/Http/Controllers/Daerah/DashboardController.php

namespace App\Http\Controllers\Daerah;

use App\Http\Controllers\Controller;

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

        return view('daerah.dashboard', compact('region', 'stats', 'recentMembers'));
    }
}