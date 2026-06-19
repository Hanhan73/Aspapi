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
        <aside class="w-64 flex-shrink-0 bg-navy flex flex-col overflow-hidden">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
                <img src="{{ asset('images/logo-aspapi.png') }}" alt="Logo ASPAPI"
                    class="h-9 w-auto object-contain flex-shrink-0"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" />
                <div class="hidden w-9 h-9 bg-primary rounded-full items-center justify-center flex-shrink-0">
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

            {{-- Nav — scrollable --}}
            <nav class="flex-1 py-4 px-3 overflow-y-auto">

                <p class="sidebar-section-title">Utama</p>
                <a href="{{ route('daerah.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('daerah.dashboard') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <p class="sidebar-section-title">Keanggotaan</p>
                <a href="{{ route('daerah.members') }}"
                    class="sidebar-link {{ request()->routeIs('daerah.members') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Data Anggota
                </a>
                <a href="{{ route('daerah.verify.index') }}"
                    class="sidebar-link {{ request()->routeIs('daerah.verify*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi Biodata
                    @php $pc = \App\Models\Member::where('registered_by_region_id',
                    auth()->user()->region?->id)->where('biodata_status','pending')->count(); @endphp
                    @if ($pc > 0)
                    <span
                        style="margin-left:auto;font-size:0.6rem;font-weight:700;background:#C0392B;color:#fff;border-radius:9999px;padding:0.1rem 0.4rem;">{{ $pc }}</span>
                    @endif
                </a>
                <a href="{{ route('daerah.batch.form') }}"
                    class="sidebar-link {{ request()->routeIs('daerah.batch*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Daftar Batch
                </a>

                <p class="sidebar-section-title">Pembayaran</p>
                <a href="{{ route('daerah.pay.form') }}"
                    class="sidebar-link {{ request()->routeIs('daerah.pay.form') || request()->routeIs('daerah.pay.store') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Bayar Iuran Kolektif
                </a>
                <a href="{{ route('daerah.pay.batches') }}"
                    class="sidebar-link {{ request()->routeIs('daerah.pay.batches') || request()->routeIs('daerah.pay.batch.show') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Riwayat Batch
                </a>

                <p class="sidebar-section-title">Konten</p>
                <a href="{{ route('daerah.agenda.index') }}"
                    class="sidebar-link {{ request()->routeIs('daerah.agenda*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />  
                    </svg>
                    Agenda
                </a>

            </nav>

            {{-- User info — SELALU di bawah --}}
            <div class="flex-shrink-0 border-t border-white/10 px-4 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'D', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs font-semibold truncate">
                            {{ auth()->user()->name ?? 'Pengurus Daerah' }}</p>
                        <p class="text-neutral-500 text-2xs truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>
                <a href="{{ route('account.settings') }}"
                    class="mt-3 flex items-center gap-1.5 text-2xs font-bold tracking-widest uppercase text-neutral-500 hover:text-white transition-colors duration-200 {{ request()->routeIs('account.*') ? 'text-white' : '' }}">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan Akun
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
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
            <header
                class="bg-white border-b border-neutral-200 px-6 py-4 flex items-center justify-between shadow-sm flex-shrink-0">
                <div>
                    <h1 class="text-base font-bold text-navy">{{ $title ?? 'Dashboard' }}</h1>
                    @if(isset($breadcrumbs))
                    <nav class="flex items-center gap-1.5 mt-0.5">
                        @foreach ($breadcrumbs as $crumb)
                        @if (!$loop->last)
                        <a href="{{ $crumb['url'] }}"
                            class="text-2xs text-neutral-400 hover:text-primary transition-colors">{{ $crumb['label'] }}</a>
                        <span class="text-neutral-300 text-2xs">›</span>
                        @else
                        <span class="text-2xs text-primary font-semibold">{{ $crumb['label'] }}</span>
                        @endif
                        @endforeach
                    </nav>
                    @endif
                </div>
                <a href="{{ url('/') }}" target="_blank" class="btn btn-ghost btn-sm">Lihat Website ↗</a>
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