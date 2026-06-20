<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{{ $title ?? 'Portal Anggota' }} — ASPAPI</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-aspapi.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
/* ── Rich editor output styling ───────────────────────────────────────────── */
/* Berlaku untuk semua container yang menampilkan output dari rich editor     */
 
.rich-output p,
.desc-content p,
.seminar-desc p,
.seminar-modal-desc p,
#modal-desc p {
    margin-bottom: 0.7em;
}
 
.rich-output p:last-child,
.desc-content p:last-child,
.seminar-desc p:last-child,
.seminar-modal-desc p:last-child,
#modal-desc p:last-child {
    margin-bottom: 0;
}
 
.rich-output strong,
.desc-content strong,
.seminar-desc strong,
.seminar-modal-desc strong,
#modal-desc strong {
    font-weight: 700;
    color: inherit;
}
 
.rich-output em,
.desc-content em,
.seminar-desc em,
.seminar-modal-desc em,
#modal-desc em {
    font-style: italic;
}
 
.rich-output u,
.desc-content u,
.seminar-desc u,
.seminar-modal-desc u,
#modal-desc u {
    text-decoration: underline;
}
 
.rich-output ul,
.desc-content ul,
.seminar-desc ul,
.seminar-modal-desc ul,
#modal-desc ul {
    list-style: disc;
    padding-left: 1.25rem;
    margin-bottom: 0.7em;
}
 
.rich-output ol,
.desc-content ol,
.seminar-desc ol,
.seminar-modal-desc ol,
#modal-desc ol {
    list-style: decimal;
    padding-left: 1.25rem;
    margin-bottom: 0.7em;
}
 
.rich-output li,
.desc-content li,
.seminar-desc li,
.seminar-modal-desc li,
#modal-desc li {
    margin-bottom: 0.25em;
}
 
.rich-output a,
.desc-content a,
.seminar-desc a,
.seminar-modal-desc a,
#modal-desc a {
    color: #2A7FC1;
    text-decoration: underline;
    text-underline-offset: 2px;
}
 
/* Khusus: jika editor menghasilkan <br> antar paragraf (browser lama) */
.rich-output br,
.desc-content br,
.seminar-desc br,
.seminar-modal-desc br,
#modal-desc br {
    display: block;
    content: '';
    margin-top: 0.5em;
}
</style>
</head>
<body class="bg-neutral-100 font-sans antialiased">
@include('components.impersonate-banner')

<div class="flex h-screen overflow-hidden">

    {{-- ── SIDEBAR ── --}}
    <aside class="w-64 flex-shrink-0 bg-navy flex flex-col overflow-hidden">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
            <img src="{{ asset('images/logo-aspapi.png') }}" alt="Logo ASPAPI"
                class="h-9 w-auto object-contain flex-shrink-0"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'"/>
            <div class="hidden w-9 h-9 bg-primary rounded-full items-center justify-center flex-shrink-0">
                <span class="text-white font-black text-2xs tracking-tight">ASP</span>
            </div>
            <div>
                <p class="text-white font-extrabold text-sm leading-none tracking-wide">ASPAPI</p>
                <p class="text-neutral-400 text-2xs tracking-wider uppercase mt-0.5">Portal Anggota</p>
            </div>
        </div>

        {{-- Nav — scrollable jika banyak item --}}
        <nav class="flex-1 py-4 px-3 overflow-y-auto">
            @php
                $links = [
                    ['route' => 'member.dashboard', 'label' => 'Dashboard',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'member.biodata',   'label' => 'Biodata Saya',  'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['route' => 'member.payment',   'label' => 'Pembayaran',    'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['route' => 'member.card',      'label' => 'Kartu Anggota', 'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2'],
                ];
            @endphp

            <p class="sidebar-section-title">Menu</p>

            @foreach ($links as $link)
            <a href="{{ route($link['route']) }}"
               class="sidebar-link {{ request()->routeIs($link['route']) ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                </svg>
                {{ $link['label'] }}
            </a>
            @endforeach

            @php
                $memberStatus = auth()->user()->member?->status;
            @endphp
            
            @if ($memberStatus === 'active')
            <p class="sidebar-section-title mt-4">Pengembangan</p>
            
            {{-- Seminar — dengan submenu --}}
            @php
                $isSeminarActive = request()->routeIs('member.seminar.*');
            @endphp
            <div x-data="{ open: {{ $isSeminarActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="sidebar-link w-full text-left {{ $isSeminarActive ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="flex-1">Seminar</span>
                    <svg class="w-3 h-3 transition-transform duration-200 flex-shrink-0"
                        :class="open ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            
                <div x-show="open" x-transition class="ml-4 mt-1 space-y-0.5">
                    <a href="{{ route('member.seminar.index') }}"
                    class="sidebar-link text-xs {{ request()->routeIs('member.seminar.index') ? 'sidebar-link-active' : '' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Daftar Seminar
                    </a>
                    <a href="{{ route('member.seminar.my-seminars') }}"
                    class="sidebar-link text-xs {{ request()->routeIs('member.seminar.my-seminars') ? 'sidebar-link-active' : '' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Seminar Saya
                    </a>
                </div>
            </div>
            @endif
        </nav>

        {{-- User info — SELALU di bawah, tidak terdorong --}}
        <div class="flex-shrink-0 border-t border-white/10 px-4 py-4">
            <div class="flex items-center gap-3">
                @php $sidebarPhoto = auth()->user()->member?->photo; @endphp
                @if ($sidebarPhoto)
                    <img src="{{ Storage::url($sidebarPhoto) }}"
                        class="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-white/20"/>
                @else
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-content-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-neutral-500 text-2xs truncate">
                        {{ auth()->user()->member?->member_type_label ?? 'Anggota' }}
                    </p>
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
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-neutral-200 px-6 py-4 flex items-center justify-between shadow-sm flex-shrink-0">
            <h1 class="text-base font-bold text-navy">{{ $title ?? 'Dashboard' }}</h1>
            <a href="{{ route('home') }}" target="_blank"
               class="btn btn-ghost btn-sm">
                Lihat Website ↗
            </a>
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