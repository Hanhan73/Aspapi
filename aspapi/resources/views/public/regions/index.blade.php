@extends('layouts.app')
@section('title', 'ASPAPI Daerah')

@section('content')

{{-- Hero --}}
<section class="bg-navy py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <span class="section-label text-primary-300">Jaringan Nasional</span>
        <h1 class="section-title text-white mt-3">ASPAPI Daerah</h1>
        <p class="mt-4 text-primary-200 max-w-2xl mx-auto text-sm leading-relaxed">
            ASPAPI Daerah adalah organisasi ASPAPI di tingkat provinsi yang dipimpin oleh
            Ketua terpilih melalui Musyawarah Daerah (Musda) setiap 4 tahun sekali.
        </p>
        <div class="mt-10 inline-flex items-center gap-10 bg-white/10 px-10 py-5 rounded-sm">
            <div class="text-center">
                <div class="text-3xl font-bold text-white">{{ $regions->count() }}</div>
                <div class="text-2xs uppercase tracking-widest text-primary-300 mt-1">Daerah Aktif</div>
            </div>
        </div>
    </div>
</section>

{{-- Grid Daerah --}}
<section class="py-16 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($regions as $region)
            <div class="card hover:shadow-lg transition-shadow duration-300 flex flex-col overflow-hidden">

                {{-- Cover Image --}}
                <div class="relative h-36 bg-navy overflow-hidden">
                    @if ($region->cover_image)
                        <img src="{{ Storage::url($region->cover_image) }}"
                             alt="Cover {{ $region->province }}"
                             class="w-full h-full object-cover opacity-80">
                    @else
                        {{-- Placeholder pattern --}}
                        <div class="absolute inset-0 opacity-10"
                             style="background-image: repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 0, transparent 50%); background-size: 12px 12px;"></div>
                    @endif
                    {{-- Gradient overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-navy/80 to-transparent"></div>

                    {{-- Badge status di pojok kanan atas --}}
                    <div class="absolute top-3 right-3">
                        @if ($region->period_is_active)
                            <span class="badge badge-success text-2xs">Aktif</span>
                        @else
                            <span class="badge badge-neutral text-2xs">{{ $region->period_end }}</span>
                        @endif
                    </div>

                    {{-- Logo/foto daerah mengambang di bawah cover --}}
                    <div class="absolute -bottom-7 left-5">
                        <div class="w-14 h-14 rounded-full border-2 border-white shadow-md overflow-hidden bg-white">
                            @if ($region->photo)
                                <img src="{{ Storage::url($region->photo) }}"
                                     alt="Logo {{ $region->province }}"
                                     class="w-full h-full object-cover">
                            @else
                                {{-- Inisial sebagai fallback --}}
                                <div class="w-full h-full bg-primary-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr($region->province, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Body — ada top-padding ekstra karena avatar mengambang --}}
                <div class="pt-10 px-5 pb-5 flex flex-col flex-1">

                    {{-- Nama provinsi --}}
                    <div>
                        <p class="text-2xs font-bold uppercase tracking-widest text-primary-400">ASPAPI</p>
                        <h3 class="text-base font-bold text-navy mt-0.5 leading-tight">
                            {{ $region->province }}
                        </h3>
                    </div>

                    {{-- Ketua --}}
                    <div class="mt-3 flex-1">
                        <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Ketua</p>
                        <p class="font-semibold text-navy text-sm leading-snug">
                            {{ $region->chairman_name ?? '—' }}
                        </p>
                        @if ($region->chairman_title)
                            <p class="text-xs text-neutral-500 mt-0.5">{{ $region->chairman_title }}</p>
                        @endif
                    </div>

                    {{-- Footer card --}}
                    <div class="mt-4 pt-4 border-t border-neutral-100 flex items-center justify-between">
                        <div>
                            <p class="text-2xs text-neutral-400 font-bold uppercase tracking-widest">Periode</p>
                            <p class="text-sm font-semibold text-primary-600 mt-0.5">{{ $region->period }}</p>
                        </div>
                        @if ($region->website_url)
                            <a href="{{ $region->website_url }}"
                               target="_blank" rel="noopener noreferrer"
                               class="btn btn-outline text-xs py-1.5 px-3">
                                Website
                            </a>
                        @endif
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection