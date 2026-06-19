@extends('layouts.app')

@php $title = 'Daftar Anggota'; @endphp

@section('content')

{{-- ══════════════════════════════════════════════
     HERO
══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-primary-600 to-primary py-20">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-red via-accent-yellow to-accent-yellow"></div>
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -right-16 -bottom-24 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 mb-4">
            <span class="w-8 h-px bg-accent-yellow"></span>
            <span class="text-accent-yellow text-2xs font-bold tracking-widest uppercase">Keanggotaan ASPAPI</span>
        </div>
        <h1 class="font-display text-white text-4xl leading-tight mb-3">
            Daftar Anggota
        </h1>
        <p class="text-primary-200 text-sm max-w-xl leading-relaxed">
            Direktori resmi anggota ASPAPI yang telah terdaftar dari seluruh Indonesia.
        </p>

        {{-- Stats --}}
        <div class="flex flex-wrap gap-6 mt-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-white/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-accent-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-lg leading-none">{{ number_format($totalAll) }}</p>
                    <p class="text-primary-300 text-2xs mt-0.5">Total Anggota Terdaftar</p>
                </div>
            </div>
            <div class="w-px h-10 bg-white/20 hidden sm:block"></div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-white/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-accent-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-lg leading-none">{{ $totalRegions }}</p>
                    <p class="text-primary-300 text-2xs mt-0.5">ASPAPI Daerah</p>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 mt-6 text-2xs text-primary-300">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white font-medium">Anggota</span>
            <span>/</span>
            <span class="text-white font-medium">Daftar Anggota</span>
        </nav>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     FILTER & SEARCH
══════════════════════════════════════════════ --}}
<section class="py-8 bg-white border-b border-neutral-100">
    <div class="max-w-7xl mx-auto px-6">
        <form method="GET" action="{{ route('members.directory') }}" id="filter-form">
            <div class="flex flex-col sm:flex-row gap-3">

                {{-- Search nama --}}
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama anggota atau instansi..."
                           class="form-input pl-9 w-full text-sm"
                           autocomplete="off">
                </div>

                {{-- Filter ASPAPI Daerah --}}
                <div class="sm:w-64">
                    <select name="region" class="form-input w-full text-sm" onchange="this.form.submit()">
                        <option value="">Semua ASPAPI Daerah</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}"
                                    {{ request('region') == $region->id ? 'selected' : '' }}>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol cari --}}
                <button type="submit" class="btn btn-primary flex-shrink-0">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>

                @if (request('search') || request('region'))
                <a href="{{ route('members.directory') }}" class="btn flex-shrink-0 border border-neutral-200 text-neutral-500 hover:bg-neutral-50">
                    Reset
                </a>
                @endif

            </div>
        </form>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     HASIL & TABEL
══════════════════════════════════════════════ --}}
<section class="py-10 bg-neutral-50 min-h-64">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Info hasil --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                @if (request('search') || request('region'))
                    <p class="text-sm text-neutral-500">
                        Menampilkan
                        <span class="font-bold text-navy">{{ number_format($members->total()) }}</span>
                        dari
                        <span class="font-bold text-navy">{{ number_format($totalAll) }}</span>
                        anggota
                        @if (request('search'))
                            untuk pencarian "<span class="text-primary font-semibold">{{ request('search') }}</span>"
                        @endif
                        @if (request('region'))
                            @php $selectedRegion = $regions->firstWhere('id', request('region')); @endphp
                            @if ($selectedRegion)
                                di <span class="text-primary font-semibold">{{ $selectedRegion->name }}</span>
                            @endif
                        @endif
                    </p>
                @else
                    <p class="text-sm text-neutral-500">
                        Menampilkan <span class="font-bold text-navy">{{ number_format($members->total()) }}</span> anggota terdaftar
                    </p>
                @endif
            </div>
            <p class="text-2xs text-neutral-400 hidden sm:block">
                Halaman {{ $members->currentPage() }} dari {{ $members->lastPage() }}
            </p>
        </div>

        @if ($members->count())

        {{-- Tabel --}}
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-100 bg-neutral-50">
                            <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400 w-10">#</th>
                            <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400">Nama</th>
                            <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400 hidden md:table-cell">Instansi</th>
                            <th class="text-left px-5 py-3 text-2xs font-bold tracking-widest uppercase text-neutral-400">ASPAPI Daerah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-50">
                        @foreach ($members as $i => $member)
                        <tr class="hover:bg-neutral-50/70 transition-colors">
                            {{-- Nomor urut --}}
                            <td class="px-5 py-4 text-xs text-neutral-300 font-mono">
                                {{ ($members->currentPage() - 1) * $members->perPage() + $i + 1 }}
                            </td>

                            {{-- Nama --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar inisial --}}
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold"
                                         style="background: #2A7FC1;">
                                        {{ strtoupper(substr($member->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-navy text-sm leading-snug">{{ $member->full_name }}</p>
                                        @if ($member->member_number)
                                        <p class="text-2xs text-neutral-400 font-mono mt-0.5">{{ $member->member_number }}</p>
                                        @endif
                                        {{-- Instansi — muncul di mobile --}}
                                        @if ($member->institution)
                                        <p class="text-xs text-neutral-400 mt-0.5 md:hidden">{{ $member->institution }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Instansi --}}
                            <td class="px-5 py-4 hidden md:table-cell">
                                <p class="text-sm text-neutral-500">{{ $member->institution ?: '—' }}</p>
                            </td>

                            {{-- ASPAPI Daerah --}}
                            <td class="px-5 py-4">
                                @if ($member->registeredByRegion)
                                    <span class="inline-flex items-center gap-1.5 bg-primary-50 text-primary text-2xs font-bold px-2.5 py-1 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $member->registeredByRegion->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-neutral-300">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($members->hasPages())
        <div class="mt-6 flex items-center justify-between gap-4">
            <p class="text-xs text-neutral-400 hidden sm:block">
                Menampilkan {{ $members->firstItem() }}–{{ $members->lastItem() }} dari {{ number_format($members->total()) }} anggota
            </p>
            <div class="flex items-center gap-1">
                {{-- Prev --}}
                @if ($members->onFirstPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded text-neutral-300 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                @else
                    <a href="{{ $members->previousPageUrl() }}"
                       class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif

                {{-- Page numbers --}}
                @foreach ($members->getUrlRange(max(1, $members->currentPage() - 2), min($members->lastPage(), $members->currentPage() + 2)) as $page => $url)
                    @if ($page == $members->currentPage())
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded bg-primary text-white text-xs font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors text-xs">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($members->hasMorePages())
                    <a href="{{ $members->nextPageUrl() }}"
                       class="inline-flex items-center justify-center w-8 h-8 rounded border border-neutral-200 text-neutral-500 hover:bg-neutral-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded text-neutral-300 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
        </div>
        @endif

        @else
        {{-- Empty state --}}
        <div class="card p-16 text-center">
            <div class="w-16 h-16 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-neutral-500">Tidak ada anggota ditemukan</p>
            <p class="text-xs text-neutral-400 mt-1">Coba ubah kata kunci pencarian atau filter daerah</p>
            <a href="{{ route('members.directory') }}" class="btn btn-primary btn-sm mt-4">Reset Pencarian</a>
        </div>
        @endif

    </div>
</section>


{{-- ══════════════════════════════════════════════
     CTA
══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-navy py-14">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-yellow to-accent-red"></div>
    <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-white/5 pointer-events-none"
         style="clip-path: polygon(20% 0%, 100% 0%, 100% 100%, 0% 100%)"></div>

    <div class="max-w-7xl mx-auto px-6 relative text-center">
        <h2 class="font-display text-white text-3xl mb-3">Belum Terdaftar?</h2>
        <p class="text-primary-200 text-sm mb-8 max-w-lg mx-auto">
            Bergabunglah dengan komunitas profesional administrasi perkantoran Indonesia.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('members.register') }}" class="btn btn-accent-yellow btn-lg">
                Daftar Sekarang →
            </a>
            <a href="{{ route('members.types') }}" class="btn btn-lg border-2 border-white/30 text-white hover:bg-white/10">
                Lihat Jenis Anggota
            </a>
        </div>
    </div>
</section>

@endsection
