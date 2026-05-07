@extends('layouts.app')

@php $title = 'Registrasi & Iuran Anggota'; @endphp

@section('content')

{{-- ══════════════════════════════════════════════
     HERO
══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-primary-600 to-primary py-20">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-red via-accent-yellow to-accent-yellow"></div>
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -right-16 -bottom-24 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 mb-4">
            <span class="w-8 h-px bg-accent-yellow"></span>
            <span class="text-accent-yellow text-2xs font-bold tracking-widest uppercase">Keanggotaan ASPAPI</span>
        </div>
        <h1 class="font-display text-white text-4xl leading-tight mb-3">
            Registrasi & Iuran Anggota
        </h1>
        <p class="text-primary-200 text-sm max-w-xl leading-relaxed">
            Ikuti langkah-langkah berikut untuk mendaftar sebagai anggota ASPAPI dan dapatkan kartu anggota resmi Anda.
        </p>

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 mt-6 text-2xs text-primary-300">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white font-medium">Anggota</span>
            <span>/</span>
            <span class="text-white font-medium">Registrasi & Iuran</span>
        </nav>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     TAB NAVIGASI
══════════════════════════════════════════════ --}}
<div class="bg-white border-b border-neutral-200 sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-6">
        <nav class="flex gap-0 overflow-x-auto">
            <a href="{{ route('members.types') }}"
               class="inline-flex items-center gap-2 px-5 py-4 text-xs font-bold tracking-widest uppercase border-b-2 border-transparent text-neutral-400 hover:text-primary hover:border-primary-300 whitespace-nowrap transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Jenis & Syarat
            </a>
            <a href="{{ route('members.register') }}"
               class="inline-flex items-center gap-2 px-5 py-4 text-xs font-bold tracking-widest uppercase border-b-2 border-primary text-primary whitespace-nowrap transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Registrasi & Iuran
            </a>
        </nav>
    </div>
</div>


{{-- ══════════════════════════════════════════════
     INFO REKENING
══════════════════════════════════════════════ --}}
<section class="py-10 bg-primary-100 border-b border-primary-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="w-10 h-10 rounded bg-primary flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-2xs font-bold tracking-widest uppercase text-primary-600 mb-1">Informasi Rekening Pembayaran</p>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-neutral-500">Bank BNI</span>
                        <span class="text-sm font-bold text-navy">1661531545</span>
                    </div>
                    <div class="w-px h-4 bg-neutral-300 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-neutral-500">a/n</span>
                        <span class="text-sm font-bold text-navy">Sitti Hardiyanti Arhas</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="bg-white rounded px-4 py-2 text-center border border-primary-200">
                    <p class="text-2xs text-neutral-400 mb-0.5">Anggota Baru</p>
                    <p class="text-sm font-bold text-primary">Rp 250.000</p>
                </div>
                <div class="bg-white rounded px-4 py-2 text-center border border-primary-200">
                    <p class="text-2xs text-neutral-400 mb-0.5">Anggota Lama</p>
                    <p class="text-sm font-bold text-primary">Rp 120.000</p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     DUA JALUR PENDAFTARAN
══════════════════════════════════════════════ --}}
<section class="py-16 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-12">
            <p class="section-label">Cara Mendaftar</p>
            <h2 class="section-title mt-1">Pilih Jalur Pendaftaran</h2>
            <div class="section-divider mx-auto"></div>
            <p class="text-neutral-500 text-sm max-w-xl mx-auto mt-4">
                Tersedia dua jalur pendaftaran — untuk anggota baru yang belum pernah terdaftar,
                dan untuk anggota lama yang ingin memperbarui keanggotaan.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- ── ANGGOTA BARU ── --}}
            <div class="card card-top-blue flex flex-col">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-primary to-primary-600 rounded-t p-6 text-white">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <span class="text-2xs font-bold tracking-widest uppercase text-primary-200">Jalur A</span>
                            <h3 class="font-display text-xl mt-1">Anggota Baru</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-primary-200 text-xs leading-relaxed">
                        Untuk Anda yang belum pernah terdaftar sebagai anggota ASPAPI sebelumnya.
                    </p>

                    {{-- Biaya badge --}}
                    <div class="mt-4 inline-flex items-center gap-2 bg-white/15 rounded px-3 py-1.5">
                        <svg class="w-3.5 h-3.5 text-accent-yellow" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-white text-xs font-bold">Uang Pangkal + Iuran Tahunan: Rp 250.000</span>
                    </div>
                </div>

                {{-- Steps --}}
                <div class="p-6 flex-1">
                    @php
                        $stepsNew = [
                            [
                                'label' => 'Buat Akun Anggota',
                                'desc'  => 'Daftarkan diri melalui portal member ASPAPI.',
                                'url'   => route('register'),
                                'cta'   => 'Buat Akun',
                            ],
                            [
                                'label' => 'Lengkapi Biodata',
                                'desc'  => 'Isi data diri dan informasi profil keanggotaan Anda.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                            [
                                'label' => 'Transfer Biaya Pendaftaran',
                                'desc'  => 'Transfer Uang Pangkal & Iuran Tahunan sebesar Rp 250.000 ke rekening BNI 1661531545 a/n Sitti Hardiyanti Arhas.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                            [
                                'label' => 'Upload Bukti Transfer',
                                'desc'  => 'Unggah bukti pembayaran Uang Pangkal dan Iuran Tahunan melalui portal.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                            [
                                'label' => 'Generate Kartu Anggota',
                                'desc'  => 'Setelah verifikasi, generate kartu anggota digital Anda.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                            [
                                'label' => 'Download Kartu Anggota',
                                'desc'  => 'Unduh kartu anggota resmi ASPAPI Anda.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                        ];
                    @endphp

                    <ol class="flex flex-col gap-0">
                        @foreach ($stepsNew as $i => $step)
                        <li class="flex gap-4 {{ !$loop->last ? 'pb-5' : '' }}">
                            {{-- Step indicator & line --}}
                            <div class="flex flex-col items-center flex-shrink-0">
                                <div class="w-7 h-7 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center">
                                    {{ $i + 1 }}
                                </div>
                                @if (!$loop->last)
                                <div class="w-px flex-1 bg-primary-200 mt-1"></div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 pt-0.5 {{ !$loop->last ? 'pb-1' : '' }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-bold text-navy">{{ $step['label'] }}</p>
                                        <p class="text-xs text-neutral-400 leading-relaxed mt-0.5">{{ $step['desc'] }}</p>
                                    </div>
                                    @if ($step['url'])
                                    <a href="{{ $step['url'] }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="btn btn-primary btn-sm flex-shrink-0 text-2xs">
                                        {{ $step['cta'] }} →
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ol>
                </div>

                {{-- Footer CTA --}}
                <div class="px-6 pb-6">
                    <a href="{{ route('register') }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-primary w-full justify-center">
                        Daftar Sebagai Anggota Baru →
                    </a>
                </div>
            </div>


            {{-- ── ANGGOTA LAMA ── --}}
            <div class="flex flex-col overflow-hidden"
                 style="border-radius: 4px; border: 1px solid #D6E8F7; box-shadow: 0 2px 12px rgba(42,127,193,0.08); border-top: 4px solid #E8B84B;">

                {{-- Header — navy gelap, aksen kuning --}}
                <div class="p-6 text-white flex flex-col gap-3"
                     style="background: linear-gradient(135deg, #1A2A3A 0%, #253646 100%);">

                    <div class="flex items-start justify-between">
                        <div>
                            <span class="font-bold tracking-widest uppercase"
                                  style="font-size: 0.65rem; color: #E8B84B; letter-spacing: 0.08em;">Jalur B</span>
                            <h3 class="font-display text-xl mt-1 text-white">Anggota Lama</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background: rgba(232,184,75,0.15); border: 1px solid rgba(232,184,75,0.3);">
                            <svg class="w-6 h-6" style="color: #E8B84B;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>

                    <p style="font-size: 0.75rem; color: #A8D4F5; line-height: 1.5;">
                        Untuk anggota yang sudah pernah terdaftar dan ingin memperbarui keanggotaan.
                    </p>

                    {{-- Biaya badge --}}
                    <div class="inline-flex items-center gap-2 self-start px-3 py-1.5 rounded"
                         style="background: rgba(232,184,75,0.15); border: 1px solid rgba(232,184,75,0.3);">
                        <svg class="w-3.5 h-3.5" style="color: #E8B84B;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-bold" style="font-size: 0.75rem; color: #E8B84B;">Iuran Tahunan: Rp 120.000</span>
                    </div>
                </div>

                {{-- Steps --}}
                <div class="p-6 flex-1 bg-white">
                    @php
                        $stepsOld = [
                            [
                                'label' => 'Buat Akun Anggota Lama',
                                'desc'  => 'Daftarkan akun melalui portal khusus anggota lama ASPAPI.',
                                'url'   => route('register.old'),
                                'cta'   => 'Buat Akun',
                            ],
                            [
                                'label' => 'Login Akun',
                                'desc'  => 'Login setelah memperoleh verifikasi dari Admin ASPAPI.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                            [
                                'label' => 'Lengkapi Biodata',
                                'desc'  => 'Perbarui data diri dan informasi profil keanggotaan Anda.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                            [
                                'label' => 'Transfer Iuran Tahunan',
                                'desc'  => 'Transfer Iuran Tahunan sebesar Rp 120.000 ke rekening BNI 1661531545 a/n Sitti Hardiyanti Arhas.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                            [
                                'label' => 'Upload Bukti Transfer',
                                'desc'  => 'Unggah bukti pembayaran Iuran Tahunan melalui portal.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                            [
                                'label' => 'Generate Kartu Anggota',
                                'desc'  => 'Setelah verifikasi, generate kartu anggota digital Anda.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                            [
                                'label' => 'Download Kartu Anggota',
                                'desc'  => 'Unduh kartu anggota resmi ASPAPI Anda.',
                                'url'   => null,
                                'cta'   => null,
                            ],
                        ];
                    @endphp

                    <ol class="flex flex-col gap-0">
                        @foreach ($stepsOld as $i => $step)
                        <li class="flex gap-4 {{ !$loop->last ? 'pb-5' : '' }}">
                            <div class="flex flex-col items-center flex-shrink-0">
                                {{-- Nomor bulat: navy bg, teks kuning --}}
                                <div class="w-7 h-7 rounded-full text-xs font-bold flex items-center justify-center"
                                     style="background: #1A2A3A; color: #E8B84B;">
                                    {{ $i + 1 }}
                                </div>
                                @if (!$loop->last)
                                <div class="w-px flex-1 mt-1" style="background: #D6E8F7;"></div>
                                @endif
                            </div>

                            <div class="flex-1 pt-0.5 {{ !$loop->last ? 'pb-1' : '' }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-bold text-navy">{{ $step['label'] }}</p>
                                        <p class="text-xs leading-relaxed mt-0.5" style="color: #7A9CB8;">{{ $step['desc'] }}</p>
                                    </div>
                                    @if ($step['url'])
                                    <a href="{{ $step['url'] }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="flex-shrink-0 inline-flex items-center gap-1 font-bold rounded transition-opacity hover:opacity-80"
                                       style="background: #1A2A3A; color: #E8B84B; font-size: 0.65rem; letter-spacing: 0.08em; padding: 0.4rem 0.75rem; white-space: nowrap;">
                                        {{ strtoupper($step['cta']) }} →
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ol>
                </div>

                {{-- Footer CTA --}}
                <div class="px-6 pb-6 bg-white">
                    <a href="{{ route('register.old') }}"
                       target="_blank"
                       rel="noopener"
                       class="w-full flex items-center justify-center font-bold rounded transition-opacity hover:opacity-90"
                       style="background: #1A2A3A; color: #E8B84B; font-size: 0.75rem; letter-spacing: 0.08em; padding: 0.75rem 1.5rem; text-transform: uppercase;">
                        Daftar Sebagai Anggota Lama →
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     FAQ / CATATAN PENTING
══════════════════════════════════════════════ --}}
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-6">

        <div class="text-center mb-10">
            <p class="section-label">Perlu Diketahui</p>
            <h2 class="section-title mt-1">Catatan Penting</h2>
            <div class="section-divider mx-auto"></div>
        </div>

        <div class="flex flex-col gap-4">
            @php
                $notes = [
                    [
                        'q' => 'Apa perbedaan Uang Pangkal dan Iuran Tahunan?',
                        'a' => 'Uang Pangkal adalah biaya pendaftaran yang dibayar sekali saat pertama kali menjadi anggota baru. Iuran Tahunan adalah biaya keanggotaan yang dibayarkan setiap tahun. Anggota baru membayar keduanya (Rp 250.000), sedangkan anggota lama hanya membayar Iuran Tahunan (Rp 120.000).',
                    ],
                    [
                        'q' => 'Berapa lama proses verifikasi pembayaran?',
                        'a' => 'Proses verifikasi biasanya membutuhkan waktu 1–3 hari kerja setelah bukti transfer diunggah. Pastikan bukti transfer yang diunggah jelas dan terbaca.',
                    ],
                    [
                        'q' => 'Apakah kartu anggota bisa dicetak?',
                        'a' => 'Ya. Setelah kartu anggota di-generate, Anda dapat mengunduhnya dalam format PDF dan mencetaknya secara mandiri, atau cukup menunjukkan versi digitalnya.',
                    ],
                    [
                        'q' => 'Bagaimana jika sudah pernah terdaftar tapi lupa nomor anggota?',
                        'a' => 'Silakan hubungi admin ASPAPI melalui kontak yang tersedia untuk verifikasi data dan pemulihan akun.',
                    ],
                ];
            @endphp

            @foreach ($notes as $note)
            <div class="card p-5" x-data="{ open: false }">
                <button class="w-full flex items-start justify-between gap-4 text-left"
                        @click="open = !open">
                    <p class="text-sm font-bold text-navy leading-snug">{{ $note['q'] }}</p>
                    <svg class="w-4 h-4 text-primary flex-shrink-0 mt-0.5 transition-transform duration-200"
                         :class="{ 'rotate-180': open }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-3 pt-3 border-t border-neutral-100"
                     style="display: none;">
                    <p class="text-xs text-neutral-500 leading-relaxed">{{ $note['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════
     CTA
══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-navy py-14">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-yellow to-accent-red"></div>
    <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-white/5 pointer-events-none"
         style="clip-path: polygon(20% 0%, 100% 0%, 100% 100%, 0% 100%)"></div>

    <div class="max-w-7xl mx-auto px-6 relative text-center">
        <h2 class="font-display text-white text-3xl mb-3">Masih Ada Pertanyaan?</h2>
        <p class="text-primary-200 text-sm mb-8 max-w-lg mx-auto">
            Hubungi pengurus ASPAPI terdekat atau cek halaman kontak kami untuk informasi lebih lanjut.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('members.types') }}" class="btn btn-lg border-2 border-white/30 text-white hover:bg-white/10">
                ← Lihat Jenis Anggota
            </a>
        </div>
    </div>
</section>

@endsection