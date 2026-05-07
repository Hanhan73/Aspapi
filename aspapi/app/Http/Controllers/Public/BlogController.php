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

        // Filter by category
        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        // Search
        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        $blogs = $query->paginate(9)->withQueryString();

        // Daftar kategori unik untuk filter
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