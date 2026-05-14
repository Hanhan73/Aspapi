<?php
// app/Http/Controllers/Admin/PartnerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $search   = $request->get('search');

        // Semua partner (untuk hitung jumlah per tab)
        $allPartners = Partner::all();

        // Partner yang ditampilkan di tabel (filter kategori + search)
        $query = Partner::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $partners   = $query->orderBy('sort_order')->orderBy('name')->paginate(20)->withQueryString();
        $categories = Partner::categories(); // sudah return Collection

        return view('admin.partners.index', compact('partners', 'allPartners', 'categories'));
    }

    public function create()
    {
        $categories = Partner::categories();
        return view('admin.partners.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'category'    => 'required|in:perguruan_tinggi,sekolah,industri,pemerintahan',
            'profile'     => 'nullable|string',
            'website_url' => 'nullable|url|max:255',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partners/logos', 'public');
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')
                         ->with('success', 'Mitra berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        $categories = Partner::categories();
        return view('admin.partners.edit', compact('partner', 'categories'));
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'category'    => 'required|in:perguruan_tinggi,sekolah,industri,pemerintahan',
            'profile'     => 'nullable|string',
            'website_url' => 'nullable|url|max:255',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo) Storage::disk('public')->delete($partner->logo);
            $data['logo'] = $request->file('logo')->store('partners/logos', 'public');
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')
                         ->with('success', 'Data mitra berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo) Storage::disk('public')->delete($partner->logo);
        $partner->delete();

        return redirect()->route('admin.partners.index')
                         ->with('success', 'Mitra berhasil dihapus.');
    }

    /**
     * Reorder per kategori via drag & drop (AJAX)
     *
     * Payload: { ids: [3,1,5], category: 'industri' }
     *
     * sort_order di-reset mulai 0 hanya untuk partner dalam kategori tsb,
     * sehingga urutan kategori lain tidak terpengaruh.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'ids'      => 'required|array',
            'ids.*'    => 'integer|exists:partners,id',
            'category' => 'nullable|string',
        ]);

        foreach ($request->ids as $order => $id) {
            Partner::where('id', $id)->update(['sort_order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}