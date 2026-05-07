<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpertController extends Controller
{
    public function index()
    {
        $experts = Expert::orderBy('sort_order')->paginate(20);
        return view('admin.experts.index', compact('experts'));
    }

    public function create()
    {
        return view('admin.experts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'title'       => 'nullable|string|max:255',
            'expertise'   => 'nullable|string|max:255',
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
            $data['photo'] = $request->file('photo')->store('experts', 'public');
        }

        Expert::create($data);

        return redirect()->route('admin.experts.index')
                         ->with('success', 'Dewan Pakar berhasil ditambahkan.');
    }

    public function edit(Expert $expert)
    {
        return view('admin.experts.edit', compact('expert'));
    }

    public function update(Request $request, Expert $expert)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'title'       => 'nullable|string|max:255',
            'expertise'   => 'nullable|string|max:255',
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
            if ($expert->photo) Storage::disk('public')->delete($expert->photo);
            $data['photo'] = $request->file('photo')->store('experts', 'public');
        }

        $expert->update($data);

        return redirect()->route('admin.experts.index')
                         ->with('success', 'Dewan Pakar berhasil diperbarui.');
    }

    public function destroy(Expert $expert)
    {
        if ($expert->photo) Storage::disk('public')->delete($expert->photo);
        $expert->delete();
        return back()->with('success', 'Dewan Pakar berhasil dihapus.');
    }
}