<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Advisor;
use App\Models\Expert;

class ProfileController extends Controller
{
    public function visionMission()
    {
        return view('public.profile.vision-mission');
    }

    public function history()
    {
        return view('public.profile.history');
    }

    public function initiators()
    {
        return view('public.profile.initiators');
    }

    public function congress()
    {
        return view('public.profile.congress');
    }

    public function advisors()
    {
        $advisors = Advisor::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('public.profile.advisors', compact('advisors'));
    }

    public function experts()
    {
        $experts = Expert::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('public.profile.experts', compact('experts'));
    }

    public function board()
    {
        $boards = Board::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('position_category');

        return view('public.profile.board', compact('boards'));
    }
}
