@extends('layouts.admin')
@section('title', 'Manajemen Seminar')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-navy">Seminar</h1>
            <p class="text-sm text-neutral-500 mt-0.5">Kelola seminar dan bank soal.</p>
        </div>
        <a href="{{ route('admin.seminar.create') }}"
           class="text-xs font-bold px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
            + Buat Seminar
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search + Filter --}}
    @php
        $categories     = \App\Models\Seminar::whereNotNull('category')
            ->distinct()->orderBy('category')->pluck('category');
        $activeCategory = request('category');
        $search         = request('search');
    @endphp

    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <form method="GET" action="{{ route('admin.seminar.index') }}" class="flex gap-2 flex-1">
            @if ($activeCategory)
                <input type="hidden" name="category" value="{{ $activeCategory }}">
            @endif
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari judul atau deskripsi seminar..."
                       class="w-full pl-9 pr-4 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30">
            </div>
            <button type="submit"
                    class="px-4 py-2.5 text-xs font-bold bg-primary text-white rounded-lg hover:bg-primary/90 transition flex-shrink-0">
                Cari
            </button>
            @if ($search || $activeCategory)
            <a href="{{ route('admin.seminar.index') }}"
               class="px-3 py-2.5 text-xs font-bold border border-neutral-200 rounded-lg text-neutral-500 hover:bg-neutral-50 transition flex-shrink-0">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Filter kategori --}}
    @if ($categories->isNotEmpty())
    <div class="flex items-center gap-2 flex-wrap mb-4">
        <a href="{{ route('admin.seminar.index', array_filter(['search' => $search])) }}"
           class="text-xs font-bold px-3 py-1.5 rounded-full border transition
                  {{ ! $activeCategory ? 'bg-primary text-white border-primary' : 'border-neutral-200 text-neutral-500 hover:border-primary hover:text-primary' }}">
            Semua
        </a>
        @foreach ($categories as $cat)
        <a href="{{ route('admin.seminar.index', array_filter(['category' => $cat, 'search' => $search])) }}"
           class="text-xs font-bold px-3 py-1.5 rounded-full border transition
                  {{ $activeCategory === $cat ? 'bg-primary text-white border-primary' : 'border-neutral-200 text-neutral-500 hover:border-primary hover:text-primary' }}">
            {{ $cat }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- Info hasil --}}
    <div class="flex items-center justify-between mb-3">
        <p class="text-xs text-neutral-400">
            Menampilkan
            <span class="font-semibold text-navy">{{ $seminars->firstItem() ?? 0 }}–{{ $seminars->lastItem() ?? 0 }}</span>
            dari <span class="font-semibold text-navy">{{ $seminars->total() }}</span> seminar
            @if ($search) yang cocok dengan "<span class="font-semibold">{{ $search }}</span>" @endif
            @if ($activeCategory) dalam kategori "<span class="font-semibold">{{ $activeCategory }}</span>" @endif
        </p>
        <p class="text-xs text-neutral-400">
            Halaman {{ $seminars->currentPage() }} dari {{ $seminars->lastPage() }}
        </p>
    </div>

    {{-- Tabel --}}
    <div class="bg-white border border-neutral-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm table-fixed">
            <colgroup>
                <col style="width: 35%">  {{-- Seminar --}}
                <col style="width: 11%">  {{-- Kategori --}}
                <col style="width: 6%">   {{-- Soal --}}
                <col style="width: 7%">   {{-- Peserta --}}
                <col style="width: 8%">   {{-- Passing --}}
                <col style="width: 8%">   {{-- Status --}}
                <col style="width: 25%">  {{-- Aksi --}}
            </colgroup>
            <thead>
                <tr class="border-b border-neutral-100 text-2xs font-bold uppercase tracking-wider text-neutral-400">
                    <th class="px-4 py-3 text-left">Seminar</th>
                    <th class="px-3 py-3 text-left">Kategori</th>
                    <th class="px-2 py-3 text-center">Soal</th>
                    <th class="px-2 py-3 text-center">Peserta</th>
                    <th class="px-2 py-3 text-center">Passing</th>
                    <th class="px-2 py-3 text-center">Status</th>
                    <th class="px-3 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @forelse ($seminars as $seminar)
                <tr class="hover:bg-neutral-50">
                    {{-- Seminar --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-lg overflow-hidden bg-neutral-100 flex-shrink-0">
                                <img src="{{ $seminar->thumbnail_url }}" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-navy text-sm truncate">{{ $seminar->title }}</p>
                                <p class="text-xs text-neutral-400 truncate">{{ $seminar->description }}</p>
                            </div>
                        </div>
                    </td>
                    {{-- Kategori --}}
                    <td class="px-3 py-3">
                        @if ($seminar->category)
                            <span class="text-2xs font-bold px-2 py-0.5 rounded-full bg-primary/10 text-primary">
                                {{ $seminar->category }}
                            </span>
                        @else
                            <span class="text-xs text-neutral-300">—</span>
                        @endif
                    </td>
                    <td class="px-2 py-3 text-center text-navy font-bold">{{ $seminar->questions_count }}</td>
                    <td class="px-2 py-3 text-center text-navy font-bold">{{ $seminar->enrollments_count }}</td>
                    <td class="px-2 py-3 text-center text-navy font-bold">{{ $seminar->passing_grade }}%</td>
                    <td class="px-2 py-3 text-center">
                        <span class="text-2xs font-bold px-2 py-0.5 rounded
                            {{ $seminar->is_active ? 'bg-green-50 text-green-700' : 'bg-neutral-100 text-neutral-500' }}">
                            {{ $seminar->is_active ? 'Aktif' : 'Non-aktif' }}
                        </span>
                    </td>
                    {{-- Aksi --}}
                    <td class="px-3 py-3">
                        <div class="flex justify-end items-center gap-1.5">
                            <a href="{{ route('admin.seminar.questions', $seminar) }}"
                               class="text-2xs font-bold px-2.5 py-1.5 border border-neutral-200 rounded-lg text-neutral-600 hover:bg-neutral-50 whitespace-nowrap">
                                Soal
                            </a>
                            <a href="{{ route('admin.seminar.enrollments', $seminar) }}"
                               class="text-2xs font-bold px-2.5 py-1.5 border border-neutral-200 rounded-lg text-neutral-600 hover:bg-neutral-50 whitespace-nowrap">
                                Peserta
                            </a>
                            <a href="{{ route('admin.seminar.edit', $seminar) }}"
                               class="text-2xs font-bold px-2.5 py-1.5 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 whitespace-nowrap">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.seminar.destroy', $seminar) }}"
                                  onsubmit="return confirm('Hapus seminar ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-2xs font-bold px-2.5 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 whitespace-nowrap">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-neutral-400 text-sm">
                        @if ($search || $activeCategory)
                            Tidak ada seminar yang cocok dengan pencarian.
                            <a href="{{ route('admin.seminar.index') }}" class="block mt-2 text-xs text-primary hover:underline">
                                Reset pencarian
                            </a>
                        @else
                            Belum ada seminar.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($seminars->hasPages())
        <div class="px-5 py-4 border-t border-neutral-100 flex items-center justify-between">
            <p class="text-xs text-neutral-400">Total {{ $seminars->total() }} seminar</p>
            <div class="flex items-center gap-1">
                @if ($seminars->onFirstPage())
                    <span class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-300 cursor-not-allowed">← Prev</span>
                @else
                    <a href="{{ $seminars->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                       class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-500 hover:bg-neutral-50 transition">← Prev</a>
                @endif

                @foreach ($seminars->getUrlRange(1, $seminars->lastPage()) as $page => $url)
                    @if ($page == $seminars->currentPage())
                        <span class="px-3 py-1.5 text-xs rounded-lg bg-primary text-white font-bold">{{ $page }}</span>
                    @elseif (abs($page - $seminars->currentPage()) <= 2)
                        <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                           class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-500 hover:bg-neutral-50 transition">{{ $page }}</a>
                    @elseif ($page == 1 || $page == $seminars->lastPage())
                        <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                           class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-500 hover:bg-neutral-50 transition">{{ $page }}</a>
                    @elseif (abs($page - $seminars->currentPage()) == 3)
                        <span class="px-2 text-xs text-neutral-300">...</span>
                    @endif
                @endforeach

                @if ($seminars->hasMorePages())
                    <a href="{{ $seminars->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                       class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-500 hover:bg-neutral-50 transition">Next →</a>
                @else
                    <span class="px-3 py-1.5 text-xs rounded-lg border border-neutral-200 text-neutral-300 cursor-not-allowed">Next →</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection