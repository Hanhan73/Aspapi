{{--
    Footer Component — ASPAPI
    Usage: @include('components.footer')
--}}

<footer class="bg-navy text-neutral-300">

    {{-- ── MAIN FOOTER ── --}}
    <div class="max-w-7xl mx-auto px-6 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Brand --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo-aspapi.png') }}"
                         alt="Logo ASPAPI"
                         class="h-10 w-auto object-contain brightness-0 invert opacity-90"
                         onerror="this.style.display='none'"/>
                    <span class="text-white font-extrabold text-base tracking-wide">ASPAPI</span>
                </div>
                <p class="text-sm text-neutral-400 leading-relaxed mb-4">
                    Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia.
                    Berdiri sejak 2010, berkomitmen membangun profesionalisme dan daya saing global.
                </p>
                <p class="text-xs text-accent-yellow font-medium italic tracking-wide">
                    Competent · Competitive · Collaborative
                </p>
                {{-- Sosial media --}}
                <div class="flex items-center gap-3 mt-5">
                    <a href="#" class="w-8 h-8 rounded bg-white/10 flex items-center justify-center
                                       hover:bg-primary transition-colors duration-200"
                       title="Instagram ASPAPI">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded bg-white/10 flex items-center justify-center
                                       hover:bg-primary transition-colors duration-200"
                       title="Facebook ASPAPI">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded bg-white/10 flex items-center justify-center
                                       hover:bg-primary transition-colors duration-200"
                       title="YouTube ASPAPI">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Profil --}}
            <div>
                <h4 class="text-2xs font-bold tracking-widest uppercase text-white mb-4">Profil</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('profile.vision-mission') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Visi dan Misi</a></li>
                    <li><a href="{{ route('profile.history') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Sejarah Singkat</a></li>
                    <li><a href="{{ route('profile.initiators') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Inisiator</a></li>
                    <li><a href="{{ route('profile.congress') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Kongres</a></li>
                    <li><a href="{{ route('profile.advisors') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Dewan Penasihat</a></li>
                    <li><a href="{{ route('profile.experts') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Dewan Pakar</a></li>
                    <li><a href="{{ route('profile.board') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Pengurus</a></li>
                </ul>
            </div>

            {{-- Anggota & Layanan --}}
            <div>
                <h4 class="text-2xs font-bold tracking-widest uppercase text-white mb-4">Keanggotaan</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('members.types') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Jenis & Syarat Anggota</a></li>
                    <li><a href="{{ route('members.register') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Pendaftaran Anggota</a></li>
                    <li><a href="{{ route('regions.index') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">ASPAPI Daerah</a></li>
                    <li><a href="{{ route('lsp.index') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">LSP</a></li>
                    <li><a href="{{ route('documents.index') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Download Dokumen</a></li>
                </ul>

                <h4 class="text-2xs font-bold tracking-widest uppercase text-white mb-3 mt-6">Konten</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('news.index') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Berita</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">Blog</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h4 class="text-2xs font-bold tracking-widest uppercase text-white mb-4">Kontak</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-primary-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm text-neutral-400 leading-relaxed">
                            Sekretariat ASPAPI Pusat<br/>Indonesia
                        </span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:info@aspapi.or.id"
                           class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">
                            info@aspapi.or.id
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                        </svg>
                        <a href="https://aspapi.or.id" target="_blank"
                           class="text-sm text-neutral-400 hover:text-accent-yellow transition-colors duration-200">
                            aspapi.or.id
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- ── BOTTOM BAR ── --}}
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-neutral-600">
                &copy; {{ date('Y') }} ASPAPI — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia.
                Hak Cipta Dilindungi.
            </p>
            <div class="flex items-center gap-5">
                <a href="#" class="text-xs text-neutral-600 hover:text-accent-yellow transition-colors duration-200">
                    Kebijakan Privasi
                </a>
                <a href="#" class="text-xs text-neutral-600 hover:text-accent-yellow transition-colors duration-200">
                    Syarat & Ketentuan
                </a>
            </div>
        </div>
    </div>

</footer>
