@extends('layouts.app')

@php
$title = 'LSP — Lembaga Sertifikasi Profesi';
$description = 'LSP-KAP adalah lembaga sertifikasi profesi administrasi perkantoran di bawah ASPAPI, berlisensi resmi dari BNSP untuk menyelenggarakan uji kompetensi.';
@endphp

@section('content')

{{-- ══════════════════════════════════════════════
     HERO
══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-primary-600 to-primary py-20">

    {{-- Top accent stripe --}}
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-red via-accent-yellow to-accent-yellow"></div>

    {{-- Decorative blobs --}}
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -right-16 -bottom-24 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute left-1/2 top-1/3 w-48 h-48 rounded-full bg-accent-yellow/5 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6">

        {{-- Eyebrow --}}
        <div class="inline-flex items-center gap-2 mb-4">
            <span class="w-8 h-px bg-accent-yellow"></span>
            <span class="text-accent-yellow text-2xs font-bold tracking-widest uppercase">Lembaga Sertifikasi Profesi</span>
        </div>

        <h1 class="font-display text-white leading-tight mb-3" style="font-size: clamp(2rem, 5vw, 3rem);">
            LSP Kompetensi<br/>Administrasi Perkantoran
        </h1>

        <p class="text-primary-300 text-sm max-w-xl leading-relaxed mb-8">
            LSP-KAP adalah lembaga sertifikasi profesi di bawah naungan ASPAPI yang telah mendapatkan
            lisensi resmi dari Badan Nasional Sertifikasi Profesi (BNSP) untuk menyelenggarakan
            uji kompetensi di bidang administrasi perkantoran.
        </p>

        <div class="flex flex-wrap items-center gap-4">
            <a href="https://lsp-kap.com/profil-lsp/"
               target="_blank"
               rel="noopener"
               class="btn btn-accent-yellow btn-lg inline-flex items-center gap-2">
                Kunjungi Website LSP-KAP
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 mt-8 text-2xs text-primary-300">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white font-semibold">LSP</span>
        </nav>

    </div>
</section>


{{-- ══════════════════════════════════════════════
     IDENTITAS LSP
══════════════════════════════════════════════ --}}
<section class="py-5 bg-primary-100 border-b border-primary-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-wrap items-center gap-y-3">

            @php
                $badges = [
                    ['label' => 'Nama Resmi', 'value' => 'LSP Kompetensi Administrasi Perkantoran'],
                    ['label' => 'Singkatan',  'value' => 'LSP-KAP'],
                    ['label' => 'Lisensi',    'value' => 'BNSP'],
                    ['label' => 'Di bawah',   'value' => 'ASPAPI'],
                ];
            @endphp

            @foreach ($badges as $badge)

                @if (!$loop->first)
                    <div class="hidden sm:block w-px h-5 bg-primary-300 mx-6"></div>
                @endif

                <div class="flex items-center gap-2">
                    <span class="text-2xs font-bold tracking-widest uppercase text-neutral-400 whitespace-nowrap">{{ $badge['label'] }}:</span>
                    <span class="text-xs font-bold text-navy whitespace-nowrap">{{ $badge['value'] }}&nbsp;&nbsp;&nbsp;</span>
                </div>

            @endforeach

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     SEJARAH LSP
══════════════════════════════════════════════ --}}
<section class="py-20 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">

            {{-- Kiri: Label + Intro --}}
            <div class="lg:col-span-1">
                <p class="section-label">Latar Belakang</p>
                <h2 class="section-title mt-1">Sejarah LSP</h2>
                <div class="section-divider"></div>
                <p class="text-neutral-500 text-sm leading-relaxed mt-4 mb-6">
                    Perjalanan panjang pendirian Lembaga Sertifikasi Profesi Administrasi Perkantoran
                    yang kini menjadi LSP-KAP berlisensi BNSP.
                </p>
                <a href="https://lsp-kap.com/profil-lsp/"
                   target="_blank"
                   rel="noopener"
                   class="btn btn-outline inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Profil LSP-KAP
                </a>
            </div>

            {{-- Kanan: Timeline --}}
            <div class="lg:col-span-2 flex flex-col gap-0">

                @php
                    $timeline = [
                        [
                            'tahun'  => '2014–2016',
                            'warna'  => 'primary',
                            'judul'  => 'Ide Pendirian Dicetuskan',
                            'isi'    => 'Ide pendirian LSP Administrasi Perkantoran dicetuskan pada kepengurusan ASPAPI Periode 2014–2018 di bawah Ketua Umum Prof. Dr. Tjutju Yuniarsih, S.E., M.Pd.',
                            'border' => 'card-top-blue',
                        ],
                        [
                            'tahun'  => '18–19 Juni 2016',
                            'warna'  => 'primary',
                            'judul'  => 'Rapat Kerja Pendirian LSP',
                            'isi'    => 'ASPAPI mengadakan Rapat Kerja untuk mendirikan LSP-3 di Hotel POSE IN Jl. Monginsidi No. 165 Surakarta. Ditetapkan susunan kepengurusan LSP dengan Keputusan ASPAPI Nomor 016/ASPAPI/VI/2016.',
                            'border' => 'card-top-blue',
                        ],
                        [
                            'tahun'  => '23 Oktober 2016',
                            'warna'  => 'primary',
                            'judul'  => 'Akta Notaris LSP-API',
                            'isi'    => 'LSP diberi nama Lembaga Sertifikasi Profesi Administrasi Perkantoran Indonesia (LSP-API), dikukuhkan dengan Akta Notaris Widyatmoko, SH., Nomor 23, dan terdaftar di Pengadilan Negeri Bandung (Reg. No. 38/LL/2017).',
                            'border' => 'card-top-blue',
                        ],
                        [
                            'tahun'  => '30 September 2017',
                            'warna'  => 'primary',
                            'judul'  => 'Perbaikan Susunan Pengurus',
                            'isi'    => 'Diterbitkan Keputusan ASPAPI Nomor 029/ASPAPI/IX/2017 tentang Perbaikan Susunan Nama Kepengurusan LSP-AP Periode 2016–2019.',
                            'border' => 'card-top-blue',
                        ],
                        [
                            'tahun'  => '3 Mei 2018',
                            'warna'  => 'accent-yellow',
                            'judul'  => 'Pergantian Nama menjadi LSP-AP → LSP-KAP',
                            'isi'    => 'Karena nama LSP-API telah lebih dahulu didaftarkan oleh KADIN Kota Bandung ke BNSP, nama diganti menjadi Lembaga Sertifikasi Profesi Administrasi Perkantoran (LSP-AP), dikukuhkan dengan Akta Notaris Widyatmoko, SH., Nomor 01, terdaftar di Pengadilan Negeri Bandung (Reg. No. 86/LL/2018).',
                            'border' => 'card-top-gold',
                        ],
                    ];
                @endphp

                @foreach ($timeline as $item)
                <div class="flex gap-5 {{ !$loop->last ? 'pb-7' : '' }}">

                    {{-- Spine --}}
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div class="w-4 h-4 rounded-full mt-1 flex-shrink-0 ring-4 ring-white shadow
                                    {{ $item['warna'] === 'accent-yellow' ? 'bg-accent-yellow' : 'bg-primary' }}">
                        </div>
                        @if (!$loop->last)
                            <div class="w-px flex-1 mt-1 bg-primary-200"></div>
                        @endif
                    </div>

                    {{-- Card --}}
                    <div class="flex-1 card {{ $item['border'] }} p-5">
                        <span class="text-2xs font-bold tracking-widest uppercase mb-1 block
                                     {{ $item['warna'] === 'accent-yellow' ? 'text-accent-yellow' : 'text-primary' }}">
                            {{ $item['tahun'] }}
                        </span>
                        <h3 class="text-sm font-bold text-navy mb-2">{{ $item['judul'] }}</h3>
                        <p class="text-xs text-neutral-500 leading-relaxed">{{ $item['isi'] }}</p>
                    </div>

                </div>
                @endforeach

                <p class="text-2xs text-neutral-300 mt-5 italic">
                    Sumber: Tjutju Yuniarsih, Laporan Pertanggungjawaban Pengurus ASPAPI Periode 2014–2018, 6 Oktober 2018.
                </p>

            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     SUSUNAN PENGURUS LSP
══════════════════════════════════════════════ --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-12">
            <p class="section-label">Kepengurusan</p>
            <h2 class="section-title mt-1">Susunan Pengurus LSP-AP</h2>
            <div class="section-divider mx-auto"></div>
            <p class="text-neutral-400 text-xs mt-3">
                Berdasarkan Keputusan ASPAPI Nomor 016/ASPAPI/VI/2016 jo. Nomor 029/ASPAPI/IX/2017 — Periode 2016–2019
            </p>
        </div>

        @php
            $pengurus = [
                [
                    'jabatan' => 'Dewan Pengarah',
                    'icon'    => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    'anggota' => [
                        'Prof. Dr. Muhyadi',
                        'Prof. Dr. Bambang Suratman, M.Pd.',
                        'Ketua Kadin Kota Bandung',
                        'Ketua Kadin Kota Surakarta',
                    ],
                ],
                [
                    'jabatan' => 'Komite Sertifikasi',
                    'icon'    => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                    'anggota' => [
                        'Prof. Dr. Tjutju Yuniarsih, SE., M.Pd. (Ketua Umum ASPAPI)',
                        'Dr. Wiedy Murtini, M.Pd. (Ketua 1 ASPAPI)',
                        'Dr. Budi Santoso, M.Si.',
                        'Dr. Ade Rustiana, M.Si.',
                        'Dra. Patni Ninghardjanti, M.Pd.',
                        'Drs. Saliman, M.Pd.',
                    ],
                ],
            ];

            $pejabat = [
                ['jabatan' => 'Direktur',                       'nama' => 'Drs. M. Jamil, M.M, M.Pd.'],
                ['jabatan' => 'Bagian Sertifikasi',             'nama' => 'Sambas Ali Muhidin, M.Si.'],
                ['jabatan' => 'Bagian Manajemen Mutu',          'nama' => 'Dr. Rasto, M.Pd.'],
                ['jabatan' => 'Bagian Administrasi & Keuangan', 'nama' => 'Marsofiati, M.Pd. — Drs. Uep Tatang Sontani, M.Si.'],
            ];
        @endphp

        {{-- Dewan & Komite --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            @foreach ($pengurus as $group)
            <div class="card card-top-blue p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $group['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-navy">{{ $group['jabatan'] }}</h3>
                </div>
                <ol class="flex flex-col gap-2">
                    @foreach ($group['anggota'] as $j => $nama)
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-primary text-white text-2xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">
                            {{ $j + 1 }}
                        </span>
                        <span class="text-xs text-neutral-500 leading-relaxed">{{ $nama }}</span>
                    </li>
                    @endforeach
                </ol>
            </div>
            @endforeach
        </div>

        {{-- Pejabat struktural --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($pejabat as $p)
            <div class="card p-5 flex flex-col gap-2">
                <span class="text-2xs font-bold tracking-widest uppercase text-primary">{{ $p['jabatan'] }}</span>
                <p class="text-sm font-bold text-navy leading-snug">{{ $p['nama'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ══════════════════════════════════════════════
     PERJALANAN NAMA
══════════════════════════════════════════════ --}}
<section class="py-16 bg-neutral-50">
    <div class="max-w-5xl mx-auto px-6">

        <div class="text-center mb-10">
            <p class="section-label">Identitas Lembaga</p>
            <h2 class="section-title mt-1">Perjalanan Nama LSP</h2>
            <div class="section-divider mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-4">

            {{-- LSP-API --}}
            <div class="card card-top-blue p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center mx-auto mb-3">
                    <span class="font-display text-primary font-bold text-sm">API</span>
                </div>
                <h3 class="text-sm font-bold text-navy mb-1">LSP-API</h3>
                <p class="text-2xs text-neutral-400 leading-relaxed mb-3">
                    Lembaga Sertifikasi Profesi Administrasi Perkantoran Indonesia
                </p>
                <div class="text-2xs text-neutral-300 space-y-0.5">
                    <p>Akta No. 23 — 23 Oktober 2016</p>
                    <p>PN Bandung Reg. No. 38/LL/2017</p>
                </div>
            </div>

            {{-- Panah --}}
            <div class="flex flex-col items-center gap-2 text-center">
                <svg class="w-8 h-8 text-accent-yellow rotate-90 md:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
                <p class="text-2xs text-neutral-400 leading-relaxed max-w-[140px]">
                    Nama telah didaftarkan lebih dulu oleh KADIN Kota Bandung ke BNSP
                </p>
            </div>

            {{-- LSP-KAP --}}
            <div class="overflow-hidden"
                 style="border-radius:4px; border:1px solid #D6E8F7; box-shadow:0 2px 12px rgba(42,127,193,0.08); border-top:4px solid #E8B84B;">
                <div class="p-6 text-center" style="background: linear-gradient(135deg, #111E2A 0%, #253646 100%);">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3"
                         style="background: rgba(232,184,75,0.15); border: 1px solid rgba(232,184,75,0.3);">
                        <span class="font-display font-bold text-sm" style="color: #E8B84B;">KAP</span>
                    </div>
                    <h3 class="text-sm font-bold text-white mb-1">LSP-KAP</h3>
                    <p class="text-2xs leading-relaxed mb-3" style="color: #A8D4F5;">
                        LSP Kompetensi Administrasi Perkantoran
                    </p>
                    <div class="text-2xs space-y-0.5" style="color: #7A9CB8;">
                        <p>Akta No. 01 — 3 Mei 2018</p>
                        <p>PN Bandung Reg. No. 86/LL/2018</p>
                        <p class="font-bold mt-1" style="color: #E8B84B;">✓ Berlisensi BNSP</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     CTA — KUNJUNGI WEBSITE LSP
══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-navy py-16">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-yellow to-accent-red"></div>
    <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-white/5 pointer-events-none"
         style="clip-path: polygon(20% 0%, 100% 0%, 100% 100%, 0% 100%)"></div>

    <div class="max-w-7xl mx-auto px-6 relative">
        <div class="max-w-2xl">
            <p class="section-label" style="color:#E8B84B;">Sertifikasi Profesi</p>
            <h2 class="font-display text-white text-3xl leading-tight mt-1 mb-3">
                Ingin Mengetahui Lebih Lanjut<br/>tentang LSP-KAP?
            </h2>
            <p class="text-primary-200 text-sm leading-relaxed mb-8">
                Kunjungi website resmi LSP-KAP untuk informasi lengkap mengenai skema sertifikasi,
                jadwal uji kompetensi, prosedur pendaftaran, dan daftar asesor bersertifikat.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="https://lsp-kap.com/profil-lsp/"
                   target="_blank"
                   rel="noopener"
                   class="btn btn-accent-yellow btn-lg inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Kunjungi lsp-kap.com
                </a>
                <a href="{{ route('members.register') }}"
                   class="btn btn-lg border-2 border-white/30 text-white hover:bg-white/10">
                    Daftar Anggota ASPAPI
                </a>
            </div>
        </div>
    </div>
</section>


@endsection