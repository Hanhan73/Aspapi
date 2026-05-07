<?php
// app/Http/Controllers/Admin/DocumentAdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentAdminController extends Controller
{
    public function index()
    {
        $documents = Document::latest()->paginate(15);
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'is_public'   => 'boolean',
            'file'        => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:20480',
        ]);

        $file      = $request->file('file');
        $fileName  = $file->getClientOriginalName();
        $filePath  = $file->store('documents', 'public');
        $fileSize = $file->getSize();
        $fileType  = strtoupper($file->getClientOriginalExtension());

        Document::create([
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
            'is_public'   => $request->boolean('is_public', true),
            'file_path'   => $filePath,
            'file_name'   => $fileName,
            'file_size'   => $fileSize,
            'file_type'   => $fileType,
        ]);

        return redirect()->route('admin.documents.index')
                         ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(Document $document)
    {
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'is_public'   => 'boolean',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:20480',
        ]);

        $data = [
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
            'is_public'   => $request->boolean('is_public', true),
        ];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($document->file_path);

            $file              = $request->file('file');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $file->store('documents', 'public');
            $data['file_size'] = $file->getSize();
            $data['file_type'] = strtoupper($file->getClientOriginalExtension());
        }

        $document->update($data);

        return redirect()->route('admin.documents.index')
                        ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('admin.documents.index')
                        ->with('success', 'Dokumen berhasil dihapus.');
    }

    // Halaman sort
    public function sortIndex()
    {
        // Ambil semua dokumen, kelompokkan per kategori, urut by sort_order
        $documents = Document::orderBy('sort_order')->orderBy('id')->get()
                            ->groupBy(fn($d) => $d->category ?? 'Umum');

        // Urutan kategori — ambil dari urutan sort_order terendah per kategori
        $categoryOrder = Document::selectRaw('COALESCE(category, "Umum") as cat, MIN(sort_order) as min_order')
                                ->groupBy('cat')
                                ->orderBy('min_order')
                                ->pluck('cat');

        // Susun ulang $documents mengikuti $categoryOrder
        $sorted = collect();
        foreach ($categoryOrder as $cat) {
            if ($documents->has($cat)) {
                $sorted[$cat] = $documents[$cat];
            }
        }
        // Tambahkan kategori yang belum ada di urutan
        foreach ($documents as $cat => $docs) {
            if (!$sorted->has($cat)) $sorted[$cat] = $docs;
        }

        return view('admin.documents.sort', ['documents' => $sorted]);
    }

    // Simpan urutan dokumen dalam 1 kategori
    public function sortDocuments(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);

        foreach ($request->order as $position => $id) {
            Document::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['success' => true]);
    }

    // Simpan urutan kategori (update sort_order semua dok dalam kategori tsb)
    public function sortCategories(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'string']);

        // Beri offset besar antar kategori supaya tidak overlap
        foreach ($request->order as $catPosition => $catName) {
            $baseOffset = $catPosition * 1000;
            $docs = Document::where(function ($q) use ($catName) {
                if ($catName === 'Umum') {
                    $q->whereNull('category')->orWhere('category', 'Umum');
                } else {
                    $q->where('category', $catName);
                }
            })->orderBy('sort_order')->get();

            foreach ($docs as $i => $doc) {
                $doc->update(['sort_order' => $baseOffset + $i]);
            }
        }

        return response()->json(['success' => true]);
    }
}