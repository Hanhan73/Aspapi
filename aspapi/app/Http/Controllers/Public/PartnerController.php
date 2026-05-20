<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search     = trim($request->get('search', ''));
        $activeTab  = $request->get('tab');
        $categories = Partner::categories();

        // Pecah input berdasarkan koma → array kata kunci, buang yang kosong
        // Contoh: "swasta, Jawa Barat" → ['swasta', 'Jawa Barat']
        $keywords = $search
            ? collect(explode(',', $search))
            ->map(fn($k) => trim($k))
            ->filter(fn($k) => $k !== '')
            ->values()
            ->all()
            : [];

        // Total per kategori tanpa filter search
        $totalCounts = Partner::active()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Default active tab
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

            // AND search: setiap keyword harus ada di name ATAU profile
            // Diterapkan hanya pada tab aktif
            if (!empty($keywords) && $key === $activeTab) {
                foreach ($keywords as $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('profile', 'like', '%' . $keyword . '%');
                    });
                }
            }

            $paginated = $query->paginate(20, ['*'], 'page_' . $key)
                ->withQueryString()
                ->appends(['tab' => $key]);

            $partners->put($key, $paginated);
        }

        return view('public.partners.index', compact(
            'partners',
            'categories',
            'search',
            'keywords',
            'activeTab',
            'totalCounts'
        ));
    }
}
