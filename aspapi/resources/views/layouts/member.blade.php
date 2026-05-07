<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{{ $title ?? 'Portal Anggota' }} — ASPAPI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body style="background:#F8FAFC;font-family:'DM Sans',sans-serif;">

<div style="display:flex;min-height:100vh;">

    {{-- Sidebar --}}
    <aside style="width:240px;background:#1A2A3A;flex-shrink:0;display:flex;flex-direction:column;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.08);">
            <img src="{{ asset('images/logo-aspapi.png') }}" style="height:36px;filter:brightness(0) invert(1);opacity:0.9;" onerror="this.style.display='none'"/>
            <p style="color:rgba(255,255,255,0.5);font-size:0.65rem;letter-spacing:0.1em;text-transform:uppercase;margin-top:0.5rem;">Portal Anggota</p>
        </div>

        <nav style="flex:1;padding:1rem 0.75rem;">
            @php
                $member = auth()->user()->member;
                $links = [
                    ['route' => 'member.dashboard', 'label' => 'Dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'member.biodata',   'label' => 'Biodata Saya', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['route' => 'member.payment',   'label' => 'Pembayaran',   'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['route' => 'member.card',      'label' => 'Kartu Anggota','icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2'],
                ];
            @endphp

            @foreach ($links as $link)
            <a href="{{ route($link['route']) }}"
               style="display:flex;align-items:center;gap:0.75rem;padding:0.625rem 0.75rem;border-radius:4px;margin-bottom:0.25rem;text-decoration:none;font-size:0.8rem;font-weight:600;transition:all 0.2s;
               {{ request()->routeIs($link['route']) ? 'background:#2A7FC1;color:#fff;' : 'color:rgba(255,255,255,0.6);' }}"
               onmouseover="{{ !request()->routeIs($link['route']) ? 'this.style.background=\'rgba(255,255,255,0.08)\';this.style.color=\'#fff\'' : '' }}"
               onmouseout="{{ !request()->routeIs($link['route']) ? 'this.style.background=\'transparent\';this.style.color=\'rgba(255,255,255,0.6)\'' : '' }}">
                <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                </svg>
                {{ $link['label'] }}
            </a>
            @endforeach
        </nav>

        {{-- User info --}}
        <div style="padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,0.08);">
            <p style="color:#fff;font-size:0.8rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ auth()->user()->name }}
            </p>
            <p style="color:rgba(255,255,255,0.4);font-size:0.7rem;margin-top:0.125rem;">
                {{ auth()->user()->member?->member_type_label ?? 'Anggota' }}
            </p>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:0.75rem;">
                @csrf
                <button type="submit"
                        style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:rgba(255,255,255,0.4);background:none;border:none;cursor:pointer;padding:0;"
                        onmouseover="this.style.color='#C0392B'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div style="flex:1;display:flex;flex-direction:column;overflow:hidden;">
        <header style="background:#fff;border-bottom:1px solid #D6E8F7;padding:1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;">
            <h1 style="font-size:1rem;font-weight:700;color:#1A2A3A;">{{ $title ?? 'Dashboard' }}</h1>
            <a href="{{ route('home') }}" target="_blank"
               style="font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#4A6580;text-decoration:none;padding:0.375rem 0.75rem;border:1.5px solid #D6E8F7;border-radius:3px;">
               Lihat Website ↗
            </a>
        </header>

        <main style="flex:1;overflow-y:auto;padding:1.5rem;">
            @if (session('success'))
            <div style="background:#F0FFF4;border-left:4px solid #276749;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;font-size:0.875rem;color:#276749;">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;font-size:0.875rem;color:#922B21;">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>