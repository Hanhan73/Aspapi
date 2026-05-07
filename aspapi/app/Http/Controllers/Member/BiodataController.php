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

    public function update(Request $request)
    {
        $member = auth()->user()->member;

        $request->validate([
            'full_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'nik'           => 'required|string|size:16',
            'gender'        => 'required|in:L,P',
            'province_id'   => 'required|exists:provinces,id',
            'city_id'       => 'required|exists:cities,id',
            'address'       => 'required|string',
            'institution'   => 'nullable|string|max:255',
            'study_program' => 'nullable|string|max:255',
            'position'      => 'nullable|string|max:255',
            'member_type'   => 'required|in:biasa,luar_biasa,kehormatan',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            if ($member->photo) Storage::disk('public')->delete($member->photo);
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        // Reset verifikasi biodata kalau ada perubahan
        if ($member->biodata_status === 'verified') {
            $data['biodata_status'] = 'pending';
            return back()->with('warning', 'Biodata diperbarui. Status verifikasi direset, Admin perlu memverifikasi ulang.');
        }

        $member->update($data);

        return back()->with('success', 'Biodata berhasil disimpan. Menunggu verifikasi dari Admin.');
    }
}