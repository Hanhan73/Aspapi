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

        $featured = null;
        if (!$hasFilter && $isFirstPage) {
            $featured = (clone $query)->first();
            if ($featured) {
                $query->where('id', '!=', $featured->id);
            }
        }

        $news = $query->paginate(9)->withQueryString();

        $categories = News::published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('public.news.index', compact('news', 'categories', 'featured'));
    }

    public function show(string $slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();

        $news->increment('views');

        $related = News::published()
            ->where('id', '!=', $news->id)
            ->when($news->category, fn($q) => $q->where('category', $news->category))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('public.news.show', compact('news', 'related'));
    }
}