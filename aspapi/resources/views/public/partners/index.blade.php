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

        {{-- Stats per kategori --}}
        <div class="mt-10 inline-flex flex-wrap justify-center gap-8 bg-white/10 px-10 py-5 rounded-sm">
            @foreach ($categories as $key => $label)
                @php $count = $partners->get($key)?->count() ?? 0 @endphp
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

{{-- Tab Section --}}
@php
    {{-- Hanya tampilkan kategori yang punya data --}}
    $activeCategories = $categories->filter(fn($label, $key) => $partners->has($key));
    $firstTab = $activeCategories->keys()->first() ?? '';
@endphp

<section class="py-12 bg-neutral-50"
    x-data="{ activeTab: '{{ $firstTab }}' }">

    <div class="max-w-7xl mx-auto px-6">

        {{-- Tab bar --}}
        <div class="flex gap-2 overflow-x-auto pb-1 mb-10 border-b border-neutral-200 scrollbar-none">
            @foreach ($activeCategories as $key => $label)
            <button
                @click="activeTab = '{{ $key }}'"
                :class="activeTab === '{{ $key }}'
                    ? 'border-b-2 border-primary text-primary bg-white shadow-sm'
                    : 'border-b-2 border-transparent text-neutral-500 hover:text-navy hover:bg-white/60'"
                class="flex-shrink-0 flex items-center gap-2 px-5 py-3 -mb-px text-xs font-bold uppercase tracking-wider rounded-t transition-all duration-200">

                {{-- Icon per kategori --}}
                @if ($key === 'perguruan_tinggi')
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
                @elseif ($key === 'sekolah')
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                @elseif ($key === 'industri')
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                @elseif ($key === 'pemerintahan')
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
                @endif

                {{ $label }}

                {{-- Count badge --}}
                <span class="ml-1 px-1.5 py-0.5 rounded text-2xs font-bold"
                    :class="activeTab === '{{ $key }}' ? 'bg-primary text-white' : 'bg-neutral-200 text-neutral-500'">
                    {{ $partners->get($key)?->count() ?? 0 }}
                </span>
            </button>
            @endforeach
        </div>

        {{-- Tab panels --}}
        @foreach ($activeCategories as $key => $label)
        @php $items = $partners->get($key, collect()) @endphp

        <div x-show="activeTab === '{{ $key }}'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0"
             style="display: none;">

            @if ($items->isEmpty())
                <div class="text-center py-20 text-neutral-400">
                    <p class="text-sm">Belum ada mitra untuk kategori ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($items as $partner)
                    <div class="card hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5 flex flex-col">

                        {{-- Logo area --}}
                        <div class="h-32 bg-white border-b border-neutral-100 flex items-center justify-center px-6 py-4">
                            @if ($partner->logo)
                                <img src="{{ Storage::url($partner->logo) }}"
                                     alt="Logo {{ $partner->name }}"
                                     class="max-h-full max-w-full object-contain">
                            @else
                                <div class="w-14 h-14 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-primary font-black text-base">
                                        {{ strtoupper(substr($partner->name, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-bold text-navy text-sm leading-snug">{{ $partner->name }}</h3>

                            @if ($partner->profile)
                                <p class="text-xs text-neutral-500 mt-2 leading-relaxed flex-1">
                                    {{ Str::limit($partner->profile, 100) }}
                                </p>
                            @else
                                <div class="flex-1"></div>
                            @endif

                            @if ($partner->website_url)
                                <div class="mt-4 pt-3 border-t border-neutral-100">
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
            @endif

        </div>
        @endforeach

    </div>
</section>

@endsection