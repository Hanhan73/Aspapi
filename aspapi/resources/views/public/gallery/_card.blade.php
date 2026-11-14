{{-- Kartu album galeri. Butuh: $album (with cover, firstPhoto, region; withCount photos) --}}
@php $cover = $album->coverPhoto(); @endphp
<a href="{{ route('galleries.show', $album->slug) }}"
   class="card card-top-blue card-hover flex flex-col overflow-hidden group">

    <div class="relative w-full overflow-hidden bg-primary-50" style="aspect-ratio:4/3;">
        @if ($cover)
            <img src="{{ $cover->thumb_url }}" alt="{{ $album->title }}" loading="lazy"
                 width="{{ $cover->thumb_width }}" height="{{ $cover->thumb_height }}"
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-12 h-12 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        <span class="absolute top-2.5 left-2.5 text-white text-[0.6rem] font-bold tracking-wide px-2 py-0.5 rounded"
              style="background:rgba(26,42,58,0.75);backdrop-filter:blur(4px);">
            {{ $album->region->name ?? 'ASPAPI Pusat' }}
        </span>
        <span class="absolute bottom-2.5 right-2.5 inline-flex items-center gap-1 text-white text-[0.65rem] font-bold px-2 py-0.5 rounded"
              style="background:rgba(26,42,58,0.75);">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $album->photos_count ?? $album->photos()->count() }} foto
        </span>
    </div>

    <div class="p-5 flex flex-col flex-1">
        <div class="flex items-center gap-1.5 mb-2 text-2xs text-neutral-400 font-medium">
            <svg class="w-3.5 h-3.5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>{{ $album->dateLabel() }}</span>
            <span class="text-neutral-300">·</span>
            <span>{{ $album->activity_type }}</span>
        </div>
        <h3 class="text-sm font-bold text-navy leading-snug mb-3 flex-1 group-hover:text-primary transition-colors">
            {{ $album->title }}
        </h3>
        <span class="text-2xs font-bold tracking-widest uppercase text-primary border-b-2 border-accent-yellow pb-0.5 w-fit mt-auto">
            Lihat Album →
        </span>
    </div>
</a>
