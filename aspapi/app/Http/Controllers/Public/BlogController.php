<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::published()->latest('published_at');

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

        $blogs = $query->paginate($perPage)->withQueryString();

        $categories = Blog::published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('public.blog.index', compact('blogs', 'categories'));
    }

    public function show(string $slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();

        // Increment views
        $blog->increment('views');

        // Blog terkait (same category, exclude current)
        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->category, fn($q) => $q->where('category', $blog->category))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('public.blog.show', compact('blog', 'related'));
    }
}