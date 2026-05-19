<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $activeTab = $request->get('tab');

        $categories = Partner::categories();

        // Build paginated results per category
        $partners = collect();
        foreach ($categories as $key => $label) {
            $query = Partner::active()
                ->where('category', $key)
                ->orderBy('sort_order')
                ->orderBy('name');

            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            $paginated = $query->paginate(20, ['*'], 'page_' . $key)
                               ->withQueryString()
                               ->appends(['tab' => $key]);

            if ($paginated->total() > 0) {
                $partners->put($key, $paginated);
            }
        }

        // Default active tab: URL param, or first tab with results
        if (!$activeTab || !$partners->has($activeTab)) {
            $activeTab = $partners->keys()->first() ?? '';
        }

        return view('public.partners.index', compact('partners', 'categories', 'search', 'activeTab'));
    }
}