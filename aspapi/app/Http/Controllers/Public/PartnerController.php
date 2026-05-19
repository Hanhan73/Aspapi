<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
{
    $search    = $request->get('search');
    $activeTab = $request->get('tab');
    $categories = Partner::categories();

    // Total per kategori tanpa filter search
    $totalCounts = Partner::active()
        ->selectRaw('category, count(*) as total')
        ->groupBy('category')
        ->pluck('total', 'category');

    // Default active tab sebelum loop
    if (!$activeTab || !$totalCounts->has($activeTab)) {
        $activeTab = $totalCounts->keys()->first() ?? '';
    }

    // Build paginated results per kategori
    $partners = collect();
    foreach ($categories as $key => $label) {
        if ($totalCounts->get($key, 0) === 0) continue;

        $query = Partner::active()
            ->where('category', $key)
            ->orderBy('sort_order')
            ->orderBy('name');

        // Search hanya diterapkan pada tab/kategori yang aktif
        if ($search && $key === $activeTab) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('profile', 'like', '%' . $search . '%');
            });
        }

        $paginated = $query->paginate(20, ['*'], 'page_' . $key)
                           ->withQueryString()
                           ->appends(['tab' => $key]);

        $partners->put($key, $paginated);
    }

    return view('public.partners.index', compact('partners', 'categories', 'search', 'activeTab', 'totalCounts'));
}
}