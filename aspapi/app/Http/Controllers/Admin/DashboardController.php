<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\News;
use App\Models\Blog;
use App\Models\Region;
use App\Models\Document;
use App\Models\Board;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalMembers'      => Member::where('status', 'active')->count(),
            'pendingMembers'    => Member::where('status', 'pending')->count(),
            'pendingMembersList'=> Member::where('status', 'pending')->latest()->take(5)->get(),
            'totalNews'         => News::where('status', 'published')->count(),
            'draftNews'         => News::where('status', 'draft')->count(),
            'latestNews'        => News::latest()->take(5)->get(),
            'totalBlogs'        => Blog::where('status', 'published')->count(),
            'draftBlogs'        => Blog::where('status', 'draft')->count(),
            'totalRegions'      => Region::where('is_active', true)->count(),
            'totalDocuments'    => Document::count(),
            'totalBoards'       => Board::where('is_active', true)->count(),
            'membersByRegion' => Region::withCount('members')
                ->having('members_count', '>', 0)
                ->orderByDesc('members_count')
                ->get(['id', 'name', 'members_count']),
            'membersNoRegion' => Member::whereNull('registered_by_region_id')->count(),

        ]);
    }
}