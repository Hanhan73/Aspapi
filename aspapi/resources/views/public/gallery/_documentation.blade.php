{{--
    Section "Dokumentasi" untuk Agenda / Seminar.
    Butuh: $albums — koleksi GalleryAlbum published, eager load photos (dibatasi, mis. 4).
    Opsional: $compact (bool) — versi ringkas untuk kartu.
--}}
@php $compact = $compact ?? false; @endphp

@if ($albums->isNotEmpty())
<div class="gallery-documentation {{ $compact ? '' : 'border-t border-neutral-100 pt-4' }}">
    <p class="text-2xs font-bold tracking-widest uppercase text-primary mb-2">Dokumentasi</p>

    @foreach ($albums as $docAlbum)
    <a href="{{ route('galleries.show', $docAlbum->slug) }}" class="block group {{ $loop->last ? '' : 'mb-3' }}">
        <div class="grid grid-cols-4 gap-1.5 mb-1.5">
            @foreach ($docAlbum->photos->take(4) as $p)
                <img src="{{ $p->thumb_url }}" alt="" loading="lazy"
                     width="{{ $p->thumb_width }}" height="{{ $p->thumb_height }}"
                     class="w-full aspect-square object-cover rounded group-hover:opacity-90 transition-opacity">
            @endforeach
        </div>
        <span class="text-xs font-semibold text-navy group-hover:text-primary transition-colors">
            {{ $docAlbum->title }} →
        </span>
    </a>
    @endforeach
</div>
@endif
