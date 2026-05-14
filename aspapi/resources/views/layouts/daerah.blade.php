<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ isset($title) ? $title . ' — Daerah ASPAPI' : 'Daerah — ASPAPI' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-aspapi.png') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-neutral-100 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ── SIDEBAR ── --}}
    <aside class="w-64 flex-shrink-0 bg-navy flex flex-col overflow-y-auto">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
            <div class="w-9 h-9 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-white font-black text-2xs tracking-tight">ASP</span>
            </div>
            <div>
                <p class="text-white font-extrabold text-sm leading-none tracking-wide">ASPAPI</p>
                @php $region = auth()->user()->region ?? null; @endphp
                <p class="text-neutral-400 text-2xs tracking-wider uppercase mt-0.5">
                    {{ $region?->province ?? 'Daerah' }}
                </p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 py-4 px-3">

            <p class="sidebar-section-title">Utama</p>
            <a href="{{ route('daerah.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('daerah.dashboard') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <p class="sidebar-section-title">Keanggotaan</p>
            <a href="{{ route('daerah.members') }}"
               class="sidebar-link {{ request()->routeIs('daerah.members') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Data Anggota
            </a>
            <a href="{{ route('daerah.batch.form') }}"
               class="sidebar-link {{ request()->routeIs('daerah.batch*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Daftar Batch
            </a>

            <p class="sidebar-section-title">Pembayaran</p>
            <a href="{{ route('daerah.pay.form') }}"
               class="sidebar-link {{ request()->routeIs('daerah.pay*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Bayar Iuran Kolektif
            </a>

        </nav>

        {{-- User info --}}
        <div class="border-t border-white/10 px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'D', 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name ?? 'Pengurus Daerah' }}</p>
                    <p class="text-neutral-500 text-2xs truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit"
                        class="w-full text-left text-2xs font-bold tracking-widest uppercase text-neutral-500 hover:text-accent-red transition-colors duration-200">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ── MAIN AREA ── --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-neutral-200 px-6 py-4 flex items-center justify-between shadow-sm flex-shrink-0">
            <div>
                <h1 class="text-base font-bold text-navy">
                    {{ $title ?? 'Dashboard' }}
                </h1>
                @if(isset($breadcrumbs))
                <nav class="flex items-center gap-1.5 mt-0.5">
                    @foreach ($breadcrumbs as $crumb)
                        @if (!$loop->last)
                            <a href="{{ $crumb['url'] }}"
                               class="text-2xs text-neutral-400 hover:text-primary transition-colors">
                                {{ $crumb['label'] }}
                            </a>
                            <span class="text-neutral-300 text-2xs">›</span>
                        @else
                            <span class="text-2xs text-primary font-semibold">{{ $crumb['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" target="_blank"
                   class="btn btn-ghost btn-sm">
                    Lihat Website ↗
                </a>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">

            @if (session('success'))
                <div class="alert alert-success mb-5">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mb-5">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

</div>

@stack('scripts')
</body>
</html>