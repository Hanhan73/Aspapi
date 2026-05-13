@extends('layouts.app')
@section('title', 'Mitra ASPAPI')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')

{{-- Hero --}}
<section class="bg-navy py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <span class="section-label text-primary-300">Kolaborasi & Kemitraan</span>
        <h1 class="section-title text-white mt-3">Mitra ASPAPI</h1>
        <p class="mt-4 text-primary-200 max-w-2xl mx-auto text-sm leading-relaxed">
            ASPAPI menjalin kerjasama strategis dengan berbagai institusi dari kalangan
            perguruan tinggi, sekolah, industri, dan pemerintahan untuk mendukung
            pengembangan profesi apoteker Indonesia.
        </p>

        {{-- Stats --}}
        <div class="mt-10 inline-flex flex-wrap justify-center gap-8 bg-white/10 px-10 py-5 rounded-sm">
            @foreach (\App\Models\Partner::categories() as $key => $label)
                @php $count = \App\Models\Partner::active()->where('category', $key)->count(); @endphp
                @if ($count > 0)
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">{{ $count }}</div>
                    <div class="text-2xs uppercase tracking-widest text-primary-300 mt-1">{{ $label }}</div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

{{-- Filter Tab --}}
<section class="bg-white border-b border-neutral-200 sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center gap-1 overflow-x-auto py-0 scrollbar-none">
            <a href="{{ route('partners.index') }}"
               class="flex-shrink-0 px-5 py-4 text-xs font-bold uppercase tracking-wider border-b-2 transition-colors
                      {{ !$category ? 'border-primary text-primary' : 'border-transparent text-neutral-500 hover:text-navy' }}">
                Semua
            </a>
            @foreach ($categories as $value => $label)
                <a href="{{ route('partners.index', ['category' => $value]) }}"
                   class="flex-shrink-0 px-5 py-4 text-xs font-bold uppercase tracking-wider border-b-2 transition-colors
                          {{ $category === $value ? 'border-primary text-primary' : 'border-transparent text-neutral-500 hover:text-navy' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Content --}}
<section class="py-16 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">

        @if ($partners->isEmpty())
            <div class="text-center py-20 text-neutral-400">
                <p class="text-sm">Belum ada data mitra untuk kategori ini.</p>
            </div>
        @else

        @foreach ($partners as $categoryKey => $items)
            @php
                $categoryLabels = \App\Models\Partner::categories();
                $label = $categoryLabels[$categoryKey] ?? ucfirst($categoryKey);
            @endphp

            {{-- Section per kategori (hanya tampil jika filter "Semua") --}}
            @if (!$category)
            <div class="mb-14">
                <div class="flex items-center gap-4 mb-6">
                    <h2 class="text-base font-bold text-navy uppercase tracking-wider">{{ $label }}</h2>
                    <div class="flex-1 border-t border-neutral-200"></div>
                    <span class="text-2xs text-neutral-400 font-semibold">{{ $items->count() }} mitra</span>
                </div>
            @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($items as $partner)
                    <div class="card hover:shadow-lg transition-shadow duration-300 flex flex-col">

                        {{-- Logo area --}}
                        <div class="h-36 bg-white border-b border-neutral-100 flex items-center justify-center px-8 py-4">
                            @if ($partner->logo)
                                <img src="{{ Storage::url($partner->logo) }}"
                                     alt="Logo {{ $partner->name }}"
                                     class="max-h-full max-w-full object-contain">
                            @else
                                <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-primary font-black text-lg">
                                        {{ strtoupper(substr($partner->name, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-5 flex flex-col flex-1">

                            {{-- Badge kategori --}}
                            <span class="badge {{ $partner->category_color }} text-2xs self-start mb-2">
                                {{ $partner->category_label }}
                            </span>

                            <h3 class="font-bold text-navy text-sm leading-snug">{{ $partner->name }}</h3>

                            @if ($partner->profile)
                                <p class="text-xs text-neutral-500 mt-2 leading-relaxed flex-1">
                                    {{ Str::limit($partner->profile, 120) }}
                                </p>
                            @else
                                <div class="flex-1"></div>
                            @endif

                            @if ($partner->website_url)
                                <div class="mt-4 pt-4 border-t border-neutral-100">
                                    <a href="{{ $partner->website_url }}"
                                       target="_blank" rel="noopener noreferrer"
                                       class="btn btn-outline text-xs py-1.5 w-full text-center">
                                        Kunjungi Website ↗
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>
                    @endforeach
                </div>

            @if (!$category)
            </div>
            @endif

        @endforeach

        @endif
    </div>
</section>

@endsection