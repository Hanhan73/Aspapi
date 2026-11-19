<?php
// app/Http/Controllers/Admin/GalleryAdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\GalleryAlbum;
use App\Models\Region;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));
        $year   = $request->integer('year') ?: null;
        $region = $request->get('region', '');

        $query = GalleryAlbum::with(['cover', 'firstPhoto', 'region'])
            ->withCount('photos')
            ->latestEvent();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('place', 'like', "%{$search}%")
                  ->orWhere('activity_type', 'like', "%{$search}%");
            });
        }

        if ($year) {
            $query->whereYear('event_date', $year);
        }

        if ($region === 'pusat') {
            $query->whereNull('region_id');
        } elseif ($region !== '') {
            $query->where('region_id', (int) $region);
        }

        $albums  = $query->paginate(15)->withQueryString();
        $years   = $this->availableYears();
        $regions = Region::orderBy('name')->get(['id', 'name']);

        return view('admin.gallery.index', compact('albums', 'years', 'regions'));
    }

    public function create()
    {
        return view('admin.gallery.create', $this->formOptions());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = GalleryAlbum::uniqueSlug($data['slug'] ?: $data['title']);

        $album = GalleryAlbum::create($data);

        return redirect()->route('admin.gallery.photos.index', $album)
                         ->with('success', 'Album berhasil dibuat. Silakan tambahkan foto.');
    }

    public function edit(GalleryAlbum $album)
    {
        $album->loadCount('photos');

        return view('admin.gallery.edit', array_merge(['album' => $album], $this->formOptions()));
    }

    public function update(Request $request, GalleryAlbum $album)
    {
        $data = $this->validated($request, $album);
        $data['slug'] = GalleryAlbum::uniqueSlug($data['slug'] ?: $data['title'], $album->id);

        $album->update($data);

        return redirect()->route('admin.gallery.index')
                         ->with('success', 'Album berhasil diperbarui.');
    }

    public function destroy(GalleryAlbum $album)
    {
        $album->delete(); // file foto & thumbnail dihapus di GalleryAlbum::booted()

        return redirect()->route('admin.gallery.index')
                         ->with('success', 'Album beserta fotonya berhasil dihapus.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function validated(Request $request, ?GalleryAlbum $album = null): array
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => ['nullable', 'string', 'max:255', 'alpha_dash',
                                 Rule::unique('gallery_albums', 'slug')->ignore($album?->id)],
            'activity_type'  => 'nullable|string|max:100',
            'event_date'     => 'required|date',
            'event_date_end' => 'nullable|date|after_or_equal:event_date',
            'place'          => 'nullable|string|max:255',
            'region_id'      => 'nullable|integer|exists:regions,id',
            'agenda_id'      => 'nullable|integer|exists:agendas,id',
            'seminar_id'     => 'nullable|integer|exists:seminars,id',
            'description'    => 'nullable|string|max:5000',
            'sort_order'     => 'nullable|integer|min:0|max:65535',
        ], [
            'event_date_end.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $data['slug']          = isset($data['slug']) ? Str::slug($data['slug']) : '';
        $data['activity_type'] = trim($data['activity_type'] ?? '') ?: GalleryAlbum::DEFAULT_ACTIVITY_TYPE;
        $data['sort_order']    = $data['sort_order'] ?? 0;
        $data['is_published']  = $request->boolean('is_published');

        return $data;
    }

    private function formOptions(): array
    {
        return [
            'regions'  => Region::orderBy('name')->get(['id', 'name']),
            'agendas'  => Agenda::with('region:id,name')->orderByDesc('event_date')->get(['id', 'title', 'event_date', 'region_id']),
            'seminars' => Seminar::orderByDesc('created_at')->get(['id', 'title']),
            'activityTypes' => GalleryAlbum::activityTypes(),
        ];
    }

    private function availableYears()
    {
        return GalleryAlbum::query()
            ->pluck('event_date')
            ->map(fn ($d) => $d->year)
            ->unique()
            ->sortDesc()
            ->values();
    }
}
