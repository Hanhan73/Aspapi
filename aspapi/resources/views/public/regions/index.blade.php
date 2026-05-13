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

                {{-- Foto Ketua sebagai hero card --}}
                <div class="relative overflow-hidden bg-neutral-100">
                    @if ($region->photo)
                        {{-- object-contain: foto tidak dicrop sama sekali, wajah selalu utuh --}}
                        <img src="{{ Storage::url($region->photo) }}"
                             alt="Foto Ketua {{ $region->province }}"
                             class="w-full h-56 object-contain object-center bg-neutral-100">
                        {{-- Gradient tipis di bawah untuk overlay teks --}}
                        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-navy/80 to-transparent"></div>
                    @else
                        {{-- Placeholder jika belum ada foto --}}
                        <div class="h-56 bg-navy flex flex-col items-center justify-center">
                            <div class="opacity-10 absolute inset-0"
                                 style="background-image: repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 0, transparent 50%); background-size: 12px 12px;"></div>
                            <div class="w-20 h-20 rounded-full bg-white/10 border-2 border-white/20 flex items-center justify-center relative z-10">
                                <span class="text-white/60 font-black text-2xl">
                                    {{ strtoupper(substr($region->province, 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-navy/80 to-transparent"></div>
                    @endif

                    {{-- Badge status pojok kanan atas --}}
                    <div class="absolute top-3 right-3 z-10">
                        @if ($region->period_is_active)
                            <span class="badge badge-success text-2xs">Aktif</span>
                        @else
                            <span class="badge badge-neutral text-2xs">{{ $region->period_end }}</span>
                        @endif
                    </div>

                    {{-- Nama provinsi overlay di bawah foto --}}
                    <div class="absolute bottom-0 left-0 right-0 px-5 pb-4 z-10">
                        <p class="text-2xs font-bold uppercase tracking-widest text-primary-300">ASPAPI</p>
                        <h3 class="text-lg font-bold text-white leading-tight mt-0.5">
                            {{ $region->province }}
                        </h3>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-5 flex flex-col flex-1">

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