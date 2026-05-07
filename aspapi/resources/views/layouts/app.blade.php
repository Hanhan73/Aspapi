<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ isset($title) ? $title . ' — ASPAPI' : 'ASPAPI — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia' }}</title>
    <meta name="description" content="{{ $description ?? 'ASPAPI adalah asosiasi profesi yang menghimpun sarjana dan praktisi administrasi perkantoran Indonesia. Competent, Competitive and Collaborative.' }}" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />

    <!-- Fonts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-neutral-50 font-sans antialiased">

    {{-- ── NAVBAR ── --}}
    @include('components.navbar')

    {{-- ── PAGE CONTENT ── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── FOOTER ── --}}
    @include('components.footer')

    {{-- ── FLASH MESSAGES ── --}}
    @if (session('success'))
        <div id="flash-success"
             class="fixed bottom-6 right-6 z-50 alert alert-success shadow-lg max-w-sm"
             x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             x-transition>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id="flash-error"
             class="fixed bottom-6 right-6 z-50 alert alert-danger shadow-lg max-w-sm"
             x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             x-transition>
            {{ session('error') }}
        </div>
    @endif

    @stack('scripts')
</body>
</html>
