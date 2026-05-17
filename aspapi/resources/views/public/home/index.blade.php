@extends('layouts.app')

@section('content')

{{-- ══════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-primary-600 to-primary min-h-[580px] flex items-center">

    {{-- Top accent bar --}}
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-red via-accent-yellow to-accent-yellow"></div>

    {{-- Background decorative circles --}}
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -right-16 -bottom-24 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute left-1/2 top-1/3 w-48 h-48 rounded-full bg-accent-yellow/5 pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-20 w-full">
        <div class="max-w-2xl">

            {{-- Label --}}
            <div class="inline-flex items-center gap-2 mb-5">
                <span class="w-8 h-px bg-accent-yellow"></span>
                <span class="text-accent-yellow text-2xs font-bold tracking-widest uppercase">
                    Asosiasi Profesi Administrasi Perkantoran
                </span>
            </div>

            {{-- Headline --}}
            <h1 class="font-display text-white leading-tight mb-4" style="font-size: clamp(2rem, 5vw, 3.25rem);">
                Membangun Profesionalisme<br/>
                <span class="text-white italic">Administrasi Perkantoran</span><br/>
                Indonesia
            </h1>

            {{-- Tagline --}}
            <p class="text-primary-200 text-base leading-relaxed mb-8 max-w-lg">
                ASPAPI hadir sebagai wadah sarjana dan praktisi administrasi perkantoran
                untuk berkembang, bersaing, dan berkolaborasi di tingkat nasional dan global.
            </p>

            {{-- Tagline badge --}}
            <p class="text-accent-yellow text-xs font-bold tracking-widest uppercase mb-8 italic">
                Competent &middot; Competitive &middot; Collaborative
            </p>

            {{-- CTA --}}
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('members.register') }}" class="btn btn-accent-yellow btn-lg">
                    Daftar Anggota
                </a>
                <a href="{{ route('profile.vision-mission') }}" class="btn btn-lg border-2 border-white/30 text-white hover:bg-white/10 hover:border-white/50">
                    Tentang ASPAPI
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     TENTANG ASPAPI
══════════════════════════════════════════════ --}}
<section class="py-20 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">

            {{-- Text --}}
            <div>
                <p class="section-label">Tentang Kami</p>
                <h2 class="section-title mt-1">Mengenal ASPAPI</h2>
                <div class="section-divider"></div>

                <p class="text-neutral-500 leading-relaxed mb-4">
                    <strong class="text-navy">ASPAPI</strong> — Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia —
                    lahir dari keberanian dan tekad para inisiator yang bervisi membangun wadah bagi para sarjana
                    dan praktisi administrasi perkantoran di seluruh Indonesia.
                </p>
                <p class="text-neutral-500 leading-relaxed mb-4">
                    Gagasan ini bermula pada <strong class="text-navy">27 Februari 2010</strong> di Universitas Sebelas Maret (UNS),
                    melalui serangkaian pertemuan intensif yang melibatkan akademisi dan praktisi dari berbagai
                    perguruan tinggi se-Indonesia. Puncaknya, ASPAPI resmi berdiri melalui Kongres dan Seminar Nasional
                    pada <strong class="text-navy">16–17 Oktober 2010</strong>, dan dikukuhkan melalui Akta Notaris pada
                    <strong class="text-navy">7 Oktober 2010</strong>.
                </p>
                <p class="text-neutral-500 leading-relaxed mb-8">
                    Dengan semangat <em class="text-primary font-medium">Competent, Competitive and Collaborative</em>,
                    ASPAPI terus melangkah maju memajukan standar profesi administrasi perkantoran Indonesia
                    di kancah nasional dan global.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('profile.history') }}" class="btn btn-outline">
                        Sejarah ASPAPI
                    </a>
                    <a href="{{ route('profile.vision-mission') }}" class="btn btn-ghost">
                        Visi &amp; Misi →
                    </a>
                </div>
            </div>

            {{-- Visual: Nilai-nilai organisasi --}}
            <div class="grid grid-cols-1 gap-4">

                @php
                    $values = [
                        [
                            'color'   => 'card-top-blue',
                            'letter'  => 'C',
                            'title'   => 'Competent',
                            'desc'    => 'Mendorong setiap anggota untuk memiliki kompetensi tinggi di bidang administrasi dan manajemen perkantoran.',
                        ],
                        [
                            'color'   => 'card-top-red',
                            'letter'  => 'C',
                            'title'   => 'Competitive',
                            'desc'    => 'Membekali anggota dengan daya saing yang kuat untuk menghadapi tantangan persaingan global.',
                        ],
                        [
                            'color'   => 'card-top-yellow',
                            'letter'  => 'C',
                            'title'   => 'Collaborative',
                            'desc'    => 'Membangun sinergi antar anggota, institusi, dan pemangku kepentingan untuk kemajuan bersama.',
                        ],
                    ];
                @endphp

                @foreach ($values as $val)
                <div class="card {{ $val['color'] }} flex items-start gap-5 p-5">
                    <div class="w-12 h-12 rounded bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <span class="font-display text-xl text-primary font-bold">{{ $val['letter'] }}</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-navy mb-1">{{ $val['title'] }}</h3>
                        <p class="text-xs text-neutral-400 leading-relaxed">{{ $val['desc'] }}</p>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     LAYANAN KAMI
══════════════════════════════════════════════ --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-12">
            <p class="section-label">Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia</p>
            <h2 class="section-title mt-1">Layanan Kami</h2>
            <div class="section-divider mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="card card-top-blue card-hover p-7">
                <div class="w-11 h-11 rounded bg-primary-100 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-navy mb-3">Publikasi Ilmiah</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    ASPAPI membuka kesempatan kepada anggota untuk mempublikasikan hasil penelitian melalui jurnal ilmiah berkala yang diterbitkan oleh ASPAPI. Jurnal ini merupakan wadah untuk memfasilitasi diseminasi penelitian berkualitas tinggi dan mendorong pertukaran gagasan di antar anggota ASPAPI.
                </p>
            </div>

            <div class="card card-top-blue card-hover p-7">
                <div class="w-11 h-11 rounded bg-primary-100 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-navy mb-3">Pelatihan dan Seminar</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    ASPAPI berkomitmen untuk mendukung pengembangan kompetensi anggota dalam bidang administrasi/manajemen perkantoran, melalui pelatihan dan seminar. Kegiatan ini diselenggarakan secara berkala, baik oleh ASPAPI Pusat maupun oleh ASPAPI Daerah.
                </p>
            </div>

            <div class="card card-top-red card-hover p-7">
                <div class="w-11 h-11 rounded bg-red-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-accent-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-navy mb-3">Uji Kompetensi Keahlian (UKK)</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    ASPAPI membuka kesempatan kepada Sekolah Menengah Kejuruan (SMK) untuk bermitra dalam menyelenggarakan UKK Administrasi Perkantoran bagi siswa. Dengan mengadakan UKK secara teratur, ASPAPI berperan aktif dalam mendukung peningkatan standar keahlian siswa SMK serta memastikan kesiapan mereka untuk memasuki dunia kerja.
                </p>
            </div>

            <div class="card card-top-red card-hover p-7">
                <div class="w-11 h-11 rounded bg-red-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-accent-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-navy mb-3">Uji Sertifikasi Kompetensi (USK)</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    ASPAPI menyelenggarakan USK untuk mengukur dan menilai tingkat keahlian individu dalam bidang Administrasi Perkantoran. Pelaksanaan USK dilakukan oleh Lembaga Sertifikasi Profesi (LSP-KAP) yang merupakan bagian integral dari ASPAPI dan telah mendapatkan lisensi dari BNSP.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     BERITA TERBARU
══════════════════════════════════════════════ --}}
@if($latestNews->isNotEmpty())
<section class="py-20 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="section-label">Terkini</p>
                <h2 class="section-title mt-1">Berita Terbaru</h2>
                <div class="section-divider"></div>
            </div>
            <a href="{{ route('news.index') }}"
               class="text-xs font-bold tracking-widest uppercase text-primary border-b-2 border-accent-yellow pb-0.5 hover:text-primary-600 transition-colors hidden sm:block">
                Lihat Semua Berita →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($latestNews as $item)
            <article class="card card-top-blue card-hover flex flex-col">

                {{-- Thumbnail --}}
                @if ($item->thumbnail)
                <div class="h-44 overflow-hidden">
                    <img src="{{ Storage::url($item->thumbnail) }}"
                         alt="{{ $item->title }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"/>
                </div>
                @else
                <div class="h-44 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                    <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                @endif

                <div class="p-5 flex flex-col flex-1">
                    {{-- Category & Date --}}
                    <div class="flex items-center justify-between mb-3">
                        @if($item->category)
                            <span class="badge badge-blue">{{ $item->category }}</span>
                        @else
                            <span class="badge badge-blue">Berita</span>
                        @endif
                        <span class="text-2xs text-neutral-300">
                            {{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-sm font-bold text-navy leading-snug mb-2 flex-1">
                        <a href="{{ route('news.show', $item->slug) }}"
                           class="hover:text-primary transition-colors">
                            {{ $item->title }}
                        </a>
                    </h3>

                    {{-- Excerpt --}}
                    @if($item->excerpt)
                    <p class="text-xs text-neutral-400 leading-relaxed mb-4 line-clamp-2">
                        {{ $item->excerpt }}
                    </p>
                    @endif

                    {{-- Read more --}}
                    <a href="{{ route('news.show', $item->slug) }}"
                       class="text-2xs font-bold tracking-widest uppercase text-primary border-b-2 border-accent-yellow pb-0.5 w-fit hover:text-primary-600 transition-colors mt-auto">
                        Baca Selengkapnya →
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Mobile: lihat semua --}}
        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('news.index') }}" class="btn btn-outline">Lihat Semua Berita</a>
        </div>

    </div>
</section>
@endif

@if($latestBlog->isNotEmpty())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="font-display text-2xl font-bold text-navy mb-8">Blog Terbaru</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestBlog as $item)
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                @if($item->thumbnail)
                <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-44 object-cover">
                @else
                <div class="h-44 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                    <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                @endif

                <div class="p-5 flex flex-col flex-1">
                    {{-- Category & Date --}}
                    <div class="flex items-center justify-between mb-3">
                        @if($item->category)
                            <span class="badge badge-blue">{{ $item->category }}</span>
                        @else
                            <span class="badge badge-blue">Blog</span>
                        @endif
                        <span class="text-2xs text-neutral-300">
                            {{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-sm font-bold text-navy leading-snug mb-2 flex-1">
                        <a href="{{ route('news.show', $item->slug) }}"
                           class="hover:text-primary transition-colors">
                            {{ $item->title }}
                        </a>
                    </h3>

                    {{-- Excerpt --}}
                    @if($item->excerpt)
                    <p class="text-xs text-neutral-400 leading-relaxed mb-4 line-clamp-2">
                        {{ $item->excerpt }}
                    </p>
                    @endif

                    {{-- Read more --}}
                    <a href="{{ route('news.show', $item->slug) }}"
                       class="text-2xs font-bold tracking-widest uppercase text-primary border-b-2 border-accent-yellow pb-0.5 w-fit hover:text-primary-600 transition-colors mt-auto">
                        Baca Selengkapnya →
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ══════════════════════════════════════════════
     CTA BANNER — DAFTAR ANGGOTA
══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-navy py-16">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-yellow to-accent-red"></div>
    <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-white/5 pointer-events-none"
         style="clip-path: polygon(20% 0%, 100% 0%, 100% 100%, 0% 100%)"></div>

    <div class="max-w-7xl mx-auto px-6 relative">
        <div class="max-w-2xl">
            <p class="section-label" style="color: #E8B84B;">Bergabung Sekarang</p>
            <h2 class="font-display text-white text-3xl leading-tight mt-1 mb-3">
                Jadilah Bagian dari<br/>Komunitas Profesional ASPAPI
            </h2>
            <p class="text-primary-200 text-sm leading-relaxed mb-8">
                Daftarkan diri Anda sebagai anggota ASPAPI dan nikmati berbagai manfaat
                keanggotaan — sertifikasi profesi, jaringan luas, dan pengembangan karir.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('members.register') }}" class="btn btn-accent-yellow btn-lg">
                    Daftar Sebagai Anggota
                </a>
                <a href="{{ route('members.types') }}" class="btn btn-lg border-2 border-white/30 text-white hover:bg-white/10">
                    Jenis &amp; Syarat Anggota
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
