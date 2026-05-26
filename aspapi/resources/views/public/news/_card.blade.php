<article class="card card-top-blue card-hover flex flex-col">

{{-- Thumbnail --}}
@if($item->thumbnail)
<div class="h-56 overflow-hidden">
    <img src="{{ Storage::url($item->thumbnail) }}"
         alt="{{ $item->title }}"
         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"/>
</div>
@else
    <div class="h-44 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
        <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
        </svg>
    </div>
    @endif

    <div class="p-5 flex flex-col flex-1">
        {{-- Category & Date --}}
        <div class="flex items-center justify-between mb-3">
            <span class="badge badge-blue">{{ $item->category ?? 'Berita' }}</span>
            <span class="text-2xs text-neutral-300">
                {{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}
            </span>
        </div>

        {{-- Title --}}
        <h3 class="text-sm font-bold text-navy leading-snug mb-2 flex-1">
            <a href="{{ route('news.show', $item->slug) }}" class="hover:text-primary transition-colors">
                {{ $item->title }}
            </a>
        </h3>

        {{-- Excerpt --}}
        @if($item->excerpt)
        <p class="text-xs text-neutral-400 leading-relaxed mb-4 line-clamp-2">
            {{ $item->excerpt }}
        </p>
        @endif

        {{-- Read more --}}
        <a href="{{ route('news.show', $item->slug) }}"
            class="text-2xs font-bold tracking-widest uppercase text-primary border-b-2 border-accent-yellow pb-0.5 w-fit hover:text-primary-600 transition-colors mt-auto">
            Baca Selengkapnya →
        </a>
    </div>
</article>