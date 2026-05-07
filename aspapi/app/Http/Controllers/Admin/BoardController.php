<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Board::orderBy('sort_order')->orderBy('position_category')->paginate(20);
        return view('admin.boards.index', compact('boards'));
    }

    public function create()
    {
        return view('admin.boards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'position'          => 'required|string|max:255',
            'position_category' => 'nullable|string|max:255',
            'institution'       => 'nullable|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:20',
            'period'            => 'nullable|string|max:50',
            'sort_order'        => 'nullable|integer',
            'is_active'         => 'nullable|boolean',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('boards', 'public');
        }

        Board::create($data);

        return redirect()->route('admin.boards.index')
                         ->with('success', 'Pengurus berhasil ditambahkan.');
    }

    public function edit(Board $board)
    {
        return view('admin.boards.edit', compact('board'));
    }

    public function update(Request $request, Board $board)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'position'          => 'required|string|max:255',
            'position_category' => 'nullable|string|max:255',
            'institution'       => 'nullable|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:20',
            'period'            => 'nullable|string|max:50',
            'sort_order'        => 'nullable|integer',
            'is_active'         => 'nullable|boolean',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            if ($board->photo) Storage::disk('public')->delete($board->photo);
            $data['photo'] = $request->file('photo')->store('boards', 'public');
        }

        $board->update($data);

        return redirect()->route('admin.boards.index')
                         ->with('success', 'Pengurus berhasil diperbarui.');
    }

    public function destroy(Board $board)
    {
        if ($board->photo) Storage::disk('public')->delete($board->photo);
        $board->delete();
        return back()->with('success', 'Pengurus berhasil dihapus.');
    }
}