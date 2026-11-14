<?php
// app/Http/Controllers/Public/GalleryController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\Region;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $year   = $request->integer('tahun') ?: null;
        $region = (string) $request->get('daerah', '');

        $albums = GalleryAlbum::published()
            ->with(['cover', 'firstPhoto', 'region'])
            ->withCount('photos')
            ->has('photos')
            ->when($year, fn ($q) => $q->whereYear('event_date', $year))
            ->when($region === 'pusat', fn ($q) => $q->whereNull('region_id'))
            ->when($region !== '' && $region !== 'pusat', fn ($q) => $q->where('region_id', (int) $region))
            ->latestEvent()
            ->paginate(12)
            ->withQueryString();

        $years = GalleryAlbum::published()->pluck('event_date')
            ->map(fn ($d) => $d->year)->unique()->sortDesc()->values();

        $regions = Region::whereIn('id', GalleryAlbum::published()->whereNotNull('region_id')->select('region_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('public.gallery.index', compact('albums', 'years', 'regions'));
    }

    public function show(string $slug)
    {
        $album = GalleryAlbum::published()
            ->where('slug', $slug)
            ->with(['photos', 'cover', 'region', 'agenda', 'seminar'])
            ->firstOrFail();

        $others = GalleryAlbum::published()
            ->where('id', '!=', $album->id)
            ->with(['cover', 'firstPhoto', 'region'])
            ->withCount('photos')
            ->has('photos')
            ->latestEvent()
            ->limit(3)
            ->get();

        return view('public.gallery.show', compact('album', 'others'));
    }
}
