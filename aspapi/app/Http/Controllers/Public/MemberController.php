<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Halaman Jenis & Syarat Anggota
     */
    public function types()
    {
        return view('public.members.types');
    }

    /**
     * Halaman Registrasi & Iuran Anggota
     */
    public function registerForm()
    {
        return view('public.members.register');
    }

    public function registerStore(Request $request)
    {
        // redirect ke portal member untuk daftar
        return redirect()->away('https://member.aspapi.id/register');
    }

    public function directory(Request $request)
{
    $query = \App\Models\Member::with('registeredByRegion')
        ->where('status', 'active')
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
 
    $totalAll    = \App\Models\Member::where('status', 'active')->count();
    $totalRegions = \App\Models\Region::where('is_active', true)->count();
    $regions     = \App\Models\Region::where('is_active', true)->orderBy('name')->get();
    $members     = $query->paginate(25)->withQueryString();
 
    return view('public.members.directory', compact('members', 'regions', 'totalAll', 'totalRegions'));
}
}