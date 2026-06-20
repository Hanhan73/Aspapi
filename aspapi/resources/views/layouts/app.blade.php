<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ isset($title) ? $title . ' — ASPAPI' : 'ASPAPI — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia' }}</title>
    <meta name="description" content="{{ $description ?? 'ASPAPI adalah asosiasi profesi yang menghimpun sarjana dan praktisi administrasi perkantoran Indonesia. Competent, Competitive and Collaborative.' }}" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-aspapi.png') }}" />

    <!-- Fonts & Styles -->
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
