<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Blog;
use App\Models\Member;
use App\Models\Region;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = News::where('status', 'published')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();
        $latestBlog = Blog::where('status', 'published')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $stats = [
            'members' => Member::where('status', 'active')->count(),
            'regions' => Region::where('is_active', true)->count(),
            'years'   => now()->year - 2010,
            'congress' => 4,
        ];

        return view('public.home.index', compact('latestNews', 'latestBlog', 'stats'));
    }
}
