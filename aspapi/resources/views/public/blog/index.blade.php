@extends('layouts.app')

@section('content')

{{-- PAGE HERO --}}
<section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-primary-600 to-primary py-16">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-red via-accent-yellow to-accent-yellow"></div>
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -right-16 -bottom-24 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="relative max-w-7xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 mb-4">
            <span class="w-8 h-px bg-accent-yellow"></span>
            <span class="text-accent-yellow text-2xs font-bold tracking-widest uppercase">Artikel &amp; Opini</span>
        </div>
        <h1 class="font-display text-white text-4xl leading-tight mb-3">Blog ASPAPI</h1>
        <p class="text-primary-200 text-sm leading-relaxed max-w-xl">
            Kumpulan artikel, opini, dan tulisan dari anggota serta praktisi administrasi perkantoran Indonesia.
        </p>
    </div>
</section>

{{-- FILTER & SEARCH BAR --}}
<section class="bg-white border-b border-neutral-200 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <form method="GET" action="{{ route('blog.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-300" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="cari" value="{{ $search }}"
                    placeholder="Cari artikel... pisah kata kunci dengan koma"
                    class="w-full pl-9 pr-4 py-2 text-sm border border-neutral-200 rounded focus:outline-none focus:border-primary text-navy placeholder-neutral-300" />
            </div>

            @if($categories->isNotEmpty())
            <select name="kategori"
                class="py-2 px-3 text-sm border border-neutral-200 rounded focus:outline-none focus:border-primary text-navy">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('kategori') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @endif

            <button type="submit" class="btn btn-primary text-sm px-5 py-2">Cari</button>

            @if($search || request('kategori'))
            <a href="{{ route('blog.index') }}"
                class="text-xs text-neutral-400 hover:text-accent-red transition-colors">Reset ×</a>
            @endif
        </form>

        @if(!empty($keywords))
        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-neutral-500">
            <span>Hasil pencarian:</span>
            @foreach($keywords as $kw)
            <span
                class="inline-flex items-center gap-1 px-2 py-0.5 bg-primary/10 text-primary font-semibold rounded-full">{{ $kw }}</span>
            @if(!$loop->last)<span class="text-neutral-400 font-bold text-2xs">+</span>@endif
            @endforeach
            <span class="text-neutral-400">→</span>
            <span
                class="font-bold {{ $blogs->total() > 0 ? 'text-primary' : 'text-neutral-400' }}">{{ $blogs->total() }}</span>
            <span>dari {{ $totalCount }} artikel</span>
        </div>
        @endif
    </div>
</section>

{{-- BLOG GRID --}}
<section class="py-16 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">

        @if(!$featured && $blogs->isEmpty())
        <div class="text-center py-20">
            <svg class="w-14 h-14 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <p class="text-neutral-400 text-sm mb-4">Belum ada artikel yang ditemukan.</p>
            <a href="{{ route('blog.index') }}" class="btn btn-outline">Lihat Semua Artikel</a>
        </div>
        @else

        {{-- Featured — hanya tampil di page 1 tanpa filter --}}
        @if($featured && $isFirstPage)
        <article class="card card-top-blue card-hover mb-8 overflow-hidden grid grid-cols-1 lg:grid-cols-2">
            @if($featured->thumbnail)
            <div class="h-64 lg:h-auto overflow-hidden">
                <img src="{{ Storage::url($featured->thumbnail) }}" alt="{{ $featured->title }}"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
            </div>
            @else
            <div
                class="h-64 lg:h-auto bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                <svg class="w-14 h-14 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            @endif
            <div class="p-8 flex flex-col justify-center">
                <div class="flex items-center gap-3 mb-4">
                    <span class="badge badge-blue">{{ $featured->category ?? 'Blog' }}</span>
                    <span class="text-2xs text-neutral-300">
                        {{ $featured->published_at?->translatedFormat('d F Y') ?? $featured->created_at->translatedFormat('d F Y') }}
                    </span>
                </div>
                <h2 class="font-display text-navy text-2xl leading-snug mb-3">
                    <a href="{{ route('blog.show', $featured->slug) }}" class="hover:text-primary transition-colors">
                        {{ $featured->title }}
                    </a>
                </h2>
                @if($featured->author_name)
                <p class="text-2xs text-neutral-400 font-medium mb-3">✍ {{ $featured->author_name }}</p>
                @endif
                @if($featured->excerpt)
                <p class="text-sm text-neutral-500 leading-relaxed mb-6 line-clamp-3">{{ $featured->excerpt }}</p>
                @endif
                <a href="{{ route('blog.show', $featured->slug) }}"
                    class="text-2xs font-bold tracking-widest uppercase text-primary border-b-2 border-accent-yellow pb-0.5 w-fit hover:text-primary-600 transition-colors">
                    Baca Selengkapnya →
                </a>
            </div>
        </article>
        @endif

        {{-- Grid --}}
        @if($blogs->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($blogs as $item)
            @include('public.blog._card', ['item' => $item])
            @endforeach
        </div>
        @endif

        {{-- Pagination --}}
        <div class="mt-12 flex flex-col items-center gap-2">
            @if($blogs->hasPages())
            <div class="flex items-center gap-1">
                @if($blogs->onFirstPage())
                <span
                    class="px-3 py-2 text-sm text-neutral-300 border border-neutral-200 rounded cursor-not-allowed">←</span>
                @else
                <a href="{{ $blogs->previousPageUrl() }}"
                    class="px-3 py-2 text-sm text-navy border border-neutral-200 rounded hover:border-primary hover:text-primary transition-colors">←</a>
                @endif

                @foreach($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                @if($page == $blogs->currentPage())
                <span
                    class="px-3 py-2 text-sm font-bold text-white bg-primary border border-primary rounded">{{ $page }}</span>
                @else
                <a href="{{ $url }}"
                    class="px-3 py-2 text-sm text-navy border border-neutral-200 rounded hover:border-primary hover:text-primary transition-colors">{{ $page }}</a>
                @endif
                @endforeach

                @if($blogs->hasMorePages())
                <a href="{{ $blogs->nextPageUrl() }}"
                    class="px-3 py-2 text-sm text-navy border border-neutral-200 rounded hover:border-primary hover:text-primary transition-colors">→</a>
                @else
                <span
                    class="px-3 py-2 text-sm text-neutral-300 border border-neutral-200 rounded cursor-not-allowed">→</span>
                @endif
            </div>
            @endif

            @php
            if ($totalCount > $blogs->total()) {
            $displayFrom = $isFirstPage ? 1 : $blogs->firstItem() + 1;
            $displayTo = min($blogs->lastItem() + 1, $totalCount);
            } else {
            $displayFrom = $blogs->firstItem();
            $displayTo = $blogs->lastItem();
            }
            @endphp
            <p class="text-xs text-neutral-400">
                Menampilkan {{ $displayFrom }}–{{ $displayTo }} dari {{ $totalCount }} artikel
            </p>
        </div>

    </div>
</section>

@endsection