@extends('layouts.app')
@php $title = 'Dewan Pakar'; @endphp
@section('content')

<div style="background:linear-gradient(135deg,#111E2A,#1A5F9A,#2A7FC1);position:relative;padding:3rem 1.5rem 2.5rem;overflow:hidden;">
    <div style="position:absolute;inset-x:0;top:0;height:4px;background:linear-gradient(90deg,#C0392B,#E8B84B);"></div>
    <div class="max-w-7xl mx-auto">
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#A8D4F5;margin-bottom:1rem;">
            <a href="{{ route('home') }}" style="color:#A8D4F5;">Beranda</a><span>›</span>
            <span style="color:#fff;font-weight:600;">Dewan Pakar</span>
        </nav>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#E8B84B;margin-bottom:0.5rem;">Profil Organisasi</p>
        <h1 style="font-family:'DM Serif Display',serif;color:#fff;font-size:clamp(1.75rem,4vw,2.75rem);line-height:1.2;">Dewan Pakar ASPAPI</h1>
        <p style="color:#A8D4F5;font-size:0.875rem;margin-top:0.75rem;max-width:600px;line-height:1.7;">Periode 2022–2026</p>
    </div>
</div>

<section class="py-14 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <p class="section-label">Tentang Dewan Pakar</p>
        <h2 class="section-title mt-1">Peran dan Fungsi</h2>
        <div class="section-divider"></div>
        <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;margin-bottom:1rem;">
            Dewan Pakar ASPAPI adalah himpunan guru besar bidang administrasi/manajemen perkantoran dan ex officio ketua program studi administrasi/manajemen perkantoran dari perguruan tinggi atau sebutan lainnya, yang berfungsi memberikan pertimbangan kepada Pengurus ASPAPI.
        </p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;">
            @foreach ([
                ['label' => 'Fungsi', 'text' => 'Memberikan pertimbangan kepada Pengurus Pusat dalam merumuskan kebijakan-kebijakan ASPAPI, berdasarkan kajian ilmu administrasi/manajemen perkantoran.'],
                ['label' => 'Tugas',  'text' => 'Memastikan pelaksanaan program kerja ASPAPI sesuai bidang kajian ilmu administrasi/manajemen perkantoran.'],
            ] as $item)
            <div style="background:#EEF4FB;border-left:3px solid #2A7FC1;border-radius:0 4px 4px 0;padding:1rem 1.125rem;">
                <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#2A7FC1;margin-bottom:0.375rem;">{{ $item['label'] }}</p>
                <p style="font-size:0.825rem;color:#4A6580;line-height:1.7;">{{ $item['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@php
$sections = [
    [
        'title'   => 'Pimpinan Dewan Pakar',
        'color'   => '#2A7FC1',
        'bg'      => '#EEF4FB',
        'members' => [
            ['name' => 'Prof. Dr. H. Suwatno, M.Si.',  'inst' => 'Universitas Pendidikan Indonesia', 'role' => 'Ketua Dewan Pakar Periode 2022–2026'],
            ['name' => 'Dhidik Apriyanto, SE., M.Si.',  'inst' => 'Universitas Tanjungpura',          'role' => 'Sekretaris Dewan Pakar Periode 2022–2026'],
        ],
    ],
    [
        'title'   => 'Anggota Dewan Pakar — Unsur Guru Besar',
        'color'   => '#C0392B',
        'bg'      => '#FDECEA',
        'members' => [
            ['name' => 'Prof. Muhyadi',                              'inst' => 'Universitas Negeri Yogyakarta',    'role' => 'Anggota'],
            ['name' => 'Prof. Dr. S. Martono, M.Si.',                'inst' => 'Universitas Negeri Semarang',     'role' => 'Anggota'],
            ['name' => 'Prof. Dr. Bambang Suratman, M.Pd.',          'inst' => 'Universitas Negeri Surabaya',     'role' => 'Anggota'],
            ['name' => 'Prof. Dr. Budi Eko Soetjipto, M.Ed., M.Si.', 'inst' => 'Universitas Negeri Malang',      'role' => 'Anggota'],
            ['name' => 'Prof. Dr. Tjutju Yuniarsih, SE., M.Pd.',     'inst' => 'Universitas Pendidikan Indonesia','role' => 'Anggota'],
            ['name' => 'Prof. Dr. Wiedy Murtini, M.Pd.',             'inst' => 'Universitas Sebelas Maret',      'role' => 'Anggota'],
            ['name' => 'Prof. Dr. H. A. Sobandi, M.Si., M.Pd.',      'inst' => 'Universitas Pendidikan Indonesia','role' => 'Anggota'],
            ['name' => 'Prof. Dr. Haedar Akib, M.Si.',               'inst' => 'Universitas Negeri Makassar',    'role' => 'Anggota'],
            ['name' => 'Prof. Dr. H. Edi Suryadi, M.Si.',            'inst' => 'Universitas Pendidikan Indonesia','role' => 'Anggota'],
            ['name' => 'Prof. Dr. Dra. Hj. Janah Sojanah, M.Si.',    'inst' => 'Universitas Pendidikan Indonesia','role' => 'Anggota'],
            ['name' => 'Prof. Dr. Hj. Nani Sutarni, M.Pd.',          'inst' => 'Universitas Pendidikan Indonesia','role' => 'Anggota'],
            ['name' => 'Prof. Dr. Henry Eryanto, M.M.',              'inst' => 'Universitas Negeri Jakarta',     'role' => 'Anggota'],
            ['name' => 'Prof. Dr. Budi Santoso, M.Si.',              'inst' => 'Universitas Pendidikan Indonesia','role' => 'Anggota'],
            ['name' => 'Prof. Dr. Dedi Purwana, S.E., M.Bus.',       'inst' => 'Universitas Negeri Jakarta',     'role' => 'Anggota'],
            ['name' => 'Prof. Dr. Drs. Saliman, M.Pd.',              'inst' => 'Universitas Negeri Yogyakarta',  'role' => 'Anggota'],
            ['name' => 'Prof. Dr. Cicilia Dyah S. I., M.Pd.',        'inst' => 'Universitas Sebelas Maret',     'role' => 'Anggota'],
            ['name' => 'Prof. Dr. Endang Supardi, M.Si.',            'inst' => 'Universitas Pendidikan Indonesia','role' => 'Anggota'],
        ],
    ],
    [
        'title'   => 'Anggota Dewan Pakar — Unsur Ketua Prodi',
        'color'   => '#2A7FC1',
        'bg'      => '#EEF4FB',
        'members' => [
            ['name' => 'Dr. Rosidah, M.Si.',                        'inst' => 'Universitas Negeri Yogyakarta',   'role' => 'Anggota'],
            ['name' => 'Ahmad Nurkhin, S.Pd., M.Si.',               'inst' => 'Universitas Negeri Semarang',    'role' => 'Anggota'],
            ['name' => 'Dr. Heri Sawiji, M.Pd.',                    'inst' => 'Universitas Sebelas Maret',      'role' => 'Anggota'],
            ['name' => 'Roni Faslah, S.Pd., M.M.',                  'inst' => 'Universitas Negeri Jakarta',     'role' => 'Anggota'],
            ['name' => 'Dr. Christian Wiradendi Wohor',              'inst' => 'Universitas Negeri Jakarta',     'role' => 'Anggota'],
            ['name' => 'Triesninda Pahlevi, S.Pd., M.Pd.',          'inst' => 'Universitas Negeri Surabaya',    'role' => 'Anggota'],
            ['name' => 'Dr. Madziatul Churiyah, S.Pd., M.M.',       'inst' => 'Universitas Negeri Malang',      'role' => 'Anggota'],
            ['name' => 'Dr. Sirajuddin Saleh, S.Pd., M.Pd.',        'inst' => 'Universitas Negeri Makassar',    'role' => 'Anggota'],
            ['name' => 'Dr. Armiati, S.Pd., M.Pd.',                 'inst' => 'Universitas Negeri Padang',      'role' => 'Anggota'],
            ['name' => 'Nelly Armayanti, SP., MP.',                  'inst' => 'Universitas Negeri Medan',       'role' => 'Anggota'],
            ['name' => 'Dr. Hady Siti Hadijah, S.Pd., M.Si.',       'inst' => 'Universitas Pendidikan Indonesia','role' => 'Anggota'],
            ['name' => 'Dra. Asima, M.Si.',                         'inst' => 'Politeknik Negeri Ujung Pandang', 'role' => 'Anggota'],
            ['name' => 'Dr. H. Onny Fitriana Sitorus, M.Pd.',       'inst' => 'UHAMKA Jakarta',                 'role' => 'Anggota'],
        ],
    ],
];
@endphp

<section class="py-14" style="background:#F8FAFC;">
    <div class="max-w-5xl mx-auto px-6 space-y-12">
        @foreach ($sections as $section)
        <div>
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
                <div style="width:4px;height:32px;background:{{ $section['color'] }};border-radius:2px;flex-shrink:0;"></div>
                <h2 style="font-family:'DM Serif Display',serif;font-size:1.4rem;color:#1A2A3A;">{{ $section['title'] }}</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
                @foreach ($section['members'] as $person)
                @php
                    $slug = \Illuminate\Support\Str::slug($person['name']);
                    $hasPhoto = file_exists(public_path('images/dewan-pakar/' . $slug . '.png'));
                    $initial  = strtoupper(substr(preg_replace('/^(Prof\.|Dr\.|Drs\.|Dra\.)\s*/i', '', $person['name']), 0, 1));
                @endphp
                <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;border-top:3px solid {{ $section['color'] }};">
                    <div style="width:100%;height:180px;background:{{ $section['bg'] }};overflow:hidden;display:flex;align-items:center;justify-content:center;">
                        @if ($hasPhoto)
                            <img src="{{ asset('images/dewan-pakar/' . $slug . '.png') }}"
                                 alt="{{ $person['name'] }}"
                                 style="width:100%;height:100%;object-fit:cover;object-position:top;"/>
                        @else
                            <div style="display:flex;flex-direction:column;align-items:center;gap:0.5rem;">
                                <div style="width:70px;height:70px;border-radius:50%;background:{{ $section['color'] }};display:flex;align-items:center;justify-content:center;">
                                    <span style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#fff;">{{ $initial }}</span>
                                </div>
                                <span style="font-size:0.6rem;color:#B0CCDF;letter-spacing:0.08em;text-transform:uppercase;">Foto belum tersedia</span>
                            </div>
                        @endif
                    </div>
                    <div style="padding:0.875rem 1rem 1rem;">
                        <span style="display:inline-block;font-size:0.62rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:0.2rem 0.5rem;border-radius:2px;background:{{ $section['bg'] }};color:{{ $section['color'] }};margin-bottom:0.5rem;">
                            {{ $person['role'] }}
                        </span>
                        <p style="font-size:0.85rem;font-weight:700;color:#1A2A3A;line-height:1.4;">{{ $person['name'] }}</p>
                        <p style="font-size:0.775rem;color:#4A6580;margin-top:0.3rem;display:flex;align-items:flex-start;gap:0.3rem;">
                            <svg style="width:12px;height:12px;flex-shrink:0;margin-top:2px;" fill="none" stroke="#B0CCDF" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $person['inst'] }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>

@endsection