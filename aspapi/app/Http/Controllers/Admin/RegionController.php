<?php
// app/Http/Controllers/Admin/RegionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::withCount('members')
                         ->with('activeUser')
                         ->orderBy('sort_order')
                         ->orderBy('province')
                         ->get();

        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'province'         => 'required|string|max:100',
            'chairman_name'    => 'nullable|string|max:150',
            'chairman_title'   => 'nullable|string|max:200',
            'period_start'     => 'nullable|digits:4',
            'period_end'       => 'nullable|digits:4',
            'website_url'      => 'nullable|url|max:255',
            'email'            => 'nullable|email|max:150',
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'description'      => 'nullable|string',
            'is_active'        => 'boolean',
            'sort_order'       => 'integer|min:0',
            'photo'            => 'nullable|image|max:2048',
            'cover_image'      => 'nullable|image|max:4096',
            'create_account'   => 'boolean',
            'account_email'    => 'nullable|email|required_if:create_account,1|unique:users,email',
            'account_password' => 'nullable|string|min:8|required_if:create_account,1',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('regions/photos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('regions/covers', 'public');
        }

        $data['name']  = 'ASPAPI ' . $data['province'];
        $data['slug']  = Str::slug($data['province']);

        $region = Region::create($data);

        if ($request->boolean('create_account')) {
            User::create([
                'name'      => 'ASPAPI ' . $data['province'],
                'email'     => $request->account_email,
                'password'  => Hash::make($request->account_password),
                'role'      => 'aspapi_daerah',
                'region_id' => $region->id,
            ]);
        }

        return redirect()->route('admin.regions.index')
                         ->with('success', 'ASPAPI Daerah berhasil ditambahkan.');
    }

    public function edit(Region $region)
    {
        $region->load('activeUser');
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $data = $request->validate([
            'province'      => 'required|string|max:100',
            'chairman_name' => 'nullable|string|max:150',
            'chairman_title'=> 'nullable|string|max:200',
            'period_start'  => 'nullable|digits:4',
            'period_end'    => 'nullable|digits:4',
            'website_url'   => 'nullable|url|max:255',
            'email'         => 'nullable|email|max:150',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'description'   => 'nullable|string',
            'is_active'     => 'boolean',
            'sort_order'    => 'integer|min:0',
            'photo'         => 'nullable|image|max:2048',
            'cover_image'   => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            if ($region->photo) Storage::disk('public')->delete($region->photo);
            $data['photo'] = $request->file('photo')->store('regions/photos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            if ($region->cover_image) Storage::disk('public')->delete($region->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('regions/covers', 'public');
        }

        $data['name'] = 'ASPAPI ' . $data['province'];

        $region->update($data);

        return redirect()->route('admin.regions.index')
                         ->with('success', 'Data ASPAPI Daerah berhasil diperbarui.');
    }

    public function destroy(Region $region)
    {
        if ($region->photo) Storage::disk('public')->delete($region->photo);
        if ($region->cover_image) Storage::disk('public')->delete($region->cover_image);
        $region->users()->delete();
        $region->delete();

        return redirect()->route('admin.regions.index')
                         ->with('success', 'ASPAPI Daerah berhasil dihapus.');
    }

    public function manageAccount(Region $region)
    {
        $user = $region->activeUser;
        return view('admin.regions.account', compact('region', 'user'));
    }

    public function storeAccount(Request $request, Region $region)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Nonaktifkan akun lama
        $region->users()->where('role', 'aspapi_daerah')->update(['role' => 'guest']);

        User::create([
            'name'      => 'ASPAPI ' . $region->province,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'aspapi_daerah',
            'region_id' => $region->id,
        ]);

        return back()->with('success', 'Akun ASPAPI Daerah berhasil dibuat.');
    }

    public function resetPassword(Request $request, Region $region)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);

        $user = $region->activeUser;
        if (!$user) return back()->with('error', 'Akun daerah tidak ditemukan.');

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil direset.');
    }
}