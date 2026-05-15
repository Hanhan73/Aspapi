<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::published()->latest('published_at');

        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $hasFilter = $request->hasAny(['cari', 'kategori']);
        $isFirstPage = $request->input('page', 1) == 1;

        // Page 1 tanpa filter: ambil 10 (1 featured + 9 grid)
        // Page lainnya atau ada filter: 9 saja
        $perPage = (!$hasFilter && $isFirstPage) ? 10 : 9;

        $news = $query->paginate($perPage)->withQueryString();

        $categories = News::published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('public.news.index', compact('news', 'categories'));
    }

    public function show(string $slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();

        // Increment views
        $news->increment('views');

        // Berita terkait (same category, exclude current)
        $related = News::published()
            ->where('id', '!=', $news->id)
            ->when($news->category, fn($q) => $q->where('category', $news->category))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('public.news.show', compact('news', 'related'));
    }
}