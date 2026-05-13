<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    public function edit()
    {
        $member    = auth()->user()->member;
        $provinces = Province::orderBy('name')->get();
        $cities    = $member?->province_id
            ? City::where('province_id', $member->province_id)->orderBy('name')->get()
            : collect();

        return view('member.biodata', compact('member', 'provinces', 'cities'));
    }



// Ini hanya potongan bagian update() — sesuaikan dengan controller kamu yang ada

public function update(Request $request)
{
    $validated = $request->validate([
        'full_name'      => 'required|string|max:255',
        'nik'            => 'required|digits:16',
        'birth_place'    => 'required|string|max:100',
        'birth_date'     => 'required|date|before:today',
        'phone'          => 'required|string|max:20',
        'email'          => 'required|email|max:255',
        'gender'         => 'required|in:L,P',
        'last_education' => 'required|in:sd,smp,sma,d3,s1,s2,s3,profesi,lainnya',
        'province_id'    => 'required|exists:provinces,id',
        'city_id'        => 'required|exists:cities,id',
        'address'        => 'required|string|max:500',
        'occupation'     => 'nullable|string|max:150',
        'institution'    => 'nullable|string|max:255',
        'position'       => 'nullable|string|max:150',
        'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $member = auth()->user()->member;

    if ($request->hasFile('photo')) {
        // Hapus foto lama jika ada
        if ($member->photo) {
            Storage::delete($member->photo);
        }
        $validated['photo'] = $request->file('photo')->store('member-photos', 'public');
    }

    // Reset status ke pending setiap kali biodata diubah
    $validated['biodata_status']        = 'pending';
    $validated['biodata_reject_reason'] = null;

    $member->update($validated);

    return redirect()->route('member.biodata')
        ->with('success', 'Biodata berhasil disimpan. Menunggu verifikasi admin.');
}
}