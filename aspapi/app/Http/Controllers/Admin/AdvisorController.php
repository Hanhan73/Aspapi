<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvisorController extends Controller
{
    public function index()
    {
        $advisors = Advisor::orderBy('sort_order')->paginate(20);
        return view('admin.advisors.index', compact('advisors'));
    }

    public function create()
    {
        return view('admin.advisors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'title'       => 'nullable|string|max:255',
            'position'    => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'bio'         => 'nullable|string',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('advisors', 'public');
        }

        Advisor::create($data);

        return redirect()->route('admin.advisors.index')
                         ->with('success', 'Dewan Penasihat berhasil ditambahkan.');
    }

    public function edit(Advisor $advisor)
    {
        return view('admin.advisors.edit', compact('advisor'));
    }

    public function update(Request $request, Advisor $advisor)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'title'       => 'nullable|string|max:255',
            'position'    => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'bio'         => 'nullable|string',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            if ($advisor->photo) Storage::disk('public')->delete($advisor->photo);
            $data['photo'] = $request->file('photo')->store('advisors', 'public');
        }

        $advisor->update($data);

        return redirect()->route('admin.advisors.index')
                         ->with('success', 'Dewan Penasihat berhasil diperbarui.');
    }

    public function destroy(Advisor $advisor)
    {
        if ($advisor->photo) Storage::disk('public')->delete($advisor->photo);
        $advisor->delete();
        return back()->with('success', 'Dewan Penasihat berhasil dihapus.');
    }
}