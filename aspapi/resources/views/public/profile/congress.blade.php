@extends('layouts.app')

@php $title = 'Kongres'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div style="background: linear-gradient(135deg, #111E2A, #1A5F9A, #2A7FC1); position: relative; padding-top: 3rem; padding-bottom: 2.5rem; padding-left: 1.5rem; padding-right: 1.5rem; overflow: hidden;">
    <div style="position:absolute;inset-x:0;top:0;height:4px;background:linear-gradient(90deg,#C0392B,#E8B84B);"></div>
    <div class="max-w-7xl mx-auto">
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#A8D4F5;margin-bottom:1rem;">
            <a href="{{ route('home') }}" style="color:#A8D4F5;">Beranda</a>
            <span>›</span>
            <span style="color:#fff;font-weight:600;">Kongres</span>
        </nav>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#E8B84B;margin-bottom:0.5rem;">Profil Organisasi</p>
        <h1 style="font-family:'DM Serif Display',serif;color:#ffffff;font-size:clamp(1.75rem,4vw,2.75rem);line-height:1.2;">
            Kongres ASPAPI
        </h1>
        <p style="color:#A8D4F5;font-size:0.875rem;margin-top:0.75rem;max-width:600px;line-height:1.7;">
            Musyawarah tertinggi ASPAPI di tingkat nasional yang diselenggarakan setiap empat tahun sekali.
        </p>
    </div>
</div>

{{-- ── PENJELASAN KONGRES ── --}}
<section class="py-14 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <p class="section-label">Tentang Kongres</p>
        <h2 class="section-title mt-1">Apa itu Kongres ASPAPI?</h2>
        <div class="section-divider"></div>
        <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;margin-bottom:1.5rem;">
            Kongres adalah musyawarah tertinggi di tingkat nasional, yang diselenggarakan Pengurus Pusat ASPAPI. Kongres dilaksanakan setiap 4 tahun sekali. Sampai saat ini ASPAPI telah menyelenggarakan 4 kali kongres.
        </p>
        <div style="background:#EEF4FB;border-radius:6px;padding:1.75rem;">
            <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#2A7FC1;margin-bottom:1rem;">Kewenangan Kongres</p>
            <div class="space-y-3">
                @foreach ([
                    'Menerima atau menolak laporan pertanggung jawaban Pengurus Pusat',
                    'Mengubah dan menetapkan Anggaran Dasar',
                    'Mengubah dan menetapkan Anggaran Rumah Tangga',
                    'Menetapkan Garis-garis Besar Program Kerja',
                    'Memilih dan mengangkat Pengurus Pusat',
                ] as $i => $item)
                <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                    <div style="width:24px;height:24px;border-radius:50%;background:#2A7FC1;color:#fff;font-size:0.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ $i + 1 }}</div>
                    <p style="font-size:0.875rem;color:#4A6580;line-height:1.7;">{{ $item }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── KONGRES LIST ── --}}
<section class="py-14" style="background:#F8FAFC;">
    <div class="max-w-4xl mx-auto px-6">

        <div class="text-center mb-12">
            <p class="section-label">Riwayat Pelaksanaan</p>
            <h2 class="section-title mt-1">Kongres I — IV ASPAPI</h2>
            <div class="section-divider mx-auto"></div>
        </div>

        <div class="space-y-8">

            {{-- KONGRES I --}}
            <div class="card" style="border-top:4px solid #2A7FC1;">
                <div class="p-6 md:p-8">
                    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.75rem;margin-bottom:1.25rem;">
                        <div style="background:#2A7FC1;color:#fff;font-family:'DM Serif Display',serif;font-size:1.25rem;font-weight:700;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">I</div>
                        <div>
                            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#C0392B;">Kongres Pertama</p>
                            <h3 style="font-size:1.1rem;font-weight:700;color:#1A2A3A;line-height:1.3;">Kongres ke-I ASPAPI</h3>
                        </div>
                        <span class="badge" style="background:#EEF4FB;color:#2A7FC1;margin-left:auto;">16–17 Oktober 2010</span>
                    </div>
                    <p style="font-size:0.8rem;color:#B0CCDF;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.375rem;">
                        <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Gedung Balai Kota & Graha Solo Raya, Surakarta
                    </p>
                    <div style="font-size:0.875rem;color:#4A6580;line-height:1.9;" class="space-y-3">
                        <p>Kongres ke-I ASPAPI dilaksanakan pada tanggal 16–17 Oktober 2010 di Gedung Balai Kota dan Gedung Graha Solo Raya Surakarta. Kongres ini sekaligus merupakan acara <strong style="color:#1A2A3A;">Launching berdirinya Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia (ASPAPI)</strong>, dilanjutkan dengan Seminar Nasional Administrasi Perkantoran. Acara ini diikuti oleh utusan dari Perguruan Tinggi dan Sekolah penyelenggara Pendidikan Administrasi Perkantoran di seluruh Indonesia, serta praktisi Administrasi Perkantoran dari berbagai instansi pemerintah dan swasta.</p>
                        <p>Sesuai dengan tema kongres: <em>"Revitalisasi Pendidikan Administrasi Perkantoran dalam Merespon Kompetisi dan Kompetensi Global"</em>, launching ASPAPI ini diharapkan akan dapat mengakomodasi dan memfasilitasi berbagai gagasan dan pemikiran untuk tercapainya visi tersebut.</p>
                        <p>Acara Kongres hari Sabtu 16 Oktober 2010 diawali dengan Pembukaan berisi Laporan Ketua Panitia, Sambutan Rektor UNS, dilanjutkan pembukaan secara resmi. Untuk persiapan sidang komisi dan sidang pleno, dilakukan pembagian menjadi 4 komisi:</p>
                    </div>

                    {{-- Komisi --}}
                    <div class="space-y-2 my-4">
                        @foreach ([
                            'Komisi Penyusunan tata cara/pedoman jaringan kerjasama antara ASPAPI dengan lembaga lain',
                            'Komisi Penyusunan pola/model pengembangan Pendidikan Administrasi Perkantoran',
                            'Komisi Penyusunan rambu-rambu untuk uji kompetensi Administrasi Perkantoran',
                            'Komisi Pembahasan konsep AD/ART ASPAPI, Logo ASPAPI, Bendera ASPAPI, dan Mars ASPAPI',
                        ] as $i => $k)
                        <div style="display:flex;align-items:flex-start;gap:0.75rem;background:#EEF4FB;padding:0.625rem 0.875rem;border-radius:4px;">
                            <div style="width:20px;height:20px;border-radius:50%;background:#2A7FC1;color:#fff;font-size:0.65rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ $i + 1 }}</div>
                            <p style="font-size:0.8rem;color:#4A6580;line-height:1.6;">{{ $k }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div style="font-size:0.875rem;color:#4A6580;line-height:1.9;" class="space-y-3">
                        <p>Acara dilanjutkan dengan Launching ASPAPI dan jumpa pers. Sidang komisi dan sidang pleno dilaksanakan pada malam hari 16 Oktober 2010.</p>
                        <p>Seminar pada hari Minggu 17 Oktober 2010 menghadirkan pembicara:</p>
                    </div>

                    {{-- Narasumber Kongres I --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 my-4">
                        @foreach ([
                            ['Prof. Djemari Mardapi, Ph.D.', 'Ketua Badan Standar Nasional Pendidikan'],
                            ['Prof. Dr. Muhyadi', 'Universitas Negeri Yogyakarta (UNY)'],
                            ['Prof. Dr. Furqon Hidayatullah', 'Dekan FKIP Universitas Sebelas Maret'],
                            ['Edy Ramon Torong', 'Alumni dan Praktisi Administrasi Perkantoran'],
                        ] as $ns)
                        <div style="display:flex;align-items:flex-start;gap:0.625rem;background:#EEF4FB;padding:0.625rem 0.875rem;border-radius:4px;">
                            <div style="width:7px;height:7px;border-radius:50%;background:#2A7FC1;margin-top:5px;flex-shrink:0;"></div>
                            <div>
                                <p style="font-size:0.8rem;font-weight:700;color:#1A2A3A;">{{ $ns[0] }}</p>
                                <p style="font-size:0.75rem;color:#4A6580;">{{ $ns[1] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;">Seminar dihadiri lebih dari <strong style="color:#1A2A3A;">200 peserta</strong> dari seluruh Indonesia.</p>

                    <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid #EEF4FB;">
                        <div style="background:#EEF4FB;border-radius:4px;padding:0.75rem 1rem;display:inline-block;">
                            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Ketua Umum Terpilih</p>
                            <p style="font-size:0.9rem;font-weight:700;color:#1A2A3A;margin-top:0.125rem;">Prof. Dr. Muhyadi, M.Pd.</p>
                            <p style="font-size:0.75rem;color:#4A6580;">Universitas Negeri Yogyakarta · Periode 2010–2014</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONGRES II --}}
            <div class="card" style="border-top:4px solid #C0392B;">
                <div class="p-6 md:p-8">
                    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.75rem;margin-bottom:1.25rem;">
                        <div style="background:#C0392B;color:#fff;font-family:'DM Serif Display',serif;font-size:1.25rem;font-weight:700;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">II</div>
                        <div>
                            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#C0392B;">Kongres Kedua</p>
                            <h3 style="font-size:1.1rem;font-weight:700;color:#1A2A3A;line-height:1.3;">Kongres ke-II ASPAPI</h3>
                        </div>
                        <span class="badge" style="background:#FDECEA;color:#C0392B;margin-left:auto;">11–12 Oktober 2014</span>
                    </div>
                    <p style="font-size:0.8rem;color:#B0CCDF;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.375rem;">
                        <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pusdiklat Universitas Sebelas Maret, Surakarta
                    </p>
                    <div style="font-size:0.875rem;color:#4A6580;line-height:1.9;" class="space-y-3">
                        <p>Kongres ke-II ASPAPI dilaksanakan tanggal 11–12 Oktober 2014 di Pusdiklat Universitas Sebelas Maret Surakarta. Selain kongres, dilaksanakan juga Koordinasi Kurikulum Administrasi Perkantoran dan Seminar Nasional dengan tema: <em>"Pengembangan Pembelajaran Administrasi Perkantoran Berbasis ICT (Information and Communication Technology)"</em>.</p>
                        <p>Seminar menghadirkan dua narasumber:</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 my-4">
                        @foreach ([
                            ['Dr. Rasto, M.Pd.', 'Universitas Pendidikan Indonesia'],
                            ['Agus Tri Haryanto, S.Com., M.Cs.', 'Universitas Sebelas Maret'],
                        ] as $ns)
                        <div style="display:flex;align-items:flex-start;gap:0.625rem;background:#FDECEA;padding:0.625rem 0.875rem;border-radius:4px;">
                            <div style="width:7px;height:7px;border-radius:50%;background:#C0392B;margin-top:5px;flex-shrink:0;"></div>
                            <div>
                                <p style="font-size:0.8rem;font-weight:700;color:#1A2A3A;">{{ $ns[0] }}</p>
                                <p style="font-size:0.75rem;color:#4A6580;">{{ $ns[1] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div style="font-size:0.875rem;color:#4A6580;line-height:1.9;" class="space-y-3">
                        <p>Acara kongres diawali dengan pembukaan berisi sambutan dari Ketua Panitia, Ketua Umum ASPAPI periode 2010–2014, dan Sambutan Rektor Universitas Sebelas Maret. Rangkaian acara kongres meliputi:</p>
                    </div>

                    <div class="space-y-2 my-4">
                        @foreach ([
                            'Penyampaian laporan pertanggungjawaban Pengurus Pusat Periode 2010–2014 dilanjutkan Pandangan Umum',
                            'Pemilihan Pimpinan Kongres',
                            'Pembahasan dan Pengesahan Tata Tertib',
                            'Pembahasan dan Pengesahan Perubahan AD/ART',
                            'Pengesahan Rantap Program Kerja',
                            'Pembentukan Tim Formatur untuk memilih Pengurus Pusat Periode 2014–2018',
                            'Pembentukan Komisi, Laporan Sidang Komisi, dan Pengesahan',
                            'Pengesahan Pengurus Pusat ASPAPI periode 2014–2018 dan Serah Terima Kepengurusan',
                        ] as $i => $agenda)
                        <div style="display:flex;align-items:flex-start;gap:0.75rem;background:#FDECEA;padding:0.5rem 0.875rem;border-radius:4px;">
                            <div style="width:20px;height:20px;border-radius:50%;background:#C0392B;color:#fff;font-size:0.65rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ $i + 1 }}</div>
                            <p style="font-size:0.8rem;color:#4A6580;line-height:1.6;">{{ $agenda }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div style="font-size:0.875rem;color:#4A6580;line-height:1.9;" class="space-y-3">
                        <p>Pengurus terpilih secara resmi dilantik pada tanggal <strong style="color:#1A2A3A;">9 November 2014</strong> di Isola Resort, Kampus Bumi Siliwangi, Universitas Pendidikan Indonesia Bandung. Acara pelantikan dilanjutkan dengan Rapat Kerja Nasional membahas tiga topik utama: Anggaran Dasar, Anggaran Rumah Tangga, dan Program Kerja.</p>
                    </div>

                    <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid #FDECEA;">
                        <div style="background:#FDECEA;border-radius:4px;padding:0.75rem 1rem;display:inline-block;">
                            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Ketua Umum Terpilih</p>
                            <p style="font-size:0.9rem;font-weight:700;color:#1A2A3A;margin-top:0.125rem;">Prof. Dr. Tjuju Yuniarsih, S.E., M.Pd.</p>
                            <p style="font-size:0.75rem;color:#4A6580;">Universitas Pendidikan Indonesia · Periode 2014–2018</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONGRES III --}}
            <div class="card" style="border-top:4px solid #2A7FC1;">
                <div class="p-6 md:p-8">
                    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.75rem;margin-bottom:1.25rem;">
                        <div style="background:#2A7FC1;color:#fff;font-family:'DM Serif Display',serif;font-size:1.25rem;font-weight:700;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">III</div>
                        <div>
                            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#C0392B;">Kongres Ketiga</p>
                            <h3 style="font-size:1.1rem;font-weight:700;color:#1A2A3A;line-height:1.3;">Kongres ke-III ASPAPI</h3>
                        </div>
                        <span class="badge" style="background:#EEF4FB;color:#2A7FC1;margin-left:auto;">6 Oktober 2018</span>
                    </div>
                    <p style="font-size:0.8rem;color:#B0CCDF;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.375rem;">
                        <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Isola Resort, Kampus Bumi Siliwangi, Universitas Pendidikan Indonesia
                    </p>
                    <div style="font-size:0.875rem;color:#4A6580;line-height:1.9;" class="space-y-3">
                        <p>Kongres ke-III ASPAPI dilaksanakan tanggal 6 Oktober 2018 di Isola Resort, Kampus Bumi Siliwangi, Universitas Pendidikan Indonesia. Pada kesempatan yang sama juga dilaksanakan <strong style="color:#1A2A3A;">Workshop Penyusunan SKKNI/SKKK Bidang Administrasi Perkantoran</strong> dengan narasumber utama Muchtar Azis, Kepala Subdit Pengembangan dan Harmonisasi Standar Kompetensi, Kementerian Ketenagakerjaan Republik Indonesia.</p>
                        <p>Laporan Ketua Umum ASPAPI Periode 2014–2018 memaparkan berbagai program dan kegiatan yang telah dilaksanakan:</p>
                    </div>

                    <div class="space-y-2 my-4">
                        @foreach ([
                            ['Revisi AD/ART dan Penyusunan Program Kerja', 'Dilaksanakan di Isola Resort, Kampus Bumi Siliwangi Bandung, 9–10 November 2014'],
                            ['Penandatanganan MoU dengan Bantex', 'Dilaksanakan di Isola Resort, Kampus Bumi Siliwangi Bandung, 10 November 2014'],
                            ['Penyegaran Asesor dan TOT Asesor Baru', 'Dilaksanakan di Korwil Jakarta (28–30 Agustus 2015) dan Korwil DIY (20 November 2016)'],
                            ['Penyusunan Learning Outcome (LO) Administrasi Perkantoran', 'Dilakukan melalui tiga workshop: di UPI (5–6 November 2016 bersamaan Rakernas), di UNY (21–22 November — Workshop Penyusunan LO dengan narasumber Prof. Dr. Anik Ghufron, M.Pd. dari Ditjen Pembelajaran Kemristekdikti), dan di UNJ (17–18 November 2017 — Workshop Kurikulum)'],
                            ['Penyusunan Kurikulum Standar Administrasi Perkantoran', 'Melalui Workshop Kurikulum di UNY, 17–18 November 2017 — menghasilkan 18 mata kuliah standar dengan total 53 SKS'],
                            ['Pendirian LSP Administrasi Perkantoran', 'Sebagai wujud nyata pengembangan sertifikasi profesi bidang administrasi perkantoran'],
                        ] as $i => $prog)
                        <div style="background:#EEF4FB;border-radius:4px;padding:0.75rem 0.875rem;display:flex;gap:0.75rem;">
                            <div style="width:20px;height:20px;border-radius:50%;background:#2A7FC1;color:#fff;font-size:0.65rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">{{ $i + 1 }}</div>
                            <div>
                                <p style="font-size:0.8rem;font-weight:700;color:#1A2A3A;">{{ $prog[0] }}</p>
                                <p style="font-size:0.78rem;color:#4A6580;margin-top:0.2rem;line-height:1.6;">{{ $prog[1] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div style="font-size:0.875rem;color:#4A6580;line-height:1.9;" class="space-y-3">
                        <p>Pengurus terpilih secara resmi dilantik pada tanggal <strong style="color:#1A2A3A;">15 Februari 2019</strong> di Fave Hotel Cililitan, Jakarta Timur, dilanjutkan Rapat Program Kerja ASPAPI Periode 2018–2022.</p>
                        <p>Pada 16 Februari 2019 diselenggarakan Seminar Nasional dengan tema: <em>"Peran Pendidikan Administrasi Perkantoran dalam Menghasilkan Lulusan yang Mampu Bersaing Di Era Industri 4.0"</em> dengan narasumber:</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 my-4">
                        @foreach ([
                            ['Dr. Rasto, M.Pd.', 'Wakil Dekan II FPEB, Universitas Pendidikan Indonesia Bandung'],
                            ['Prof. Dr. Rusdarti, M.Si.', 'Dosen Universitas Negeri Semarang (UNNES)'],
                        ] as $ns)
                        <div style="display:flex;align-items:flex-start;gap:0.625rem;background:#EEF4FB;padding:0.625rem 0.875rem;border-radius:4px;">
                            <div style="width:7px;height:7px;border-radius:50%;background:#2A7FC1;margin-top:5px;flex-shrink:0;"></div>
                            <div>
                                <p style="font-size:0.8rem;font-weight:700;color:#1A2A3A;">{{ $ns[0] }}</p>
                                <p style="font-size:0.75rem;color:#4A6580;">{{ $ns[1] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;">Bertindak sebagai moderator <strong style="color:#1A2A3A;">Prof. Dr. Dedi Purwana E.S., M.Bus.</strong> (Dekan FE UNJ).</p>

                    <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid #EEF4FB;">
                        <div style="background:#EEF4FB;border-radius:4px;padding:0.75rem 1rem;display:inline-block;">
                            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Ketua Umum Terpilih</p>
                            <p style="font-size:0.9rem;font-weight:700;color:#1A2A3A;margin-top:0.125rem;">Dr. Henry Eryanto, M.M.</p>
                            <p style="font-size:0.75rem;color:#4A6580;">Universitas Negeri Jakarta · Periode 2018–2022</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONGRES IV --}}
            <div class="card" style="border-top:4px solid #E8B84B;">
                <div class="p-6 md:p-8">
                    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.75rem;margin-bottom:1.25rem;">
                        <div style="background:#E8B84B;color:#1A2A3A;font-family:'DM Serif Display',serif;font-size:1.25rem;font-weight:700;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">IV</div>
                        <div>
                            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#C0392B;">Kongres Keempat</p>
                            <h3 style="font-size:1.1rem;font-weight:700;color:#1A2A3A;line-height:1.3;">Kongres ke-IV ASPAPI</h3>
                        </div>
                        <span class="badge" style="background:#FEF8EC;color:#B8860B;margin-left:auto;">24–28 Oktober 2022</span>
                    </div>
                    <p style="font-size:0.8rem;color:#B0CCDF;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.375rem;">
                        <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Hotel Agro Beach Resort, Kabupaten Bintan, Kepulauan Riau
                    </p>
                    <div style="font-size:0.875rem;color:#4A6580;line-height:1.9;" class="space-y-3">
                        <p>Kongres ke-IV ASPAPI dilaksanakan pada tanggal 24–28 Oktober 2022 di Hotel Agro Beach Resort, Kabupaten Bintan, Provinsi Kepulauan Riau. Kegiatan ini menjadi forum tertinggi organisasi dalam merumuskan arah kebijakan serta memperkuat eksistensi ASPAPI di tingkat nasional.</p>
                        <p>Pembukaan kongres dihadiri oleh <strong style="color:#1A2A3A;">Prof. Dr. Henry Eryanto, M.M.</strong> selaku Ketua ASPAPI periode sebelumnya, yang dalam sambutannya menegaskan pentingnya transformasi administrasi perkantoran di tengah perkembangan teknologi digital yang semakin pesat.</p>
                    </div>

                    {{-- Rangkaian Kegiatan --}}
                    <div class="space-y-2 my-4">
                        @foreach ([
                            ['Hari Pertama — Pembukaan', 'Sambutan Ketua ASPAPI periode 2018–2022, Prof. Dr. Henry Eryanto, M.M., menekankan perlunya peningkatan kompetensi SDM agar tetap relevan dengan kebutuhan dunia kerja di era digital.'],
                            ['Hari Kedua — Seminar Nasional', 'Bertema "Eksistensi Administrasi Perkantoran: Hilang atau Terjang", menghadirkan Prof. Dr. Henry Eryanto, M.M. sebagai pembicara utama dan Dr. Ir. Hilda Herasmus, S.Kom., M.Kom. sebagai narasumber dari kalangan praktisi industri.'],
                            ['Hari Ketiga — Pelatihan & Sertifikasi', 'Penguatan kapasitas organisasi melalui pelatihan dan sertifikasi asesor ASPAPI, dilanjutkan penyampaian laporan pertanggungjawaban pengurus periode sebelumnya.'],
                            ['Hari Keempat — Sidang Pleno & Pemilihan', 'Puncak kongres berupa sidang pleno pemilihan Ketua Umum ASPAPI periode 2022–2026. Terpilih Dr. Rasto, M.Pd. sebagai Ketua Umum yang baru.'],
                            ['Hari Kelima — Penutupan', 'Dokumentasi bersama dan kepulangan peserta ke daerah masing-masing.'],
                        ] as $i => $agenda)
                        <div style="background:#FEF8EC;border-radius:4px;padding:0.75rem 0.875rem;display:flex;gap:0.75rem;">
                            <div style="width:20px;height:20px;border-radius:50%;background:#E8B84B;color:#1A2A3A;font-size:0.65rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">{{ $i + 1 }}</div>
                            <div>
                                <p style="font-size:0.8rem;font-weight:700;color:#1A2A3A;">{{ $agenda[0] }}</p>
                                <p style="font-size:0.78rem;color:#4A6580;margin-top:0.2rem;line-height:1.6;">{{ $agenda[1] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Narasumber Seminar --}}
                    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#B8860B;margin-bottom:0.75rem;">Narasumber Seminar Nasional</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        @foreach ([
                            ['Prof. Dr. Henry Eryanto, M.M.', 'Pembicara Utama — Universitas Negeri Jakarta'],
                            ['Dr. Ir. Hilda Herasmus, S.Kom., M.Kom.', 'Narasumber Praktisi Industri'],
                        ] as $ns)
                        <div style="display:flex;align-items:flex-start;gap:0.625rem;background:#FEF8EC;padding:0.625rem 0.875rem;border-radius:4px;">
                            <div style="width:7px;height:7px;border-radius:50%;background:#E8B84B;margin-top:5px;flex-shrink:0;"></div>
                            <div>
                                <p style="font-size:0.8rem;font-weight:700;color:#1A2A3A;">{{ $ns[0] }}</p>
                                <p style="font-size:0.75rem;color:#4A6580;">{{ $ns[1] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <p style="font-size:0.875rem;color:#4A6580;line-height:1.9;">Seminar menegaskan bahwa profesi administrasi perkantoran akan tetap eksis selama organisasi masih berjalan, namun memerlukan <strong style="color:#1A2A3A;">adaptasi berbasis teknologi digital</strong> dalam pelaksanaannya.</p>

                    <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid #FEF8EC;">
                        <div style="background:#FEF8EC;border-radius:4px;padding:0.75rem 1rem;display:inline-block;">
                            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Ketua Umum Terpilih</p>
                            <p style="font-size:0.9rem;font-weight:700;color:#1A2A3A;margin-top:0.125rem;">Dr. Rasto, M.Pd.</p>
                            <p style="font-size:0.75rem;color:#4A6580;">Universitas Pendidikan Indonesia · Periode 2022–2026</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection