<?php
// app/Http/Controllers/Public/RegionController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Region;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::where('is_active', true)
                         ->orderBy('sort_order')
                         ->orderBy('province')
                         ->get();

        return view('public.regions.index', compact('regions'));
    }

    public function show(Region $region)
    {
        abort_unless($region->is_active, 404);
        return view('public.regions.show', compact('region'));
    }
}