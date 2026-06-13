@extends('layouts.member')
@section('title', 'Seminar Saya')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-navy">Seminar Saya</h1>
            <p class="text-sm text-neutral-500 mt-1">Riwayat seminar yang sudah anda ikuti.</p>
        </div>
        @if ($isActive)
        <a href="{{ route('member.seminar.index') }}"
           class="text-xs font-bold px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition flex-shrink-0">
            + Tambah Seminar
        </a>
        @endif
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if ($grouped->isEmpty())
        <div class="text-center py-20 text-neutral-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm font-medium text-neutral-500">Anda belum mengambil seminar apapun.</p>
            @if ($isActive)
                <a href="{{ route('member.seminar.index') }}"
                   class="mt-3 inline-block text-xs font-bold text-primary hover:underline">
                    Lihat daftar seminar →
                </a>
            @endif
        </div>
    @else

        {{-- Kuota periode aktif --}}
        @if ($isActive && $currentPeriod)
        <div class="mb-6 bg-white border border-neutral-200 rounded-xl p-4 flex items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-1.5">
                    <p class="text-xs font-bold text-neutral-500 uppercase tracking-wide">
                        Kuota Periode Aktif
                        <span class="font-normal normal-case text-neutral-400 ml-1">
                            (sejak {{ \Carbon\Carbon::parse($currentPeriod)->translatedFormat('d M Y') }})
                        </span>
                    </p>
                    <p class="text-xs font-bold {{ $usedThisPeriod >= 3 ? 'text-red-500' : 'text-navy' }}">
                        {{ $usedThisPeriod }} / 3
                    </p>
                </div>
                <div class="h-2 bg-neutral-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500
                        {{ $usedThisPeriod >= 3 ? 'bg-red-400' : ($usedThisPeriod >= 2 ? 'bg-amber-400' : 'bg-primary') }}"
                         style="width: {{ ($usedThisPeriod / 3) * 100 }}%">
                    </div>
                </div>
                @if ($usedThisPeriod >= 3)
                    <p class="text-2xs text-red-500 mt-1">Kuota periode ini sudah habis.</p>
                @else
                    <p class="text-2xs text-neutral-400 mt-1">Sisa {{ 3 - $usedThisPeriod }} kuota di periode ini.</p>
                @endif
            </div>
            <div class="flex gap-1.5 flex-shrink-0">
                @for ($i = 1; $i <= 3; $i++)
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold
                    {{ $i <= $usedThisPeriod
                        ? 'border-primary bg-primary text-white'
                        : 'border-neutral-200 bg-neutral-50 text-neutral-300' }}">
                    {{ $i }}
                </div>
                @endfor
            </div>
        </div>
        @endif

        {{-- Seminar per periode --}}
        @foreach ($grouped->sortKeysDesc() as $period => $enrollments)
        @php
            $isCurrentPeriod = $period === $currentPeriod;
            $periodLabel     = \Carbon\Carbon::parse($period)->translatedFormat('d M Y');
        @endphp

        {{-- Periode aktif: langsung tampil --}}
        @if ($isCurrentPeriod)
            <div class="{{ ! $loop->first ? 'mt-8' : '' }}">
                {{-- Label --}}
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                    <p class="text-sm font-extrabold text-navy">Periode Aktif</p>
                    <span class="text-2xs font-bold px-2 py-0.5 rounded-full bg-green-50 text-green-700">
                        Sejak {{ $periodLabel }}
                    </span>
                    <div class="flex-1 h-px bg-neutral-200"></div>
                    <span class="text-2xs text-neutral-400">{{ $enrollments->count() }} seminar</span>
                </div>

                {{-- List --}}
                <div class="space-y-3">
                    @foreach ($enrollments as $enrollment)
                        @include('member.seminar._enrollment-card', ['enrollment' => $enrollment, 'isCurrentPeriod' => true])
                    @endforeach
                </div>
            </div>

        {{-- Periode sebelumnya: collapsible --}}
        @else
            <div class="mt-8" x-data="{ open: false }">
                {{-- Label — bisa diklik untuk expand/collapse --}}
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 mb-3 group text-left">
                    <span class="w-2 h-2 rounded-full bg-neutral-300 flex-shrink-0"></span>
                    <p class="text-sm font-extrabold text-neutral-500 group-hover:text-neutral-700 transition">
                        Periode Sebelumnya
                    </p>
                    <span class="text-2xs font-bold px-2 py-0.5 rounded-full bg-neutral-100 text-neutral-500">
                        Sejak {{ $periodLabel }}
                    </span>
                    <div class="flex-1 h-px bg-neutral-200"></div>
                    <span class="text-2xs text-neutral-400 flex-shrink-0">{{ $enrollments->count() }} seminar</span>
                    {{-- Chevron --}}
                    <svg class="w-4 h-4 text-neutral-400 flex-shrink-0 transition-transform duration-200"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Konten collapsible --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="space-y-3">
                    @foreach ($enrollments as $enrollment)
                        @include('member.seminar._enrollment-card', ['enrollment' => $enrollment, 'isCurrentPeriod' => false])
                    @endforeach
                </div>

                {{-- Hint saat collapsed --}}
                <div x-show="!open" class="text-2xs text-neutral-400 text-center py-1">
                    Klik untuk lihat {{ $enrollments->count() }} seminar dari periode ini
                </div>
            </div>
        @endif

        @endforeach
    @endif
</div>
@endsection