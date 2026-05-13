<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Partner;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $categories = Partner::categories();

        return view('public.partners.index', compact('partners', 'categories'));
    }
}