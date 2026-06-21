<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\News;
use App\Models\Region;

class SitemapController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('status', 'published')
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);

        $news = News::where('status', 'published')
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);

        $regions = Region::where('is_active', true)
            ->get(['slug', 'updated_at']);

        // Bersihkan semua output buffer yang mungkin ada
        while (ob_get_level()) {
            ob_end_clean();
        }

        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= view('sitemap', compact('blogs', 'news', 'regions'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }
}