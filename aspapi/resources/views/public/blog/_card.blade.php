<article class="card card-top-blue card-hover flex flex-col">

    {{-- Thumbnail --}}
    @if($item->thumbnail)
    <div class="h-44 overflow-hidden">
        <img src="{{ Storage::url($item->thumbnail) }}"
             alt="{{ $item->title }}"
             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"/>
    </div>
    @else
    <div class="h-44 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
        <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
    </div>
    @endif

    <div class="p-5 flex flex-col flex-1">
        {{-- Category & Date --}}
        <div class="flex items-center justify-between mb-3">
            <span class="badge badge-blue">{{ $item->category ?? 'Blog' }}</span>
            <span class="text-2xs text-neutral-300">
                {{ $item->published_at?->translatedFormat('d M Y') ?? $item->created_at->translatedFormat('d M Y') }}
            </span>
        </div>

        {{-- Title --}}
        <h3 class="text-sm font-bold text-navy leading-snug mb-2 flex-1">
            <a href="{{ route('blog.show', $item->slug) }}"
               class="hover:text-primary transition-colors">
                {{ $item->title }}
            </a>
        </h3>

        {{-- Author --}}
        @if($item->author_name)
        <p class="text-2xs text-neutral-400 font-medium mb-2">✍ {{ $item->author_name }}</p>
        @endif

        {{-- Excerpt --}}
        @if($item->excerpt)
        <p class="text-xs text-neutral-400 leading-relaxed mb-4 line-clamp-2">
            {{ $item->excerpt }}
        </p>
        @endif

        {{-- Read more --}}
        <a href="{{ route('blog.show', $item->slug) }}"
           class="text-2xs font-bold tracking-widest uppercase text-primary border-b-2 border-accent-yellow pb-0.5 w-fit hover:text-primary-600 transition-colors mt-auto">
            Baca Selengkapnya →
        </a>
    </div>
</article>