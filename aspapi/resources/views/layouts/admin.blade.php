<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ isset($title) ? $title . ' — Admin ASPAPI' : 'Admin Panel — ASPAPI' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-aspapi.png') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-neutral-100 font-sans antialiased">

    {{-- Alpine.js state: sidebarOpen untuk drawer di < lg --}}
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

        {{-- ── OVERLAY (mobile/tablet, muncul saat sidebar terbuka) ── --}}
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/50 lg:hidden"
            style="display: none;">
        </div>

        {{-- ── SIDEBAR ── --}}
        {{--
            Desktop (lg+)  : selalu tampil, posisi static dalam flex
            Mobile/tablet  : posisi fixed, slide dari kiri via translate
        --}}
        <aside
            class="fixed inset-y-0 left-0 z-30 w-64 flex-shrink-0 bg-navy flex flex-col overflow-y-auto
                   transform transition-transform duration-200 ease-in-out
                   lg:relative lg:translate-x-0 lg:z-auto"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10 flex-shrink-0">
                <img src="{{ asset('images/logo-aspapi.png') }}" alt="Logo ASPAPI"
                    class="h-9 w-auto object-contain flex-shrink-0"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'"/>
                <div class="hidden w-9 h-9 bg-primary rounded-full items-center justify-center flex-shrink-0">
                    <span class="text-white font-black text-2xs tracking-tight">ASP</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-extrabold text-sm leading-none tracking-wide">ASPAPI</p>
                    <p class="text-neutral-400 text-2xs tracking-wider uppercase mt-0.5">Admin Panel</p>
                </div>
                {{-- Tombol tutup sidebar (mobile only) --}}
                <button
                    @click="sidebarOpen = false"
                    class="lg:hidden ml-auto text-neutral-400 hover:text-white transition-colors p-1 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 py-4 px-3 overflow-y-auto">

                <p class="sidebar-section-title">Dashboard</p>
                <a href="{{ route('admin.dashboard') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <p class="sidebar-section-title">Profil Organisasi</p>
                <a href="{{ route('admin.boards.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.boards.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                    </svg>
                    Pengurus
                </a>
                <a href="{{ route('admin.advisors.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.advisors.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Dewan Penasihat
                </a>
                <a href="{{ route('admin.experts.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.experts.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    Dewan Pakar
                </a>

                <p class="sidebar-section-title">Konten</p>
                <a href="{{ route('admin.news.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.news.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0014.914 3H12" />
                    </svg>
                    Berita
                </a>
                <a href="{{ route('admin.blogs.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.blogs.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Blog
                </a>
                <a href="{{ route('admin.agenda.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.agenda.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Agenda
                </a>
                <a href="{{ route('admin.documents.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.documents.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Download Dokumen
                </a>

                <p class="sidebar-section-title">Keanggotaan</p>
                <a href="{{ route('admin.members.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.members.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Data Anggota
                </a>
                <a href="{{ route('admin.regions.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.regions.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    ASPAPI Daerah
                </a>

                <p class="sidebar-section-title">Program</p>
                <a href="{{ route('admin.seminar.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link text-xs {{ request()->routeIs('admin.seminar.index') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Seminar
                </a>

                <p class="sidebar-section-title">Kemitraan</p>
                <a href="{{ route('admin.partners.index') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.partners.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Mitra
                </a>

                @if(auth()->user()->role === 'superadmin')
                <p class="sidebar-section-title" style="margin-top:0.75rem;">Super Admin</p>
                <a href="{{ route('admin.superadmin.users') }}"
                    @click="sidebarOpen = false"
                    class="sidebar-link {{ request()->routeIs('admin.superadmin.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Kelola Akun
                </a>
                @endif

            </nav>

            {{-- User info --}}
            <div class="border-t border-white/10 px-4 py-4 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-neutral-500 text-2xs truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>

                <a href="{{ route('account.settings') }}"
                   class="mt-3 flex items-center gap-1.5 text-2xs font-bold tracking-widest uppercase text-neutral-500 hover:text-white transition-colors duration-200 {{ request()->routeIs('account.*') ? 'text-white' : '' }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Pengaturan Akun
                </a>

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
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            {{-- Top Bar --}}
            <header class="bg-white border-b border-neutral-200 px-4 lg:px-6 py-3 lg:py-4 flex items-center gap-3 shadow-sm flex-shrink-0">

                {{-- Hamburger (hanya muncul di < lg) --}}
                <button
                    @click="sidebarOpen = true"
                    class="lg:hidden flex-shrink-0 p-1.5 rounded-md text-navy hover:bg-neutral-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="flex-1 min-w-0">
                    <h1 class="text-sm lg:text-base font-bold text-navy truncate">
                        {{ $title ?? 'Dashboard' }}
                    </h1>
                    {{-- Breadcrumb --}}
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

                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-ghost btn-sm hidden sm:inline-flex">
                        Lihat Website ↗
                    </a>
                    {{-- Versi icon saja untuk mobile --}}
                    <a href="{{ url('/') }}" target="_blank"
                        class="sm:hidden p-1.5 rounded-md text-neutral-500 hover:bg-neutral-100 transition-colors"
                        title="Lihat Website">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">

                {{-- Flash Messages --}}
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