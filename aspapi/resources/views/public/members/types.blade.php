@extends('layouts.app')

@php
$title = 'Jenis & Syarat Anggota';
$description = 'Pelajari jenis keanggotaan ASPAPI — Anggota Biasa, Luar Biasa, dan Kehormatan — beserta syarat, hak, dan kewajiban sebagai anggota.';
@endphp

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
            Jenis & Syarat Anggota
        </h1>
        <p class="text-primary-200 text-sm max-w-xl leading-relaxed">
            ASPAPI membuka keanggotaan bagi sarjana, dosen, guru, dan praktisi administrasi perkantoran
            di seluruh Indonesia. Temukan kategori keanggotaan yang sesuai dengan latar belakang Anda.
        </p>

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 mt-6 text-2xs text-primary-300">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-white font-medium">Anggota</span>
            <span>/</span>
            <span class="text-white font-medium">Jenis & Syarat</span>
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
               class="inline-flex items-center gap-2 px-5 py-4 text-xs font-bold tracking-widest uppercase border-b-2 border-primary text-primary whitespace-nowrap transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Jenis & Syarat
            </a>
            <a href="{{ route('members.register') }}"
               class="inline-flex items-center gap-2 px-5 py-4 text-xs font-bold tracking-widest uppercase border-b-2 border-transparent text-neutral-400 hover:text-primary hover:border-primary-300 whitespace-nowrap transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Registrasi & Iuran
            </a>
        </nav>
    </div>
</div>


{{-- ══════════════════════════════════════════════
     JENIS KEANGGOTAAN
══════════════════════════════════════════════ --}}
<section class="py-16 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="mb-10">
            <p class="section-label">Klasifikasi</p>
            <h2 class="section-title mt-1">Jenis Keanggotaan</h2>
            <div class="section-divider"></div>
            <p class="text-neutral-500 text-sm leading-relaxed max-w-2xl mt-4">
                Berdasarkan Anggaran Dasar ASPAPI, keanggotaan dibagi menjadi tiga kategori yang berlaku
                untuk seluruh tingkatan organisasi — pusat, provinsi, maupun kabupaten/kota.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Anggota Biasa --}}
            <div class="card card-top-blue card-hover flex flex-col">
                <div class="p-6 border-b border-neutral-100">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="badge badge-blue mb-1">Kategori A</span>
                            <h3 class="text-sm font-bold text-navy">Anggota Biasa</h3>
                        </div>
                    </div>
                    <p class="text-xs text-neutral-400 leading-relaxed">
                        Sarjana, dosen, guru, dan praktisi di bidang administrasi dan manajemen perkantoran.
                    </p>
                </div>
                <div class="p-6 flex-1">
                    <p class="text-2xs font-bold tracking-widest uppercase text-neutral-300 mb-3">Kriteria Keanggotaan</p>
                    <ol class="flex flex-col gap-2.5">
                        @php
                            $biasa = [
                                'Sarjana Pendidikan Administrasi Perkantoran',
                                'Sarjana Pendidikan Manajemen Perkantoran',
                                'Sarjana Ilmu Administrasi',
                                'Sarjana Ilmu Manajemen',
                                'Dosen Pendidikan Administrasi/Manajemen Perkantoran, Ilmu Administrasi, atau Ilmu Manajemen',
                                'Guru bidang keahlian Administrasi/Manajemen Perkantoran',
                                'Praktisi Ilmu Administrasi/Manajemen/Administrasi Perkantoran/Manajemen Perkantoran',
                                'Pemerhati bidang Ilmu Administrasi/Manajemen/Administrasi Perkantoran/Manajemen Perkantoran',
                            ];
                        @endphp
                        @foreach ($biasa as $i => $item)
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-primary-100 text-primary text-2xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                            <span class="text-xs text-neutral-500 leading-relaxed">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            {{-- Anggota Luar Biasa --}}
            <div class="card card-top-yellow card-hover flex flex-col">
                <div class="p-6 border-b border-neutral-100">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded bg-yellow-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="badge" style="background:#FEF3C7; color:#92400E; font-size:0.6rem;">Kategori B</span>
                            <h3 class="text-sm font-bold text-navy mt-1">Anggota Luar Biasa</h3>
                        </div>
                    </div>
                    <p class="text-xs text-neutral-400 leading-relaxed">
                        Sarjana dan dosen/guru di luar bidang administrasi dan manajemen perkantoran.
                    </p>
                </div>
                <div class="p-6 flex-1">
                    <p class="text-2xs font-bold tracking-widest uppercase text-neutral-300 mb-3">Kriteria Keanggotaan</p>
                    <ol class="flex flex-col gap-2.5">
                        @php
                            $luarBiasa = [
                                'Sarjana di luar bidang Ilmu Administrasi/Ilmu Manajemen/Ilmu Administrasi Perkantoran/Ilmu Manajemen Perkantoran',
                                'Dosen/Guru di luar bidang Ilmu Administrasi/Ilmu Manajemen/Ilmu Administrasi Perkantoran/Ilmu Manajemen Perkantoran',
                            ];
                        @endphp
                        @foreach ($luarBiasa as $i => $item)
                        <li class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-yellow-100 text-yellow-700 text-2xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                            <span class="text-xs text-neutral-500 leading-relaxed">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            {{-- Anggota Kehormatan --}}
            <div class="card card-top-red card-hover flex flex-col">
                <div class="p-6 border-b border-neutral-100">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded bg-red-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="badge" style="background:#FEE2E2; color:#991B1B; font-size:0.6rem;">Kategori C</span>
                            <h3 class="text-sm font-bold text-navy mt-1">Anggota Kehormatan</h3>
                        </div>
                    </div>
                    <p class="text-xs text-neutral-400 leading-relaxed">
                        Warga negara Indonesia dan/atau asing yang memiliki komitmen dan jasa terhadap pengembangan
                        pendidikan ilmu administrasi dan manajemen di Indonesia.
                    </p>
                </div>
                <div class="p-6 flex-1">
                    <p class="text-2xs font-bold tracking-widest uppercase text-neutral-300 mb-3">Ketentuan</p>
                    <div class="bg-red-50 rounded p-4 border border-red-100">
                        <p class="text-xs text-neutral-600 leading-relaxed">
                            Ditetapkan oleh <strong class="text-navy">Pengurus Pusat</strong>, <strong class="text-navy">Pengurus Daerah</strong>,
                            atau <strong class="text-navy">Pengurus Cabang</strong> berdasarkan kedudukan, peran, serta jasa yang bersangkutan
                            terhadap pengembangan Pendidikan Ilmu Administrasi dan Manajemen di Indonesia.
                        </p>
                    </div>

                    <div class="mt-4 p-3 bg-neutral-50 rounded border border-neutral-100">
                        <p class="text-2xs text-neutral-400 leading-relaxed">
                            <span class="font-bold text-neutral-500">Catatan:</span> Jenis keanggotaan di atas berlaku untuk semua anggota
                            di tingkat pusat, provinsi, maupun kabupaten/kota.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     KEWAJIBAN DAN HAK ANGGOTA
══════════════════════════════════════════════ --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-10">
            <p class="section-label">Aturan Keanggotaan</p>
            <h2 class="section-title mt-1">Kewajiban & Hak Anggota</h2>
            <div class="section-divider mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">

            {{-- Kewajiban --}}
            <div class="card card-top-blue p-7">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-navy">Kewajiban Anggota</h3>
                </div>

                @php
                    $kewajiban = [
                        'Berpartisipasi aktif dalam kegiatan ASPAPI',
                        'Memilih Pengurus',
                        'Menghadiri Kongres, Musyawarah, dan Rapat',
                        'Membayar iuran',
                    ];
                @endphp

                <ol class="flex flex-col gap-3">
                    @foreach ($kewajiban as $i => $item)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white text-2xs font-bold flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <span class="text-sm text-neutral-500 leading-relaxed">{{ $item }}</span>
                    </li>
                    @endforeach
                </ol>
            </div>

            {{-- Hak --}}
            <div class="card card-top-yellow p-7">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded bg-yellow-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-accent-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-navy">Hak Anggota</h3>
                </div>

                @php
                    $hak = [
                        'Memperoleh Kartu Anggota dengan Nomor Anggota (NA)',
                        'Dipilih sebagai Pengurus',
                        'Memberikan suara dan berpendapat dalam Kongres, Musyawarah, dan Rapat',
                        'Mengikuti seleksi calon Asesor Uji Kompetensi atau Uji Sertifikasi Profesi, sesuai ketentuan',
                        'Ditetapkan sebagai Asesor Uji Kompetensi atau Uji Sertifikasi Profesi, setelah lulus seleksi',
                        'Mendapatkan perlindungan profesi',
                    ];
                @endphp

                <ol class="flex flex-col gap-3">
                    @foreach ($hak as $i => $item)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-accent-yellow flex items-center justify-center text-white text-2xs font-bold flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                        <span class="text-sm text-neutral-500 leading-relaxed">{{ $item }}</span>
                    </li>
                    @endforeach
                </ol>
            </div>

        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     BERAKHIRNYA KEANGGOTAAN
══════════════════════════════════════════════ --}}
<section class="py-16 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="max-w-3xl mx-auto">
            <p class="section-label">Ketentuan</p>
            <h2 class="section-title mt-1">Berakhirnya Keanggotaan</h2>
            <div class="section-divider"></div>
            <p class="text-neutral-500 text-sm leading-relaxed mt-4 mb-8">
                Seorang anggota dinyatakan berakhir keanggotaannya apabila memenuhi salah satu kondisi berikut:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $berakhir = [
                        ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'Meninggal Dunia'],
                        ['icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636', 'label' => 'Berhalangan Tetap'],
                        ['icon' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1', 'label' => 'Mengundurkan Diri'],
                        ['icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'label' => 'Pelanggaran Kode Etik'],
                        ['icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3', 'label' => 'Putusan Pengadilan Berkekuatan Hukum Tetap'],
                    ];
                @endphp

                @foreach ($berakhir as $item)
                <div class="card p-5 flex items-start gap-3">
                    <div class="w-8 h-8 rounded bg-red-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-accent-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <p class="text-xs text-neutral-600 font-medium leading-snug pt-1">{{ $item['label'] }}</p>
                </div>
                @endforeach
            </div>
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
        <h2 class="font-display text-white text-3xl mb-3">Siap Bergabung?</h2>
        <p class="text-primary-200 text-sm mb-8 max-w-xl mx-auto">
            Daftarkan diri Anda sekarang dan jadilah bagian dari komunitas profesional administrasi perkantoran Indonesia.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('members.register') }}" class="btn btn-accent-yellow btn-lg">
                Lihat Cara Pendaftaran →
            </a>
        </div>
    </div>
</section>

@endsection