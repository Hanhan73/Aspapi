{{--
    Navbar Component — ASPAPI
    Usage: @include('components.navbar')
--}}

<header class="bg-white border-b-2 border-primary-400 shadow-navbar sticky top-0 z-50"
    x-data="{ mobileOpen: false }">

    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">

            {{-- ── LOGO ── --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0">
                <img src="{{ asset('images/logo-aspapi.png') }}" alt="Logo ASPAPI"
                    class="h-10 w-auto object-contain"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" />
                <div class="hidden w-10 h-10 bg-primary rounded-full items-center justify-center flex-shrink-0">
                    <span class="text-white font-black text-2xs tracking-tight">ASP</span>
                </div>
                <div>
                    <p style="font-family: 'Ethnocentric', serif; color: #d70100; font-size: 0.95rem; line-height: 1; letter-spacing: 0.02em;">
                        ASPAPI
                    </p>
                    <p class="text-xs text-primary-900 tracking-widest mt-0.5 hidden sm:block">
                        Competent · Competitive · Collaborative
                    </p>
                </div>
            </a>

            {{-- ── DESKTOP NAV ── --}}
            <nav class="hidden lg:flex items-center gap-6">

                {{-- Beranda --}}
                <a href="{{ route('home') }}"
                    class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">
                    Beranda
                </a>

                {{-- Profil Dropdown --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="nav-link flex items-center gap-1
                                   {{ request()->is('profil*') || request()->routeIs('regions.*') ? 'nav-link-active' : '' }}">
                        Profil
                        <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 mt-0.5 w-52 bg-white border border-neutral-200 rounded-md shadow-card-hover py-1.5 z-50"
                        style="display: none;">
                        <a href="{{ route('profile.vision-mission') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150">
                            Visi dan Misi
                        </a>
                        <a href="{{ route('profile.history') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150">
                            Sejarah Singkat
                        </a>
                        <a href="{{ route('profile.initiators') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150">
                            Inisiator
                        </a>
                        <a href="{{ route('profile.congress') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150">
                            Kongres
                        </a>
                        <div class="border-t border-neutral-100 my-1"></div>
                        <a href="{{ route('profile.advisors') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150">
                            Dewan Penasihat
                        </a>
                        <a href="{{ route('profile.experts') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150">
                            Dewan Pakar
                        </a>
                        <a href="{{ route('profile.board') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150">
                            Pengurus
                        </a>
                        <div class="border-t border-neutral-100 my-1"></div>
                        <a href="{{ route('regions.index') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150
                                   {{ request()->routeIs('regions.*') ? 'text-primary font-semibold' : '' }}">
                            ASPAPI Daerah
                        </a>
                    </div>
                </div>



                {{-- Publikasi Dropdown --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="nav-link flex items-center gap-1
                                   {{ request()->is('publikasi*') || request()->routeIs('news.*') || request()->routeIs('blog.*') || request()->routeIs('documents.*') ? 'nav-link-active' : '' }}">
                        Publikasi
                        <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 mt-0.5 w-52 bg-white border border-neutral-200 rounded-md shadow-card-hover py-1.5 z-50"
                        style="display: none;">
                        <a href="{{ route('news.index') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150
                                   {{ request()->routeIs('news.*') ? 'text-primary font-semibold' : '' }}">
                            Berita
                        </a>
                        <a href="{{ route('blog.index') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150
                                   {{ request()->routeIs('blog.*') ? 'text-primary font-semibold' : '' }}">
                            Blog
                        </a>
                        <a href="{{ route('public.agenda') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150
                                {{ request()->routeIs('public.agenda') ? 'text-primary font-semibold' : '' }}">
                            Agenda
                        </a>
                        <a href="{{ route('public.seminars') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150
                                   {{ request()->routeIs('public.seminars') ? 'text-primary font-semibold' : '' }}">
                            Seminar & Pelatihan
                        </a>
                        <a href="{{ route('documents.index') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150
                                   {{ request()->routeIs('documents.*') ? 'text-primary font-semibold' : '' }}">
                            Download
                        </a>
                    </div>
                </div>

                {{-- LSP --}}
                <a href="{{ route('lsp.index') }}"
                    class="nav-link {{ request()->routeIs('lsp.*') ? 'nav-link-active' : '' }}">
                    LSP-KAP
                </a>

                {{-- Mitra — di dalam nav, sejajar dengan item lain --}}
                <a href="{{ route('partners.index') }}"
                    class="nav-link {{ request()->routeIs('partners.*') ? 'nav-link-active' : '' }}">
                    Mitra
                </a>

                                {{-- Anggota Dropdown --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="nav-link flex items-center gap-1
                                   {{ request()->is('anggota*') ? 'nav-link-active' : '' }}">
                        Anggota
                        <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 mt-0.5 w-52 bg-white border border-neutral-200 rounded-md shadow-card-hover py-1.5 z-50"
                        style="display: none;">
                        <a href="{{ route('members.register') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150">
                            Pendaftaran Anggota
                        </a>

                        <a href="{{ route ('members.directory') }}"
                            class="flex items-center px-4 py-2.5 text-xs text-neutral-600 hover:bg-primary-100 hover:text-primary transition-colors duration-150">
                            Daftar Anggota
                        </a>
                    </div>
                </div>

            </nav>

            {{-- ── CTA BUTTON (desktop) ── --}}
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                    Login
                </a>
            </div>

            {{-- ── MOBILE HAMBURGER ── --}}
            <button
                class="lg:hidden p-2 rounded text-neutral-500 hover:text-primary hover:bg-primary-100 transition-colors"
                @click="mobileOpen = !mobileOpen"
                aria-label="Toggle menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" style="display:none;" />
                </svg>
            </button>
        </div>
    </div>

    {{-- ── MOBILE MENU ── --}}
    <div x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden bg-white border-t border-neutral-200 overflow-y-auto max-h-[80vh]"
        style="display: none;">

        <nav class="px-4 py-3 flex flex-col divide-y divide-neutral-100">

            {{-- Beranda --}}
            <a href="{{ route('home') }}"
                class="py-3 text-xs font-bold tracking-wider uppercase {{ request()->routeIs('home') ? 'text-primary' : 'text-neutral-600' }} hover:text-primary">
                Beranda
            </a>

            {{-- Profil accordion --}}
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between py-3 text-xs font-bold tracking-wider uppercase {{ request()->is('profil*') || request()->routeIs('regions.*') ? 'text-primary' : 'text-neutral-600' }} hover:text-primary">
                    Profil
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-2 pl-3 flex flex-col gap-0.5" style="display:none;">
                    <a href="{{ route('profile.vision-mission') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Visi dan Misi</a>
                    <a href="{{ route('profile.history') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Sejarah Singkat</a>
                    <a href="{{ route('profile.initiators') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Inisiator</a>
                    <a href="{{ route('profile.congress') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Kongres</a>
                    <div class="border-t border-neutral-100 my-1"></div>
                    <a href="{{ route('profile.advisors') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Dewan Penasihat</a>
                    <a href="{{ route('profile.experts') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Dewan Pakar</a>
                    <a href="{{ route('profile.board') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Pengurus</a>
                    <div class="border-t border-neutral-100 my-1"></div>
                    <a href="{{ route('regions.index') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">ASPAPI Daerah</a>
                </div>
            </div>



            {{-- Publikasi accordion --}}
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between py-3 text-xs font-bold tracking-wider uppercase {{ request()->is('publikasi*') || request()->routeIs('news.*') || request()->routeIs('blog.*') || request()->routeIs('documents.*') ? 'text-primary' : 'text-neutral-600' }} hover:text-primary">
                    Publikasi
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-2 pl-3 flex flex-col gap-0.5" style="display:none;">
                    <a href="{{ route('news.index') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Berita</a>
                    <a href="{{ route('blog.index') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Blog</a>
                    <a href="{{ route('public.agenda') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Agenda</a>
                    <a href="{{ route('public.seminars') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Seminar & Pelatihan</a>
                    <a href="{{ route('documents.index') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Download</a>
                </div>
            </div>

            {{-- LSP --}}
            <a href="{{ route('lsp.index') }}"
                class="py-3 text-xs font-bold tracking-wider uppercase {{ request()->routeIs('lsp.*') ? 'text-primary' : 'text-neutral-600' }} hover:text-primary">
                LSP-KAP
            </a>

            {{-- Mitra --}}
            <a href="{{ route('partners.index') }}"
                class="py-3 text-xs font-bold tracking-wider uppercase {{ request()->routeIs('partners.*') ? 'text-primary' : 'text-neutral-600' }} hover:text-primary">
                Mitra
            </a>

                        {{-- Anggota accordion --}}
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between py-3 text-xs font-bold tracking-wider uppercase {{ request()->is('anggota*') ? 'text-primary' : 'text-neutral-600' }} hover:text-primary">
                    Anggota
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="pb-2 pl-3 flex flex-col gap-0.5" style="display:none;">
                    <a href="{{ route('members.register') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Pendaftaran Anggota</a>
                    <a href="{{ route ('members.directory') }}" class="py-2 text-xs text-neutral-500 hover:text-primary">Daftar Anggota</a>
                </div>
            </div>

            {{-- CTA --}}
            <div class="pt-4 pb-2 flex flex-col gap-3">
                <a href="{{ route('login') }}" class="btn btn-secondary w-full text-center">
                    Login
                </a>
            </div>
        </nav>
    </div>

</header>