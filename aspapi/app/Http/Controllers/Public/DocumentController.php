<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::where('is_public', true)
                         ->orderBy('sort_order')
                         ->orderBy('id');

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        $documents = $query->get()
                           ->groupBy(fn($doc) => $doc->category ?? 'Umum');

        // Urutan kategori mengikuti sort_order dokumen pertama di tiap kategori
        $documents = $documents->sortBy(function ($docs) {
            return $docs->first()->sort_order;
        });

        $categories = Document::where('is_public', true)
                               ->whereNotNull('category')
                               ->distinct()
                               ->orderBy('category')
                               ->pluck('category');

        return view('public.documents.index', compact('documents', 'categories'));
    }

    public function download(Document $document)
    {
        if (!$document->is_public) {
            abort(403);
        }

        // Nama kolom di migration adalah 'downloads'
        $document->increment('downloads');

        $path = Storage::disk('public')->path($document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($path, $document->file_name);
    }
}