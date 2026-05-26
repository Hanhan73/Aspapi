<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search     = trim($request->get('cari', ''));
        $kategori   = $request->get('kategori', '');

        // Multi-keyword: pisah dengan koma
        $keywords = $search
            ? collect(explode(',', $search))
                ->map(fn($k) => trim($k))
                ->filter(fn($k) => $k !== '')
                ->values()
                ->all()
            : [];

        $hasFilter  = !empty($keywords) || $kategori !== '';
        $isFirstPage = $request->input('page', 1) == 1;

        $query = Blog::published()->latest('published_at');

        if ($kategori) {
            $query->where('category', $kategori);
        }

        foreach ($keywords as $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title',       'like', "%{$keyword}%")
                  ->orWhere('excerpt',   'like', "%{$keyword}%")
                  ->orWhere('author_name','like', "%{$keyword}%");
            });
        }

        // Featured: hanya di halaman 1 tanpa filter
        $featured = null;
        if (!$hasFilter && $isFirstPage) {
            $featured = (clone $query)->first();
            if ($featured) {
                $query->where('id', '!=', $featured->id);
            }
        }

        // Total tanpa filter search (hanya filter kategori)
        $totalQuery = Blog::published();
        if ($kategori) $totalQuery->where('category', $kategori);
        $totalCount = $totalQuery->count();

        $blogs = $query->paginate(9)->withQueryString();

        $categories = Blog::published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('public.blog.index', compact(
            'blogs', 'categories', 'featured',
            'keywords', 'search', 'totalCount'
        ));
    }

    public function show(string $slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();
        $blog->increment('views');

        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->category, fn($q) => $q->where('category', $blog->category))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('public.blog.show', compact('blog', 'related'));
    }
}