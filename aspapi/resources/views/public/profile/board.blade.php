@extends('layouts.app')
@php $title = 'Pengurus'; @endphp
@section('content')

<div style="background:linear-gradient(135deg,#111E2A,#1A5F9A,#2A7FC1);position:relative;padding:3rem 1.5rem 2.5rem;overflow:hidden;">
    <div style="position:absolute;inset-x:0;top:0;height:4px;background:linear-gradient(90deg,#C0392B,#E8B84B);"></div>
    <div class="max-w-7xl mx-auto">
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#A8D4F5;margin-bottom:1rem;">
            <a href="{{ route('home') }}" style="color:#A8D4F5;">Beranda</a><span>›</span>
            <span style="color:#fff;font-weight:600;">Pengurus</span>
        </nav>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#E8B84B;margin-bottom:0.5rem;">Profil Organisasi</p>
        <h1 style="font-family:'DM Serif Display',serif;color:#fff;font-size:clamp(1.75rem,4vw,2.75rem);line-height:1.2;">Pengurus Pusat ASPAPI</h1>
        <p style="color:#A8D4F5;font-size:0.875rem;margin-top:0.75rem;max-width:600px;line-height:1.7;">Periode 2022–2026</p>
    </div>
</div>

<section class="py-14 bg-white">
    <div class="max-w-5xl mx-auto px-6">
        <p class="section-label">Tentang Pengurus Pusat</p>
        <h2 class="section-title mt-1">Pengurus Pusat ASPAPI</h2>
        <div class="section-divider"></div>
        <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;">
            ASPAPI Pusat adalah organisasi ASPAPI yang dipimpin dan dikelola oleh Pengurus Pusat. Pengurus Pusat adalah personalia yang mengelola ASPAPI pada tingkat nasional. Pengurus Pusat Periode 2022–2026 terdiri atas: Ketua Umum, Ketua I, Ketua II, Ketua III, Sekretaris Jenderal, Sekretaris I, Sekretaris II, Sekretaris III, Bendahara Umum, Bendahara I, Bendahara II, Bendahara III, dan Departemen.
        </p>
    </div>
</section>

@php
$groups = [
    [
        'title' => 'Ketua',
        'color' => '#2A7FC1',
        'bg'    => '#EEF4FB',
        'members' => [
            ['name' => 'Dr. Rasto, M.Pd.',                                          'inst' => 'Universitas Pendidikan Indonesia',          'role' => 'Ketua Umum'],
            ['name' => 'Dra. Armida Silvia, M.Si., QPOA., CESP.',                   'inst' => 'Universitas Negeri Padang',                  'role' => 'Ketua I'],
            ['name' => 'Dr. Patni Ninghardjanti, M.Pd.',                            'inst' => 'Universitas Sebelas Maret',                  'role' => 'Ketua II'],
            ['name' => 'Drs. H.M. Jamil Latief, M.M., M.Pd., QPOA., CESP., CAP., COSP', 'inst' => 'Universitas Muhammadiyah Prof. Dr. Hamka', 'role' => 'Ketua III'],
        ],
    ],
    [
        'title' => 'Sekretaris',
        'color' => '#C0392B',
        'bg'    => '#FDECEA',
        'members' => [
            ['name' => 'Muh. Darwis, M.Pd., QPOA. CESP.',       'inst' => 'Universitas Negeri Makassar',    'role' => 'Sekretaris Jenderal'],
            ['name' => 'Ahmad Saeroji, S.Pd., M.Pd.',            'inst' => 'Universitas Negeri Semarang',    'role' => 'Sekretaris I'],
            ['name' => 'Marsofiyati, M.Pd., QPOA., CESP.',       'inst' => 'Universitas Negeri Jakarta',     'role' => 'Sekretaris II'],
            ['name' => 'Dra. Imasita, M.Si., QPOA, CESP, CAP, COSP', 'inst' => 'Politeknik Negeri Ujung Pandang', 'role' => 'Sekretaris III'],
        ],
    ],
    [
        'title' => 'Bendahara',
        'color' => '#B8860B',
        'bg'    => '#FEF8EC',
        'members' => [
            ['name' => 'Dewi Nurmalasari, M.M.',                                      'inst' => 'Universitas Negeri Jakarta',       'role' => 'Bendahara Umum'],
            ['name' => 'Prof. Dr. Cicilia Dyah Sulistyaningrum Indrawati, M.Pd.',     'inst' => 'Universitas Sebelas Maret',        'role' => 'Bendahara I'],
            ['name' => 'Dr. Siti Umi Khayatun Mardiyah, M.Pd.',                      'inst' => 'Universitas Negeri Yogyakarta',    'role' => 'Bendahara II'],
            ['name' => 'Sitti Hardiyanti Arhas, M.Pd., QPOA., CAP., COSP.',          'inst' => 'Universitas Negeri Makassar',      'role' => 'Bendahara III'],
        ],
    ],
    [
        'title' => 'Departemen Pengembangan Organisasi',
        'color' => '#2A7FC1',
        'bg'    => '#EEF4FB',
        'members' => [
            ['name' => 'Jamaluddin, S.Pd., M.Si. CRA., CRP.',  'inst' => 'Universitas Negeri Makassar',         'role' => 'Kepala Departemen'],
            ['name' => 'Muslikhah Dwihartanti, M.Pd.',         'inst' => 'Universitas Negeri Yogyakarta',        'role' => 'Anggota'],
            ['name' => 'Enjang Suhaedin, S.Pd., Gr., M.M.',    'inst' => 'SMK Negeri 7 Batam',                  'role' => 'Anggota'],
            ['name' => 'Mulyadi Yusuf, S.A.P., M.Si., QPOA.', 'inst' => 'Politeknik Negeri Ujung Pandang',      'role' => 'Anggota'],
            ['name' => 'Sufriadi, S.Pd., QPOA., CAP.',         'inst' => 'SMK Negeri 1 Sinjai Sulawesi Selatan','role' => 'Anggota'],
        ],
    ],
    [
        'title' => 'Departemen Penelitian dan Publikasi Ilmiah',
        'color' => '#C0392B',
        'bg'    => '#FDECEA',
        'members' => [
            ['name' => 'Arwan Nur Ramadhan, M.Pd.',             'inst' => 'Universitas Negeri Yogyakarta',    'role' => 'Kepala Departemen'],
            ['name' => 'Dr. Rino, S.Pd., M.Pd., M.M.',          'inst' => 'Universitas Negeri Padang',        'role' => 'Anggota'],
            ['name' => 'Drs. Hirman, M.Si., QPOA., CESP., CAP.','inst' => 'Politeknik Negeri Ujung Pandang',  'role' => 'Anggota'],
            ['name' => 'Drs. Sarimin',                           'inst' => 'SMK Negeri 1 Tanjung Pinang',     'role' => 'Anggota'],
            ['name' => 'Agung Kuswantoro, S.Pd., M.Pd.',         'inst' => 'Universitas Negeri Semarang',     'role' => 'Anggota'],
        ],
    ],
    [
        'title' => 'Departemen Kerjasama',
        'color' => '#2A7FC1',
        'bg'    => '#EEF4FB',
        'members' => [
            ['name' => 'Drs. Iwan Giwangkara, M.M.',            'inst' => 'Praktisi DKI Jakarta',            'role' => 'Kepala Departemen'],
            ['name' => 'Prof. Dr. Budi Santoso, M.Si.',          'inst' => 'Universitas Pendidikan Indonesia','role' => 'Anggota'],
            ['name' => 'Sri Arita, S.Pd., M.Pd. E.',             'inst' => 'Universitas Negeri Padang',       'role' => 'Anggota'],
            ['name' => 'Durinda Puspasari, M.Pd.',               'inst' => 'Universitas Negeri Surabaya',     'role' => 'Anggota'],
            ['name' => 'Dr. Nasaruddin H., S.Pd., S.AN., M.Pd.','inst' => 'Universitas Negeri Makassar',     'role' => 'Anggota'],
        ],
    ],
    [
        'title' => 'Departemen Hukum dan Advokasi',
        'color' => '#C0392B',
        'bg'    => '#FDECEA',
        'members' => [
            ['name' => 'Prof. Dr. H. Andi Sukri Syamsuri, M. Hum., QPOA.', 'inst' => 'Universitas Muhammadiyah Makassar',        'role' => 'Kepala Departemen'],
            ['name' => 'Dr. Hj. Ehsana El Khuluqo, M.Pd.',                 'inst' => 'Universitas Muhammadiyah Prof. Dr. Hamka', 'role' => 'Anggota'],
            ['name' => 'Durinta Puspasari, M.Pd.',                          'inst' => 'Universitas Negeri Surabaya',              'role' => 'Anggota'],
            ['name' => 'Dra. Sri Mutmainnah, M.Si.',                        'inst' => 'Universitas Negeri Medan',                 'role' => 'Anggota'],
            ['name' => 'Rian Candra Dinata, S.Pd.',                         'inst' => 'SMK Negeri 2 Pagar Alam Sumsel',           'role' => 'Anggota'],
        ],
    ],
    [
        'title' => 'Departemen Pendidikan dan Pelatihan',
        'color' => '#2A7FC1',
        'bg'    => '#EEF4FB',
        'members' => [
            ['name' => 'Dr. Agus Hermawan, GradDipMgt., M.Si., M.Bus.', 'inst' => 'Universitas Negeri Malang',  'role' => 'Anggota'],
            ['name' => 'Dr. Yuhendry Leo Vrista, M.Pd.',                 'inst' => 'Universitas Negeri Padang',  'role' => 'Anggota'],
            ['name' => 'Darma Rika Swaramarinda, S.Pd., M.SE.',          'inst' => 'Universitas Negeri Jakarta', 'role' => 'Anggota'],
            ['name' => 'Dra. Lina Marlina, M.Pd.',                       'inst' => 'Praktisi Jawa Barat',        'role' => 'Anggota'],
            ['name' => 'H. Muhammad Rusdi, SE., M.Si., QPOA., CESP.',    'inst' => 'Universitas Muhammadiyah Makassar', 'role' => 'Anggota'],
        ],
    ],
    [
        'title' => 'Departemen Humas dan Sistem Informasi',
        'color' => '#C0392B',
        'bg'    => '#FDECEA',
        'members' => [
            ['name' => 'Dr. Drs. Edy Ramon Torong, SH., MM.',  'inst' => 'Praktisi DKI Jakarta',             'role' => 'Anggota'],
            ['name' => 'Yogi Kardillah, SKM., M. Kes.',         'inst' => 'SMK Muhammadiyah Pagar Alam Sumsel','role' => 'Anggota'],
            ['name' => 'Andi Gunawan, S.Si., M. Kom., QPOA.',   'inst' => 'Politeknik Negeri Ujung Pandang',  'role' => 'Anggota'],
            ['name' => 'Drs. Mohammad Arif, M.Pd.',             'inst' => 'Universitas Negeri Malang',        'role' => 'Anggota'],
            ['name' => 'Deni Darmawan, SE., M.Si.',             'inst' => 'Universitas Tanjungpura Kalbar',   'role' => 'Anggota'],
        ],
    ],
    [
        'title' => 'Departemen Sertifikasi',
        'color' => '#2A7FC1',
        'bg'    => '#EEF4FB',
        'members' => [
            ['name' => 'Dr. Sambas Ali Muhidin, M.Si., QPOA.', 'inst' => 'Universitas Pendidikan Indonesia', 'role' => 'Anggota'],
            ['name' => 'Dra. Dewi Setiati',                     'inst' => 'Praktisi Jawa Barat',             'role' => 'Anggota'],
            ['name' => 'Ishak Suhada, S.T., M.Si.',             'inst' => 'Universitas Tanjungpura Kalbar',  'role' => 'Anggota'],
            ['name' => 'Ani Ismarini, S.Pd., Gr., QPOA',        'inst' => 'SMK Negeri 2 Pagar Alam Sumsel',  'role' => 'Anggota'],
            ['name' => 'Wahyu Rusdiyanto, M.M.',                'inst' => 'Universitas Negeri Yogyakarta',   'role' => 'Anggota'],
        ],
    ],
];
@endphp

<section class="py-14" style="background:#F8FAFC;">
    <div class="max-w-5xl mx-auto px-6 space-y-12">
        @foreach ($groups as $group)
        <div>
            {{-- Group Header --}}
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
                <div style="width:4px;height:32px;background:{{ $group['color'] }};border-radius:2px;flex-shrink:0;"></div>
                <h2 style="font-family:'DM Serif Display',serif;font-size:1.4rem;color:#1A2A3A;">{{ $group['title'] }}</h2>
            </div>

            {{-- Members Grid --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;">
                @foreach ($group['members'] as $member)
                @php
                    $slug = \Illuminate\Support\Str::slug($member['name']);
                    $hasPhoto = file_exists(public_path('images/pengurus/' . $slug . '.png'))
                             || file_exists(public_path('images/pengurus/' . $slug . '.jpg'));
                    $photoExt = file_exists(public_path('images/pengurus/' . $slug . '.png')) ? 'png' : 'jpg';
                    $initial  = strtoupper(substr(preg_replace('/^(Prof\.|Dr\.|Drs\.|Dra\.)\s*/i', '', $member['name']), 0, 1));
                    $isHead   = str_contains($member['role'], 'Ketua') || str_contains($member['role'], 'Sekretaris Jenderal') || str_contains($member['role'], 'Bendahara Umum') || str_contains($member['role'], 'Kepala');
                @endphp
                <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;border-top:3px solid {{ $group['color'] }};">

                    {{-- Foto --}}
                    <div style="width:100%;height:180px;background:{{ $group['bg'] }};overflow:hidden;display:flex;align-items:center;justify-content:center;">
                        @if ($hasPhoto)
                            <img src="{{ asset('images/pengurus/' . $slug . '.' . $photoExt) }}"
                                 alt="{{ $member['name'] }}"
                                 style="width:100%;height:100%;object-fit:cover;object-position:top;"/>
                        @else
                            <div style="display:flex;flex-direction:column;align-items:center;gap:0.5rem;">
                                <div style="width:70px;height:70px;border-radius:50%;background:{{ $group['color'] }};display:flex;align-items:center;justify-content:center;">
                                    <span style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#fff;">{{ $initial }}</span>
                                </div>
                                <span style="font-size:0.6rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:{{ $group['color'] }};opacity:0.5;">Foto belum tersedia</span>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div style="padding:0.875rem 1rem 1rem;">
                        <span style="display:inline-block;font-size:0.62rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:0.2rem 0.5rem;border-radius:2px;background:{{ $group['bg'] }};color:{{ $group['color'] }};margin-bottom:0.5rem;">
                            {{ $member['role'] }}
                        </span>
                        <p style="font-size:0.85rem;font-weight:700;color:#1A2A3A;line-height:1.4;">{{ $member['name'] }}</p>
                        <p style="font-size:0.775rem;color:#4A6580;margin-top:0.3rem;display:flex;align-items:flex-start;gap:0.3rem;">
                            <svg style="width:12px;height:12px;flex-shrink:0;margin-top:2px;" fill="none" stroke="#B0CCDF" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $member['inst'] }}
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