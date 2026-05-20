@extends('layouts.app')

@php $title = 'Download Dokumen'; @endphp

@section('content')

{{-- ── HERO ── --}}
<section class="bg-navy text-white py-20">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl">
            <p class="text-2xs font-bold tracking-[0.25em] uppercase text-primary-300 mb-4">
                Pusat Dokumen
            </p>
            <h1 class="text-5xl font-display font-bold leading-tight text-primary-200 mb-5">
                Download<br>Dokumen
            </h1>
            <p class="text-neutral-400 text-base leading-relaxed max-w-lg">
                Unduh dokumen resmi ASPAPI — AD/ART, panduan keanggotaan,
                formulir, dan materi organisasi lainnya.
            </p>
        </div>
    </div>
</section>

{{-- ── FILTER BAR ── --}}
<div class="bg-white border-b border-neutral-200 sticky top-0 z-20 shadow-sm">
    <div class="container mx-auto px-6 py-4">
        <form method="GET" action="{{ route('documents.index') }}"
              class="flex flex-wrap items-center gap-3">

            <div class="relative flex-1 min-w-[240px]">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-300 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari dokumen..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm text-navy border border-neutral-200 rounded-lg bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-neutral-300">
            </div>

            @if($categories->isNotEmpty())
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('documents.index', request()->except('kategori')) }}"
                   class="px-3.5 py-2 text-xs font-semibold rounded-lg border transition-all
                       {{ !request('kategori') ? 'bg-navy text-white border-navy' : 'bg-white text-neutral-500 border-neutral-200 hover:border-navy hover:text-navy' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('documents.index', array_merge(request()->except('kategori'), ['kategori' => $cat])) }}"
                   class="px-3.5 py-2 text-xs font-semibold rounded-lg border transition-all
                       {{ request('kategori') === $cat ? 'bg-navy text-white border-navy' : 'bg-white text-neutral-500 border-neutral-200 hover:border-navy hover:text-navy' }}">
                    {{ $cat }}
                </a>
                @endforeach
            </div>
            @endif

            <button type="submit"
                    class="px-5 py-2.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-600 transition-colors">
                Cari
            </button>

            @if(request()->hasAny(['q', 'kategori']))
            <a href="{{ route('documents.index') }}"
               class="px-4 py-2.5 text-xs font-semibold text-neutral-400 border border-neutral-200 rounded-lg hover:bg-neutral-100 transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>
</div>

{{-- ── KONTEN ── --}}
<section class="py-16 bg-neutral-50 min-h-[50vh]">
    <div class="container mx-auto px-6">

        @if($documents->isEmpty())

        <div class="flex flex-col items-center justify-center py-32 text-center">
            <div class="w-16 h-16 rounded-2xl bg-white border border-neutral-200 flex items-center justify-center mb-5 shadow-sm">
                <svg class="w-7 h-7 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-base font-semibold text-neutral-500 mb-1">Tidak ada dokumen ditemukan</p>
            <p class="text-sm text-neutral-400 mb-4">Coba ubah kata kunci atau filter kategori</p>
            @if(request()->hasAny(['q', 'kategori']))
            <a href="{{ route('documents.index') }}" class="text-sm text-primary font-semibold hover:underline">
                Lihat semua dokumen
            </a>
            @endif
        </div>

        @else

        {{-- ── LOOP PER KATEGORI ── --}}
        @foreach($documents as $kategori => $docs)

        <div class="mb-14 last:mb-0">

            {{-- Header kategori --}}
            <div class="flex items-center gap-4 mb-7">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-navy leading-none">{{ $kategori }}</h2>
                        <p class="text-2xs text-neutral-400 mt-0.5">
                            {{ $docs->count() }} dokumen
                        </p>
                    </div>
                </div>
                <div class="flex-1 h-px bg-neutral-200"></div>
            </div>

            {{-- Grid dokumen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($docs as $doc)

                {{-- Tiap card punya state expanded sendiri --}}
                <div x-data="{ expanded: false }"
                     class="group bg-white rounded-xl border border-neutral-200
                            hover:border-primary/30 hover:shadow-card-hover
                            transition-all duration-300 flex flex-col overflow-hidden">

                    {{-- Stripe warna atas berdasarkan tipe file --}}
                    <div class="h-1 w-full flex-shrink-0
                        @if($doc->file_type === 'PDF') bg-accent-red
                        @elseif(in_array($doc->file_type, ['DOC','DOCX'])) bg-primary
                        @elseif(in_array($doc->file_type, ['XLS','XLSX'])) bg-green-500
                        @elseif(in_array($doc->file_type, ['PPT','PPTX'])) bg-orange-400
                        @else bg-neutral-300
                        @endif">
                    </div>

                    {{-- Body --}}
                    <div class="px-6 pt-5 pb-4 flex-1 flex flex-col gap-4">

                        {{-- Baris atas: ikon tipe file + ukuran --}}
                        <div class="flex items-center justify-between">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0
                                @if($doc->file_type === 'PDF') bg-red-50
                                @elseif(in_array($doc->file_type, ['DOC','DOCX'])) bg-primary-50
                                @elseif(in_array($doc->file_type, ['XLS','XLSX'])) bg-green-50
                                @elseif(in_array($doc->file_type, ['PPT','PPTX'])) bg-orange-50
                                @else bg-neutral-100
                                @endif">
                                <span class="text-2xs font-black tracking-tight leading-none
                                    @if($doc->file_type === 'PDF') text-accent-red
                                    @elseif(in_array($doc->file_type, ['DOC','DOCX'])) text-primary
                                    @elseif(in_array($doc->file_type, ['XLS','XLSX'])) text-green-700
                                    @elseif(in_array($doc->file_type, ['PPT','PPTX'])) text-orange-600
                                    @else text-neutral-500
                                    @endif">
                                    {{ $doc->file_type }}
                                </span>
                            </div>
                            <span class="text-2xs text-neutral-300 tabular-nums">
                                {{ $doc->file_size_formatted }}
                            </span>
                        </div>

                        {{-- Judul & deskripsi collapsible --}}
                        <div class="flex-1">
                            <h3 class="font-bold text-navy text-sm leading-snug mb-2">
                                {{ $doc->title }}
                            </h3>

                            @if($doc->description)
                            <div>
                                {{-- Collapsed: 2 baris | Expanded: full teks --}}
                                <p class="text-xs text-neutral-400 leading-relaxed"
                                   :class="expanded ? '' : 'line-clamp-2'"
                                   style="word-break:break-word;">
                                    {{ $doc->description }}
                                </p>

                                {{-- Hanya tampilkan tombol jika deskripsi cukup panjang (>120 karakter) --}}
                                @if(strlen($doc->description) > 120)
                                <button @click="expanded = !expanded"
                                        class="mt-1 flex items-center gap-0.5 text-2xs font-bold text-primary
                                               hover:text-primary-700 transition-colors duration-150 focus:outline-none">
                                    <span x-text="expanded ? 'Sembunyikan ↑' : 'Selengkapnya ↓'"></span>
                                </button>
                                @endif
                            </div>
                            @endif
                        </div>

                        {{-- Meta: jumlah unduhan --}}
                        <div class="flex items-center gap-1.5 pt-1">
                            <svg class="w-3.5 h-3.5 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span class="text-xs text-neutral-400 tabular-nums">
                                {{ number_format($doc->downloads) }}x diunduh
                            </span>
                        </div>
                    </div>

                    {{-- Footer: tombol unduh --}}
                    <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-100">
                        <a href="{{ route('documents.download', $doc->id) }}"
                           class="flex items-center justify-center gap-2 w-full py-2.5
                                  bg-primary text-white text-xs font-bold rounded-lg
                                  hover:bg-primary-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Unduh Dokumen
                        </a>
                    </div>

                </div>
                @endforeach
            </div>
        </div>

        @endforeach

        @endif
    </div>
</section>

@endsection