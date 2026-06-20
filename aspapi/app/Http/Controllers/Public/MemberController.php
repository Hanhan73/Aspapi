<?php
// app/Http/Controllers/Public/MemberController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function types()
    {
        return view('public.members.types');
    }

    public function registerForm()
    {
        return view('public.members.register');
    }

    public function registerStore(Request $request)
    {
        return redirect()->away('https://member.aspapi.id/register');
    }

    public function directory(Request $request)
    {
        // ── Query utama: semua anggota terverifikasi biodata ─────────────────
        $query = Member::with('registeredByRegion')
            ->where('biodata_status', 'verified')
            ->whereIn('status', ['active', 'pending', 'inactive'])
            ->orderBy('full_name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%");
            });
        }

        if ($regionId = $request->input('region')) {
            $query->where('registered_by_region_id', $regionId);
        }

        // Filter status: aktif = active + active_until future, terdaftar = sisanya
        if ($request->input('status_filter') === 'aktif') {
            $query->where('status', 'active')
                ->whereNotNull('active_until')
                ->where('active_until', '>', now());
        } elseif ($request->input('status_filter') === 'terdaftar') {
            $query->where(function ($q) {
                $q->where('status', '!=', 'active')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'active')
                            ->where(function ($q3) {
                                $q3->whereNull('active_until')
                                    ->orWhere('active_until', '<=', now());
                            });
                    });
            });
        }

        // ── Stats ─────────────────────────────────────────────────────────────
        $totalAll = Member::where('biodata_status', 'verified')
            ->whereIn('status', ['active', 'pending', 'inactive'])
            ->count();

        $totalActive = Member::where('status', 'active')
            ->whereNotNull('active_until')
            ->where('active_until', '>', now())
            ->count();

        $totalRegions = Region::where('is_active', true)->count();
        $regions      = Region::where('is_active', true)->orderBy('name')->get();
        $members      = $query->paginate(25)->withQueryString();

        // ── Chart: Per Daerah ─────────────────────────────────────────────────
        $regionStats = Region::where('is_active', true)
            ->withCount([
                'members as total_members' => fn($q) =>
                $q->where('biodata_status', 'verified')
                    ->whereIn('status', ['active', 'pending', 'inactive']),
                'members as aktif_members' => fn($q) =>
                $q->where('status', 'active')
                    ->whereNotNull('active_until')
                    ->where('active_until', '>', now()),
            ])
            ->orderBy('total_members', 'desc')
            ->get();

        $chartDaerah = $regionStats->map(fn($r) => [
            'name'  => $r->name,
            'total' => $r->total_members,
            'aktif' => $r->aktif_members,
        ])->values();

        // ── Chart: Jenis Kelamin ──────────────────────────────────────────────
        $genderRaw = Member::where('biodata_status', 'verified')
            ->whereIn('status', ['active', 'pending', 'inactive'])
            ->select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->pluck('count', 'gender');

        $chartGender = [
            ['label' => 'Laki-laki',    'count' => (int)($genderRaw['L'] ?? 0)],
            ['label' => 'Perempuan',     'count' => (int)($genderRaw['P'] ?? 0)],
        ];
        if (($genderRaw->except(['L', 'P'])->sum()) > 0) {
            $chartGender[] = ['label' => 'Lainnya', 'count' => (int)$genderRaw->except(['L', 'P'])->sum()];
        }

        // ── Chart: Pekerjaan ─────────────────────────────────────────────────
        $occRaw = Member::where('biodata_status', 'verified')
            ->whereIn('status', ['active', 'pending', 'inactive'])
            ->whereNotNull('occupation')
            ->where('occupation', '!=', '')
            ->select('occupation', DB::raw('count(*) as count'))
            ->groupBy('occupation')
            ->orderBy('count', 'desc')
            ->limit(6)
            ->get();

        $chartOccupation = $occRaw->map(fn($o) => [
            'label' => $o->occupation,
            'count' => (int)$o->count,
        ])->values();

        return view('public.members.directory', compact(
            'members',
            'regions',
            'totalAll',
            'totalActive',
            'totalRegions',
            'chartDaerah',
            'chartGender',
            'chartOccupation'
        ));
    }
}
