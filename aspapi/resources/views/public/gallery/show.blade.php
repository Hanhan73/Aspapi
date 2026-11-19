@extends('layouts.app')

@php
    $title           = $album->title;
    $metaDescription = $album->metaDescription();
    $ogType          = 'article';
    $ogImage         = \App\Services\OgImage::url($album->coverPhoto()?->path);
@endphp

@push('styles')
<style>
    /* Caption lightbox (PhotoSwipe dynamic caption) */
    .pswp__dynamic-caption { color: #fff; max-width: 52rem; }
    .pswp__dynamic-caption .gallery-caption p + p { margin-top: .15rem; }
    .pswp__dynamic-caption--mobile { background: rgba(10, 18, 28, .85); padding: .75rem 1rem; }
</style>
@endpush

@section('content')

{{-- HEADER KEGIATAN --}}
<section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-primary-600 to-primary py-16">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-red via-accent-yellow to-accent-yellow"></div>
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">
        <nav class="flex items-center gap-2 mb-5 text-2xs text-primary-300">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('galleries.index') }}" class="hover:text-white transition-colors">Galeri</a>
            <span>/</span>
            <span class="text-white font-medium line-clamp-1">{{ Str::limit($album->title, 50) }}</span>
        </nav>

        <div class="inline-flex items-center gap-2 mb-3">
            <span class="w-8 h-px bg-accent-yellow"></span>
            <span class="text-accent-yellow text-2xs font-bold tracking-widest uppercase">{{ $album->activity_type }}</span>
        </div>
        <h1 class="font-display text-white text-3xl md:text-4xl leading-tight mb-4 max-w-4xl">{{ $album->title }}</h1>

        <dl class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-primary-100">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-accent-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <dt class="sr-only">Tanggal</dt><dd>{{ $album->dateLabel() }}</dd>
            </div>
            @if ($album->place)
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-accent-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <dt class="sr-only">Tempat</dt><dd>{{ $album->place }}</dd>
            </div>
            @endif
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-accent-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <dt class="sr-only">Daerah</dt><dd>{{ $album->region->name ?? 'ASPAPI Pusat' }}</dd>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-accent-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <dt class="sr-only">Jumlah foto</dt><dd>{{ $album->photos->count() }} foto</dd>
            </div>
        </dl>

        @if ($album->agenda || $album->seminar)
        <div class="mt-4 flex flex-wrap gap-2">
            @if ($album->agenda)
                <a href="{{ route('public.agenda', ['search' => $album->agenda->title]) }}"
                   class="inline-flex items-center gap-1.5 text-2xs font-bold px-2.5 py-1 rounded bg-white/10 text-white hover:bg-white/20 transition-colors">
                    Agenda: {{ Str::limit($album->agenda->title, 60) }}
                </a>
            @endif
            @if ($album->seminar)
                <a href="{{ route('public.seminars', ['search' => $album->seminar->title]) }}"
                   class="inline-flex items-center gap-1.5 text-2xs font-bold px-2.5 py-1 rounded bg-white/10 text-white hover:bg-white/20 transition-colors">
                    Seminar: {{ Str::limit($album->seminar->title, 60) }}
                </a>
            @endif
        </div>
        @endif
    </div>
</section>

{{-- DESKRIPSI + GRID FOTO --}}
<section class="py-12 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">

        @if ($album->description)
        <div class="max-w-3xl mb-8 text-sm text-neutral-600 leading-relaxed whitespace-pre-line">{{ $album->description }}</div>
        @endif

        @if ($album->photos->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-3" data-pswp-gallery>
            @foreach ($album->photos as $photo)
            <a href="{{ $photo->url }}"
               data-pswp-width="{{ $photo->width }}"
               data-pswp-height="{{ $photo->height }}"
               data-cropped="true"
               class="block relative overflow-hidden rounded-md bg-primary-50 group focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
               style="aspect-ratio:4/3;"
               aria-label="Buka foto {{ $loop->iteration }} dari {{ $loop->count }}">
                <img src="{{ $photo->thumb_url }}" alt="{{ $photo->note ?: $album->title . ' — foto ' . $loop->iteration }}"
                     loading="lazy" decoding="async"
                     width="{{ $photo->thumb_width }}" height="{{ $photo->thumb_height }}"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="pswp-caption-content" hidden>
                    <x-gallery-caption :album="$album" :photo="$photo" />
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="card p-16 text-center text-sm text-neutral-400">Belum ada foto di album ini.</div>
        @endif

        <div class="mt-10">
            <a href="{{ route('galleries.index') }}" class="text-xs font-bold tracking-widest uppercase text-primary border-b-2 border-accent-yellow pb-0.5 hover:text-primary-600 transition-colors">
                ← Semua Album
            </a>
        </div>
    </div>
</section>

{{-- ALBUM LAIN --}}
@if ($others->isNotEmpty())
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-8">
            <p class="section-label">Galeri</p>
            <h2 class="section-title mt-1">Album Lainnya</h2>
            <div class="section-divider"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($others as $other)
                @include('public.gallery._card', ['album' => $other])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
    @vite('resources/js/gallery.js')
@endpush
