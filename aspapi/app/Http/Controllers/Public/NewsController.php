<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $search   = trim($request->get('cari', ''));
        $kategori = $request->get('kategori', '');

        $keywords = $search
            ? collect(explode(',', $search))
                ->map(fn($k) => trim($k))
                ->filter(fn($k) => $k !== '')
                ->values()
                ->all()
            : [];

        $hasFilter   = !empty($keywords) || $kategori !== '';
        $isFirstPage = $request->input('page', 1) == 1;

        $query = News::published()->latest('published_at');

        if ($kategori) {
            $query->where('category', $kategori);
        }

        foreach ($keywords as $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title',   'like', "%{$keyword}%")
                  ->orWhere('excerpt','like', "%{$keyword}%");
            });
        }

        $featured = null;
        if (!$hasFilter && $isFirstPage) {
            $featured = (clone $query)->first();
            if ($featured) {
                $query->where('id', '!=', $featured->id);
            }
        }

        $totalQuery = News::published();
        if ($kategori) $totalQuery->where('category', $kategori);
        $totalCount = $totalQuery->count();

        $news = $query->paginate(9)->withQueryString();

        $categories = News::published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('public.news.index', compact(
            'news', 'categories', 'featured',
            'keywords', 'search', 'totalCount'
        ));
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