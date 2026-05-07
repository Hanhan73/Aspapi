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
            <div class="card hover:shadow-lg transition-shadow duration-300 flex flex-col">

                {{-- Header --}}
                <div class="bg-navy px-5 py-4 flex items-start justify-between">
                    <div>
                        <p class="text-2xs font-bold uppercase tracking-widest text-primary-300">ASPAPI</p>
                        <h3 class="text-base font-bold text-white mt-0.5 leading-tight">
                            {{ $region->province }}
                        </h3>
                    </div>
                    @if ($region->period_is_active)
                        <span class="badge badge-success text-2xs shrink-0 mt-1">Aktif</span>
                    @else
                        <span class="badge badge-neutral text-2xs shrink-0 mt-1">{{ $region->period_end }}</span>
                    @endif
                </div>

                {{-- Body --}}
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex-1">
                        <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Ketua</p>
                        <p class="font-semibold text-navy text-sm leading-snug">
                            {{ $region->chairman_name ?? '—' }}
                        </p>
                        @if ($region->chairman_title)
                            <p class="text-xs text-neutral-500 mt-0.5">{{ $region->chairman_title }}</p>
                        @endif
                    </div>

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