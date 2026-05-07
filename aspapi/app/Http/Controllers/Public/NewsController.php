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

        // Filter by category
        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        // Search
        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate(9)->withQueryString();

        // Daftar kategori unik untuk filter
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