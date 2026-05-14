@extends('layouts.app')
@section('title', 'ASPAPI Daerah')

@php use Illuminate\Support\Facades\Storage; @endphp

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
            <div class="card hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5 flex flex-col overflow-hidden">

                {{-- Foto Ketua --}}
                <div style="position:relative;width:100%;aspect-ratio:3/4;max-height:260px;background:#EEF4FB;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                    @if ($region->photo)
                        <img src="{{ Storage::url($region->photo) }}"
                             alt="Foto Ketua {{ $region->province }}"
                             style="width:100%;height:100%;object-fit:cover;object-position:center top;">
                    @else
                        <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg,#111E2A,#1A5F9A);">
                            <div style="position:absolute;inset:0;opacity:0.08;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;"></div>
                            <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.1);border:2px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;position:relative;z-index:1;">
                                <span style="color:rgba(255,255,255,0.6);font-size:1.5rem;font-weight:900;">
                                    {{ strtoupper(substr($region->province, 0, 2)) }}
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Badge status --}}
                    <div style="position:absolute;top:0.75rem;right:0.75rem;z-index:10;">
                        @if ($region->period_is_active)
                        <span class="badge badge-success text-2xs">Aktif</span>
                        @else
                        <span class="badge badge-neutral text-2xs">{{ $region->period_end }}</span>
                        @endif
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-5 flex flex-col flex-1">

                    {{-- Nama Provinsi --}}
                    <div class="mb-3">
                        <p class="text-2xs font-bold uppercase tracking-widest text-primary-400">ASPAPI</p>
                        <h3 class="text-base font-bold text-navy leading-tight mt-0.5">{{ $region->province }}</h3>
                    </div>

                    {{-- Ketua --}}
                    <div class="flex-1">
                        <p class="text-2xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Ketua</p>
                        <p class="font-semibold text-navy text-sm leading-snug">
                            {{ $region->chairman_name ?? '—' }}
                        </p>
                        @if ($region->chairman_title)
                        <p class="text-xs text-neutral-500 mt-0.5 leading-relaxed">
                            {{ $region->chairman_title }}
                        </p>
                        @endif
                    </div>

                    {{-- Footer card --}}
                    <div class="mt-4 pt-4 border-t border-neutral-100 flex items-center justify-between">
                        <div>
                            <p class="text-2xs text-neutral-400 font-bold uppercase tracking-widest">Periode</p>
                            <p class="text-sm font-semibold text-primary-600 mt-0.5">{{ $region->period }}</p>
                        </div>
                        @if ($region->website_url)
                        <a href="{{ $region->website_url }}" target="_blank" rel="noopener noreferrer"
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