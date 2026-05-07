@extends('layouts.app')

@php $title = 'Inisiator'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div style="background: linear-gradient(135deg, #111E2A, #1A5F9A, #2A7FC1); position: relative; padding-top: 3rem; padding-bottom: 2.5rem; padding-left: 1.5rem; padding-right: 1.5rem; overflow: hidden;">
    <div style="position:absolute;inset-x:0;top:0;height:4px;background:linear-gradient(90deg,#C0392B,#E8B84B);"></div>
    <div class="max-w-7xl mx-auto">
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#A8D4F5;margin-bottom:1rem;">
            <a href="{{ route('home') }}" style="color:#A8D4F5;">Beranda</a>
            <span>›</span>
            <span style="color:#fff;font-weight:600;">Inisiator</span>
        </nav>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#E8B84B;margin-bottom:0.5rem;">Profil Organisasi</p>
        <h1 style="font-family:'DM Serif Display',serif;color:#ffffff;font-size:clamp(1.75rem,4vw,2.75rem);line-height:1.2;">
            Inisiator ASPAPI
        </h1>
        <p style="color:#A8D4F5;font-size:0.875rem;margin-top:0.75rem;max-width:600px;line-height:1.7;">
            Para tokoh yang memiliki pemikiran dan keinginan yang sama untuk mendirikan wadah bagi sarjana dan praktisi administrasi perkantoran Indonesia.
        </p>
    </div>
</div>

{{-- ── INTRO ── --}}
<section class="py-14 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <p class="section-label">Pendiri ASPAPI</p>
        <h2 class="section-title mt-1">Mereka yang Memulai</h2>
        <div class="section-divider"></div>
        <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;">
            ASPAPI didirikan oleh para inisiator yang memiliki pemikiran dan keinginan yang sama untuk membuat sebuah organisasi tempat berkumpulnya para sarjana dan praktisi dalam bidang administrasi/manajemen perkantoran di Indonesia. Melalui serangkaian pertemuan sejak 27 Februari 2010, mereka bersama-sama meletakkan fondasi kokoh bagi ASPAPI hingga akhirnya resmi berdiri pada 7 Oktober 2010.
        </p>
    </div>
</section>

{{-- ── INISIATOR GRID ── --}}
<section class="py-14" style="background:#F8FAFC;">
    <div class="max-w-6xl mx-auto px-6">

        @php
        $inisiator = [
            ['name' => 'Prof. Dr. Muhyadi',                 'inst' => 'Universitas Negeri Yogyakarta',  'slug' => 'prof-dr-muhyadi'],
            ['name' => 'Prof. Dr. Bambang Suratman, M.Pd.', 'inst' => 'Universitas Negeri Surabaya',    'slug' => 'prof-dr-bambang-suratman-m-pd'],
            ['name' => 'Prof. Dr. Wiedy Murtini, M.Pd.',    'inst' => 'Universitas Sebelas Maret',      'slug' => 'prof-dr-wiedy-murtini-m-pd'],
            ['name' => 'Prof. Dr. Drs. Saliman, M.Pd.',     'inst' => 'Universitas Negeri Yogyakarta',  'slug' => 'prof-dr-drs-saliman-m-pd'],
            ['name' => 'Prof. Dr. Cicilia Dyah S. I., M.Pd.','inst' => 'Universitas Sebelas Maret',     'slug' => 'prof-dr-cicilia-dyah-s-i-m-pd'],
            ['name' => 'Dr. Patni Ninghardjanti, M.Pd.',    'inst' => 'Universitas Sebelas Maret',      'slug' => 'dr-patni-ninghardjanti-m-pd'],
            ['name' => 'Drs. IGN Wagimin, M.Si.',            'inst' => 'Universitas Sebelas Maret',      'slug' => 'drs-ign-wagimin-m-si'],
            ['name' => 'Drs. Sudaryanto, M.Si.',             'inst' => 'Universitas Negeri Yogyakarta',  'slug' => 'drs-sudaryanto-m-si'],
            ['name' => 'Jamaluddin, S.Pd., M.Si., CRA., CRP.','inst' => 'Universitas Negeri Makassar',  'slug' => 'jamaluddin-s-pd-m-si-cra-crp'],
            ['name' => 'Dr. Ade Rustiana, M.Si.',            'inst' => 'Universitas Negeri Semarang',    'slug' => 'dr-ade-rustiana-m-si'],
            ['name' => 'Dr. Heri Sawiji, M.Pd.',             'inst' => 'Universitas Sebelas Maret',      'slug' => 'dr-heri-sawiji-m-pd'],
            ['name' => 'Drs. Purwanto, M.Si.',               'inst' => 'Universitas Negeri Yogyakarta',  'slug' => 'drs-purwanto-m-si'],
            ['name' => 'M. Farid Sunarto, S.Pd., M.Si.',     'inst' => 'Praktisi',                       'slug' => 'm-farid-sunarto-s-pd-m-si'],
            ['name' => 'Dr. Agus Hermawan, GradDipMgt., M.Si., M.Bus.','inst' => 'Universitas Negeri Malang','slug' => 'dr-agus-hermawan-graddipmgt-m-si-m-bus'],
            ['name' => 'Dr. Drs. Edy Ramon Torong, SH., MM.','inst' => 'Praktisi',                      'slug' => 'dr-drs-edy-ramon-torong-sh-mm'],
            ['name' => 'Drs. Suhirman',                      'inst' => 'SMK Negeri 6 Surakarta',         'slug' => 'drs-suhirman'],
            ['name' => 'Drs. Bangkit Budiarto, M.Pd.',       'inst' => 'SMK Negeri 7 Surakarta',         'slug' => 'drs-bangkit-budiarto-m-pd'],
        ];
        @endphp

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1.5rem;">
            @foreach ($inisiator as $i => $person)
            @php
                $slug = $person['slug'];
                $initial = strtoupper(substr(preg_replace('/^(Prof\.|Dr\.|Drs\.|Dra\.)\s*/i', '', $person['name']), 0, 1));
                $hasPhoto = file_exists(public_path('images/inisiator/' . $slug . '.png'));
            @endphp
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;transition:box-shadow 0.2s,transform 0.2s;"
                 onmouseover="this.style.boxShadow='0 8px 32px rgba(42,127,193,0.14)';this.style.transform='translateY(-3px)'"
                 onmouseout="this.style.boxShadow='none';this.style.transform='translateY(0)'">

                {{-- Foto --}}
                <div style="width:100%;height:220px;background:#EEF4FB;overflow:hidden;position:relative;">
                    @if ($hasPhoto)
                        <img src="{{ asset('images/inisiator/' . $slug . '.png') }}"
                             alt="{{ $person['name'] }}"
                             style="width:100%;height:100%;object-fit:cover;object-position:top;"/>
                    @else
                        {{-- Placeholder --}}
                        <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg,#EEF4FB,#D6E8F7);">
                            <div style="width:80px;height:80px;border-radius:50%;background:#2A7FC1;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                                <span style="font-family:'DM Serif Display',serif;font-size:2rem;color:#fff;font-weight:700;">{{ $initial }}</span>
                            </div>
                            <span style="font-size:0.65rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#B0CCDF;">Foto belum tersedia</span>
                        </div>
                    @endif
                    {{-- Nomor urut overlay --}}
                    <div style="position:absolute;top:0.75rem;left:0.75rem;width:28px;height:28px;border-radius:50%;background:#2A7FC1;color:#fff;font-size:0.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;">
                        {{ $i + 1 }}
                    </div>
                </div>

                {{-- Info --}}
                <div style="padding:1rem 1.25rem 1.25rem;">
                    <div style="width:32px;height:3px;background:#E8B84B;border-radius:2px;margin-bottom:0.75rem;"></div>
                    <p style="font-size:0.875rem;font-weight:700;color:#1A2A3A;line-height:1.4;">{{ $person['name'] }}</p>
                    <p style="font-size:0.775rem;color:#4A6580;margin-top:0.375rem;display:flex;align-items:flex-start;gap:0.375rem;">
                        <svg style="width:13px;height:13px;flex-shrink:0;margin-top:2px;color:#B0CCDF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ $person['inst'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ── CTA ── --}}
<section class="py-14 bg-white">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <p class="section-label">Teruskan Semangat</p>
        <h2 class="section-title mt-1">Jadilah Bagian dari ASPAPI</h2>
        <div class="section-divider mx-auto"></div>
        <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;max-width:520px;margin:0 auto 2rem;">
            Para inisiator telah meletakkan fondasi yang kokoh. Kini giliran Anda untuk meneruskan semangat mereka dalam memajukan profesi administrasi perkantoran Indonesia.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('members.register') }}" class="btn btn-primary">Daftar Anggota</a>
            <a href="{{ route('profile.history') }}" class="btn btn-outline">Baca Sejarah ASPAPI</a>
        </div>
    </div>
</section>

@endsection