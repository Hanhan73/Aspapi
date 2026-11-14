@extends('layouts.app')

@php
$title = 'Galeri Kegiatan';
$description = 'Dokumentasi foto kegiatan ASPAPI Pusat dan ASPAPI Daerah: seminar, rapat kerja, kunjungan, dan pelatihan.';
@endphp

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-primary-600 to-primary py-20">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-red via-accent-yellow to-accent-yellow"></div>
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -right-16 -bottom-24 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 mb-4">
            <span class="w-8 h-px bg-accent-yellow"></span>
            <span class="text-accent-yellow text-2xs font-bold tracking-widest uppercase">Publikasi ASPAPI</span>
        </div>
        <h1 class="font-display text-white text-4xl leading-tight mb-3">Galeri Kegiatan</h1>
        <p class="text-primary-200 text-sm max-w-xl leading-relaxed">
            Dokumentasi foto kegiatan ASPAPI Pusat dan ASPAPI Daerah di seluruh Indonesia.
        </p>
        <nav class="flex items-center gap-2 mt-6 text-2xs text-primary-300">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white font-medium">Galeri</span>
        </nav>
    </div>
</section>

{{-- FILTER --}}
<section class="py-8 bg-white border-b border-neutral-100">
    <div class="max-w-7xl mx-auto px-6">
        <form method="GET" action="{{ route('galleries.index') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="sm:w-44">
                    <select name="tahun" class="form-input w-full text-sm" onchange="this.form.submit()" aria-label="Tahun">
                        <option value="">Semua Tahun</option>
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ (string) request('tahun') === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-64">
                    <select name="daerah" class="form-input w-full text-sm" onchange="this.form.submit()" aria-label="Daerah">
                        <option value="">Semua Daerah</option>
                        <option value="pusat" {{ request('daerah') === 'pusat' ? 'selected' : '' }}>ASPAPI Pusat</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ (string) request('daerah') === (string) $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <noscript><button type="submit" class="btn btn-primary flex-shrink-0">Terapkan</button></noscript>
                @if (request('tahun') || request('daerah'))
                <a href="{{ route('galleries.index') }}" class="btn flex-shrink-0 border border-neutral-200 text-neutral-500 hover:bg-neutral-50">Reset</a>
                @endif
            </div>
        </form>
    </div>
</section>

{{-- GRID --}}
<section class="py-12 bg-neutral-50 min-h-64">
    <div class="max-w-7xl mx-auto px-6">

        <p class="text-sm text-neutral-500 mb-6">
            <span class="font-bold text-navy">{{ $albums->total() }}</span> album ditemukan
        </p>

        @if ($albums->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($albums as $album)
                @include('public.gallery._card', ['album' => $album])
            @endforeach
        </div>

        @if ($albums->hasPages())
        <div class="mt-10 flex items-center justify-center gap-1">
            @if ($albums->onFirstPage())
                <span class="inline-flex items-center justify-center w-8 h-8 rounded text-neutral-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $albums->previousPageUrl() }}" aria-label="Sebelumnya" class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            @foreach ($albums->getUrlRange(max(1, $albums->currentPage()-2), min($albums->lastPage(), $albums->currentPage()+2)) as $page => $url)
                @if ($page == $albums->currentPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded bg-primary text-white text-xs font-bold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors text-xs">{{ $page }}</a>
                @endif
            @endforeach

            @if ($albums->hasMorePages())
                <a href="{{ $albums->nextPageUrl() }}" aria-label="Berikutnya" class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-8 h-8 rounded text-neutral-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
        @endif

        @else
        <div class="card p-16 text-center">
            <div class="w-16 h-16 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-neutral-500">Belum ada album</p>
            <p class="text-xs text-neutral-400 mt-1">Dokumentasi kegiatan akan tampil di sini.</p>
        </div>
        @endif

    </div>
</section>

@endsection
