<?php
// app/Http/Controllers/PartnerController.php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');

        $partners = Partner::active()
            ->when($category, fn($q) => $q->where('category', $category))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $categories = Partner::categories();

        return view('partners.index', compact('partners', 'categories', 'category'));
    }
}