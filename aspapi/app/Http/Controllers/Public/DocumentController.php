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
        // Cast eksplisit ke bool untuk menghindari masalah tipe integer dari DB
        if (! (bool) $document->is_public) {
            abort(403, 'Dokumen ini tidak tersedia untuk publik.');
        }

        $document->increment('downloads');

        // Coba disk 'public' dulu, fallback ke disk default
        $disk = Storage::disk('public');
        $path = $disk->path($document->file_path);

        if (! file_exists($path)) {
            // Fallback: coba path tanpa prefix 'public/'
            $stripped = preg_replace('#^public/#', '', $document->file_path);
            $path     = Storage::disk('public')->path($stripped);

            if (! file_exists($path)) {
                abort(404, 'File tidak ditemukan di server.');
            }
        }

        $fileName = $document->file_name
            ?? basename($document->file_path);

        return response()->download($path, $fileName);
    }
}