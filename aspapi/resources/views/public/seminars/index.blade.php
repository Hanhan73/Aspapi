@extends('layouts.app')

@php
$title = 'Seminar & Pelatihan';
$description = 'Ikuti program seminar dan pelatihan ASPAPI untuk meningkatkan kompetensi di bidang administrasi perkantoran. Dapatkan sertifikat resmi ASPAPI.';
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
        <h1 class="font-display text-white text-4xl leading-tight mb-3">Seminar & Pelatihan</h1>
        <p class="text-primary-200 text-sm max-w-xl leading-relaxed">
            Program pengembangan kompetensi untuk anggota dan praktisi administrasi perkantoran Indonesia.
            Daftar dan ikuti seminar untuk mendapatkan sertifikat resmi ASPAPI.
        </p>
        <nav class="flex items-center gap-2 mt-6 text-2xs text-primary-300">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white font-medium">Seminar & Pelatihan</span>
        </nav>
    </div>
</section>

{{-- INFO BANNER --}}
<div class="bg-primary-50 border-b border-primary-100">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-start gap-3">
            <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-primary-700 leading-relaxed">
                Untuk mendaftar dan mengikuti seminar, Anda harus menjadi
                <strong>anggota aktif ASPAPI</strong> yang telah membayar iuran tahunan.
                <a href="{{ route('members.register') }}" class="font-bold underline underline-offset-2 hover:text-primary-600">Daftar sebagai anggota →</a>
            </p>
        </div>
    </div>
</div>

{{-- FILTER & SEARCH --}}
<section class="py-8 bg-white border-b border-neutral-100">
    <div class="max-w-7xl mx-auto px-6">
        <form method="GET" action="{{ route('public.seminars') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul seminar..."
                           class="form-input pl-9 w-full text-sm" autocomplete="off">
                </div>

                @if ($categories->isNotEmpty())
                <div class="sm:w-56">
                    <select name="category" class="form-input w-full text-sm" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <button type="submit" class="btn btn-primary flex-shrink-0">Cari</button>

                @if (request('search') || request('category'))
                <a href="{{ route('public.seminars') }}" class="btn flex-shrink-0 border border-neutral-200 text-neutral-500 hover:bg-neutral-50">Reset</a>
                @endif
            </div>
        </form>
    </div>
</section>

{{-- GRID --}}
<section class="py-12 bg-neutral-50 min-h-64">
    <div class="max-w-7xl mx-auto px-6">

        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-neutral-500">
                Menampilkan <span class="font-bold text-navy">{{ $seminars->total() }}</span> seminar
                @if (request('search'))
                    untuk "<span class="text-primary font-semibold">{{ request('search') }}</span>"
                @endif
            </p>
        </div>

        @if ($seminars->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($seminars as $seminar)
            @php
                $hasDesc = !empty(trim(strip_tags($seminar->description ?? '')));
                $isLong  = strlen(strip_tags($seminar->description ?? '')) > 300;
            @endphp
            <div class="bg-white border border-neutral-200 rounded-xl overflow-hidden flex flex-col shadow-sm hover:shadow-md transition-shadow card-top-blue">

                {{-- Thumbnail --}}
                <div style="position:relative;width:100%;padding-top:100%;overflow:hidden;background:#EEF4FB;flex-shrink:0;">
                    @if ($seminar->thumbnail)
                        <img src="{{ Storage::url($seminar->thumbnail) }}"
                             style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;display:block;"
                             alt="{{ $seminar->title }}">
                    @else
                        <div style="position:absolute;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:40px;height:40px;color:#B0CCDF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    @endif

                    @if ($seminar->category)
                    <div style="position:absolute;top:10px;left:10px;">
                        <span style="background:rgba(26,42,58,0.75);color:#fff;font-size:0.6rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:3px 8px;border-radius:4px;">
                            {{ $seminar->category }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <h3 class="font-bold text-navy text-sm leading-snug mb-1.5">{{ $seminar->title }}</h3>

                    {{-- Deskripsi: render HTML dari rich editor --}}
                    @if ($hasDesc)
                    <div x-data="{ open: false }" class="mb-3 flex-1">
                        {{-- Collapsed: max 3 baris --}}
                        <div x-show="!open"
                        x-cloak
                             class="text-xs text-neutral-500 leading-relaxed rich-output"
                             style="overflow:hidden;display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;">
                            {!! $seminar->description !!}
                        </div>
                        {{-- Expanded: full --}}
                        <div x-show="open"
                             x-cloak
                             class="text-xs text-neutral-500 leading-relaxed rich-output">
                            {!! $seminar->description !!}
                        </div>
                        @if ($isLong)
                        <button @click="open = !open"
                                type="button"
                                class="mt-1 text-2xs font-bold"
                                style="color:#2A7FC1;background:none;border:none;cursor:pointer;padding:0;line-height:1.4;">
                            <span x-text="open ? '↑ Tutup' : '↓ Selengkapnya'">↓ Selengkapnya</span>
                        </button>
                        @endif
                    </div>
                    @else
                    <div class="flex-1 mb-3"></div>
                    @endif

                    {{-- Stats --}}
                    <div class="flex items-center gap-3 pb-3 border-b border-neutral-100 mb-3">
                        <span class="flex items-center gap-1 text-2xs text-neutral-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-semibold text-navy">{{ $seminar->questions_count }}</span> soal
                        </span>
                        <span class="flex items-center gap-1 text-2xs text-neutral-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Passing <span class="font-semibold text-navy">{{ $seminar->passing_grade }}%</span>
                        </span>
                    </div>

                    {{-- CTA --}}
                    <a href="{{ route('login') }}"
                       class="block text-center text-xs font-bold py-2 px-4 rounded-lg bg-primary text-white hover:bg-primary/90 transition">
                        Login untuk Mendaftar →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($seminars->hasPages())
        <div class="mt-10 flex items-center justify-center gap-1">
            @if ($seminars->onFirstPage())
                <span class="inline-flex items-center justify-center w-8 h-8 rounded text-neutral-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $seminars->previousPageUrl() }}" class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            @foreach ($seminars->getUrlRange(max(1, $seminars->currentPage()-2), min($seminars->lastPage(), $seminars->currentPage()+2)) as $page => $url)
                @if ($page == $seminars->currentPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded bg-primary text-white text-xs font-bold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors text-xs">{{ $page }}</a>
                @endif
            @endforeach

            @if ($seminars->hasMorePages())
                <a href="{{ $seminars->nextPageUrl() }}" class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-neutral-500">Belum ada seminar tersedia</p>
            <p class="text-xs text-neutral-400 mt-1">Pantau terus halaman ini untuk program seminar terbaru.</p>
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-navy py-14">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-yellow to-accent-red"></div>
    <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-white/5 pointer-events-none" style="clip-path: polygon(20% 0%, 100% 0%, 100% 100%, 0% 100%)"></div>
    <div class="max-w-7xl mx-auto px-6 relative text-center">
        <h2 class="font-display text-white text-3xl mb-3">Siap Mengikuti Seminar?</h2>
        <p class="text-primary-200 text-sm mb-8 max-w-lg mx-auto">
            Login ke portal member untuk mendaftar dan mengikuti seminar ASPAPI.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('login') }}" class="btn btn-accent-yellow btn-lg">Login ke Portal →</a>
            <a href="{{ route('members.register') }}" class="btn btn-lg border-2 border-white/30 text-white hover:bg-white/10">Daftar Jadi Anggota</a>
        </div>
    </div>
</section>

@endsection