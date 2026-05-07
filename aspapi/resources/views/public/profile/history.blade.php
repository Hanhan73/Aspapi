@extends('layouts.app')

@php $title = 'Sejarah Singkat'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div style="background: linear-gradient(135deg, #111E2A, #1A5F9A, #2A7FC1); position: relative; padding-top: 3rem; padding-bottom: 2.5rem; padding-left: 1.5rem; padding-right: 1.5rem; overflow: hidden;">
    <div style="position:absolute;inset-x:0;top:0;height:4px;background:linear-gradient(90deg,#C0392B,#E8B84B);"></div>
    <div class="max-w-7xl mx-auto">
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#A8D4F5;margin-bottom:1rem;">
            <a href="{{ route('home') }}" style="color:#A8D4F5;">Beranda</a>
            <span>›</span>
            <span style="color:#fff;font-weight:600;">Sejarah Singkat</span>
        </nav>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#E8B84B;margin-bottom:0.5rem;">Profil Organisasi</p>
        <h1 style="font-family:'DM Serif Display',serif;color:#ffffff;font-size:clamp(1.75rem,4vw,2.75rem);line-height:1.2;">
            Sejarah Singkat ASPAPI
        </h1>
        <p style="color:#A8D4F5;font-size:0.875rem;margin-top:0.75rem;max-width:600px;line-height:1.7;">
            Perjalanan panjang penuh dedikasi dalam membentuk wadah formal bagi para sarjana
            dan praktisi administrasi perkantoran Indonesia.
        </p>
    </div>
</div>

{{-- ── INTRO ── --}}
<section class="py-14 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <p class="section-label">Latar Belakang</p>
        <h2 class="section-title mt-1">Awal Mula Terbentuknya ASPAPI</h2>
        <div class="section-divider"></div>
        <div class="space-y-4 text-sm leading-relaxed" style="color:#4A6580;">
            <p>Terbentuknya Asosiasi Sarjana dan Praktisi Administrasi Perkantoran (ASPAPI) berawal dari keberanian dan tekad para inisiator yang memiliki gagasan untuk membentuk suatu organisasi yang dapat menjadi wadah bagi para sarjana dan praktisi administrasi perkantoran. Perjalanan panjang ini diwarnai oleh berbagai tantangan, memerlukan kerja keras yang tidak kenal lelah, serta kolaborasi yang erat antar inisiator dengan visi yang serupa dalam upaya mengembangkan dan memajukan bidang administrasi perkantoran.</p>
            <p>Gagasan untuk membentuk ASPAPI timbul sebagai tanggapan terhadap perkembangan pesat dunia administrasi perkantoran. Di tengah dinamika perubahan tersebut, muncul kebutuhan mendesak untuk membentuk sebuah wadah yang dapat mendukung pertukaran ide, pengetahuan, dan pengalaman di antara para ahli dan praktisi di bidang administrasi perkantoran.</p>
            <p>Para inisiator menjalani serangkaian pertemuan dengan penuh semangat, meskipun proses ini tidak terlepas dari pengorbanan waktu, tenaga, pikiran, dan bahkan biaya yang tidak sedikit. Dedikasi mereka tercermin dalam upaya keras untuk membentuk landasan kokoh bagi ASPAPI, menghadapi setiap hambatan dengan tekad yang kuat.</p>
        </div>
    </div>
</section>

{{-- ── TIMELINE ── --}}
<section class="py-14" style="background:#F8FAFC;">
    <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-12">
            <p class="section-label">Perjalanan Pembentukan</p>
            <h2 class="section-title mt-1">9 Pertemuan Menuju ASPAPI</h2>
            <div class="section-divider mx-auto"></div>
        </div>

        @php
        $meetings = [
            [
                'no'       => 1,
                'date'     => '27 Februari 2010',
                'loc'      => 'Program Studi PAP, Universitas Sebelas Maret (UNS)',
                'title'    => 'Inisiasi untuk Membentuk Asosiasi Profesi di Bidang Administrasi Perkantoran',
                'bg'       => '#2A7FC1',
                'tbg'      => '#EEF4FB',
                'tc'       => '#2A7FC1',
                'border'   => '#2A7FC1',
                'dot_text' => '#fff',
                'content'  => [
                    'Pada tanggal 27 Februari 2010, diadakan Pertemuan 1 di Program Studi Pendidikan Administrasi Perkantoran (PAP) Universitas Sebelas Maret (UNS). Pertemuan ini diinisiasi dengan tujuan utama untuk menjalin hubungan yang lebih erat antara Program Studi Pendidikan Administrasi Perkantoran dan para alumni. Agenda utama pertemuan melibatkan dialog konstruktif dengan para alumni PAP UNS, serta praktisi di bidang administrasi perkantoran. Fokus utamanya adalah merumuskan pemikiran bersama dan usulan yang dapat menjadi dasar pembentukan Asosiasi Profesi di bidang Administrasi Perkantoran.',
                    'Dalam pertemuan ini, terbangun semangat untuk menggalang persatuan antara para alumni dan praktisi, dengan harapan dapat memperkuat eksistensi PAP UNS. Diskusi melibatkan pemikiran kreatif dan solutif guna memperkuat hubungan antara institusi dan lulusan. Tujuannya adalah menciptakan wadah yang lebih formal dan berkelanjutan. Pertemuan ini menjadi titik awal yang signifikan dalam perjalanan panjang pembentukan ASPAPI.',
                    'Pada pertemuan tersebut, dilakukan analisis SWOT untuk mengevaluasi Program Studi PAP di UNS. Kekuatan program terletak pada bekal kemampuan lulusan yang banyak dibutuhkan di lapangan kerja. Namun terdapat kelemahan signifikan karena program ini tidak termasuk dalam daftar UMPTN/SNMPTN sehingga kurang dikenal di dunia kerja.',
                    'Dalam merespons permasalahan tersebut, beberapa solusi diusulkan: membentuk lembaga pengujian independen, mendirikan Asosiasi Profesi Pendidikan Administrasi Perkantoran, dan meningkatkan status BKK PAP menjadi Program Studi.',
                ],
            ],
            [
                'no'       => 2,
                'date'     => '1 Mei 2010',
                'loc'      => 'Ruang Sidang Dekan FKIP, Universitas Sebelas Maret (UNS)',
                'title'    => 'Nama ASPAPI Disepakati',
                'bg'       => '#C0392B',
                'tbg'      => '#FDECEA',
                'tc'       => '#C0392B',
                'border'   => '#C0392B',
                'dot_text' => '#fff',
                'content'  => [
                    'Pada Pertemuan 2 yang diadakan pada tanggal 1 Mei 2010 di Ruang Sidang Dekan Fakultas Keguruan dan Ilmu Pendidikan (FKIP), Universitas Sebelas Maret (UNS), berlangsung sejumlah perkembangan signifikan. Salah satu hasil utama dari pertemuan ini adalah kesepakatan pembentukan Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia (ASPAPI).',
                    'Nama ini disepakati dari berbagai alternatif nama yang muncul, yaitu: (a) Asosiasi Profesi dan Sarjana Administrasi Perkantoran Indonesia; (b) Asosiasi Profesi dan Pendidikan Administrasi Perkantoran Indonesia; (c) Asosiasi Pendidik dan Profesi Administrasi Perkantoran Indonesia; (d) Asosiasi Sarjana Administrasi Perkantoran Indonesia; (e) Himpunan Administrasi Perkantoran Indonesia; (f) Asosiasi Sarjana Pendidikan Administrasi Perkantoran Indonesia; (g) Himpunan Sarjana Pendidikan Administrasi Perkantoran Indonesia.',
                    'Hasil pertemuan juga mencakup rencana penyelenggaraan Kongres & Seminar Nasional untuk menyatukan pemikiran dan melahirkan Asosiasi Profesi di bidang Administrasi Perkantoran seluruh Indonesia. Undangan akan diberikan kepada berbagai Perguruan Tinggi se-Indonesia yang memiliki jurusan/program studi Administrasi Perkantoran, serta SMK se-Jawa Tengah dan praktisi Bisnis dan Perkantoran.',
                    'Langkah berikutnya adalah penyusunan Anggaran Dasar dan Anggaran Rumah Tangga (AD & ART) ASPAPI yang direncanakan akan dibahas pada pertemuan berikutnya di Universitas Negeri Yogyakarta (UNY).',
                ],
            ],
            [
                'no'       => 3,
                'date'     => '19 Juni 2010',
                'loc'      => 'Ruang Sidang Dekan, Universitas Negeri Yogyakarta (UNY)',
                'title'    => 'Penyusunan Draft Anggaran Dasar (AD) dan Anggaran Rumah Tangga (ART) ASPAPI',
                'bg'       => '#2A7FC1',
                'tbg'      => '#EEF4FB',
                'tc'       => '#2A7FC1',
                'border'   => '#2A7FC1',
                'dot_text' => '#fff',
                'content'  => [
                    'Pertemuan 3, yang merupakan rapat koordinasi lanjutan untuk pembentukan ASPAPI, diselenggarakan pada Sabtu, 19 Juni 2010, di Ruang Sidang Dekan Universitas Negeri Yogyakarta. Agenda utama dari pertemuan ini melibatkan dua aspek penting, yaitu penyusunan Anggaran Dasar (AD) dan persiapan penyelenggaraan kongres serta seminar nasional ASPAPI.',
                    'Dalam penyusunan AD, berbagai aspek terkait struktur organisasi, tujuan, dan tugas pokok ASPAPI dibahas secara mendalam. Adanya diskusi yang komprehensif diharapkan dapat menghasilkan landasan yang kuat untuk pembentukan ASPAPI sebagai lembaga yang berfungsi efektif.',
                    'Selain itu, persiapan penyelenggaraan kongres dan seminar nasional ASPAPI menjadi fokus utama. Rinciannya melibatkan perencanaan program, undangan kepada narasumber dan peserta, serta persiapan logistik dan teknis lainnya. Kongres dan seminar nasional ini diharapkan menjadi platform penting dalam membangun kesatuan, pertukaran ide, dan pengembangan profesi Administrasi Perkantoran di Indonesia.',
                ],
            ],
            [
                'no'       => 4,
                'date'     => '6 Juli 2010',
                'loc'      => 'Candi Resto, Solo-Baru',
                'title'    => 'Pembentukan Kepanitiaan Kongres dan Seminar ASPAPI',
                'bg'       => '#C0392B',
                'tbg'      => '#FDECEA',
                'tc'       => '#C0392B',
                'border'   => '#C0392B',
                'dot_text' => '#fff',
                'content'  => [
                    'Pertemuan 4, yang fokus pada persiapan penyelenggaraan Kongres dan Seminar ASPAPI, diadakan pada tanggal 6 Juli 2010, di Candi Resto, Solo-Baru. Pertemuan ini dihadiri oleh semua dosen Program Studi Pendidikan Administrasi Perkantoran (PAP) UNS sebanyak 10 orang, serta perwakilan dari Sekolah Menengah Kejuruan (SMK) di wilayah Surakarta, Wonogiri, Karanganyar, Sukoharjo, Klaten, Blora, dan praktisi bisnis.',
                    'Agenda utama pertemuan ini adalah merinci persiapan penyelenggaraan Kongres dan Seminar ASPAPI. Diskusi melibatkan semua peserta dengan tujuan menyelaraskan langkah-langkah konkrit untuk kesuksesan acara tersebut.',
                    'Hasil utama dari pertemuan ini adalah terbentuknya susunan kepanitiaan Kongres dan Seminar. Keberhasilan pembentukan susunan kepanitiaan menjadi langkah positif dalam memastikan kelancaran dan kesuksesan acara yang akan menjadi panggung utama untuk memperkuat jaringan serta berbagi pengetahuan di bidang Administrasi Perkantoran.',
                ],
            ],
            [
                'no'       => 5,
                'date'     => '17 Juli 2010',
                'loc'      => 'Universitas Negeri Yogyakarta (UNY)',
                'title'    => 'Pembahasan Rencana Kongres dan Seminar ASPAPI',
                'bg'       => '#2A7FC1',
                'tbg'      => '#EEF4FB',
                'tc'       => '#2A7FC1',
                'border'   => '#2A7FC1',
                'dot_text' => '#fff',
                'content'  => [
                    'Pertemuan 5, yang diselenggarakan di Universitas Negeri Yogyakarta (UNY) pada tanggal 17 Juli 2010, dihadiri oleh perwakilan dari Universitas Sebelas Maret (UNS), UNY, Universitas Negeri Jakarta (UNJ), Universitas Negeri Malang (UM), dan Universitas Negeri Surabaya (UNESA).',
                    'Hasil pertemuan ini meliputi beberapa poin penting: (1) Diputuskan rencana pelaksanaan Kongres dan Seminar pada tanggal 16-17 Oktober di Riyadi Palace Hotel. (2) Disusun rancangan seminar dengan prioritas pada stakeholders terkait. (3) Ditetapkan topik-topik yang akan dibahas: Pencitraan dan Eksistensi Program Studi PAP, Kurikulum dan Standar Kompetensi Keahlian PAP, serta Jaringan kemitraan dengan BKD, BKM, BKN, dan BSNP.',
                    'Ditetapkan tema kongres: "Revitalisasi Pendidikan Administrasi Perkantoran dalam Merespon Kompetensi dan Kompetisi Global". Pertemuan ini menciptakan landasan yang kokoh untuk persiapan dan pelaksanaan Kongres dan Seminar ASPAPI, memastikan bahwa acara tersebut menjadi platform yang efektif untuk pembahasan terkait Pendidikan Administrasi Perkantoran di tingkat nasional.',
                ],
            ],
            [
                'no'       => 6,
                'date'     => '13 Agustus 2010',
                'loc'      => 'Program Studi Pendidikan Ekonomi FKIP, Universitas Sebelas Maret (UNS) Surakarta',
                'title'    => 'Koordinasi Persiapan Penyelenggaraan Kongres dan Seminar ASPAPI',
                'bg'       => '#C0392B',
                'tbg'      => '#FDECEA',
                'tc'       => '#C0392B',
                'border'   => '#C0392B',
                'dot_text' => '#fff',
                'content'  => [
                    'Pertemuan 6, yang merupakan Rapat Koordinasi Persiapan Penyelenggaraan Kongres dan Seminar ASPAPI, berlangsung pada Jumat, 13 Agustus 2010. Pertemuan ini berhasil membentuk kepanitiaan untuk pelaksanaan Kongres dan Seminar ASPAPI yang akan datang.',
                    'Rapat koordinasi ini membahas secara mendalam persiapan teknis dan logistik untuk memastikan kelancaran acara tersebut. Para peserta, yang terdiri dari berbagai pihak terkait, berkontribusi dalam menentukan tugas dan tanggung jawab masing-masing anggota kepanitiaan.',
                    'Terbentuknya kepanitiaan kongres dan seminar menjadi langkah konkrit dalam menjalankan rencana penyelenggaraan acara tersebut. Sinergi dari anggota kepanitiaan memastikan bahwa Kongres dan Seminar ASPAPI dapat berjalan dengan sukses dan memberikan kontribusi positif bagi pengembangan Pendidikan Administrasi Perkantoran di Indonesia.',
                ],
            ],
            [
                'no'       => 7,
                'date'     => '5 September 2010',
                'loc'      => 'Restoran Ilham',
                'title'    => 'Pematangan Acara Kongres dan Seminar ASPAPI',
                'bg'       => '#2A7FC1',
                'tbg'      => '#EEF4FB',
                'tc'       => '#2A7FC1',
                'border'   => '#2A7FC1',
                'dot_text' => '#fff',
                'content'  => [
                    'Pertemuan 7, yang merupakan Rapat Koordinasi Persiapan Penyelenggaraan Kongres antara Universitas Negeri Yogyakarta (UNY) dengan Universitas Sebelas Maret (UNS), dilaksanakan pada tanggal 5 September 2010 di Restoran Ilham. Pertemuan ini menghasilkan beberapa keputusan penting.',
                    'Pertama, dilakukan penataan ulang daftar acara kongres. Kedua, terjadi pergeseran rencana launching. Ketiga, jumlah kontribusi kongres untuk peserta ditentukan. Keempat, diputuskan tempat pelaksanaan di Graha Solo Raya dengan penginapan di Hotel Best Western Premiere pada tanggal 16 dan 17 Oktober 2010.',
                    'Kelima, diputuskan narasumber yang akan mengisi acara kongres: (a) Prof. Djemari Mardapi, Ph.D. — Ketua Badan Standar Nasional Pendidikan; (b) Prof. Dr. Muhyadi, M.Pd. — Guru Besar Program Studi Pendidikan Administrasi Perkantoran; (c) Prof. Dr. Fuqon Hidayatullah, M.Pd. — Ketua Forum Komunikasi Pendidikan Tinggi Kejuruan seluruh Indonesia; (d) Drs. Ramon Torong, MM. — Praktisi Dunia Usaha dan Industri.',
                ],
            ],
            [
                'no'       => 8,
                'date'     => '8 Oktober 2010',
                'loc'      => 'Kantor Program Studi PAP, Universitas Sebelas Maret (UNS)',
                'title'    => 'Persiapan Akhir Pelaksanaan Kongres dan Seminar ASPAPI',
                'bg'       => '#C0392B',
                'tbg'      => '#FDECEA',
                'tc'       => '#C0392B',
                'border'   => '#C0392B',
                'dot_text' => '#fff',
                'content'  => [
                    'Pertemuan 8, yang merupakan Rapat Koordinasi Terakhir menjelang Pelaksanaan Kongres dan Seminar, diselenggarakan pada tanggal 8 Oktober 2010. Agenda utama adalah laporan akhir persiapan Kongres dan Seminar ASPAPI oleh Ketua Pelaksana Kongres dan para Ketua Bidang.',
                    'Telah disepakati para narasumber beserta topik seminar: (1) Prof. Muhyadi, M.Pd. — "Asosiasi Profesi sebagai Sarana Aktualisasi dan Eksistensi Pendidikan Administrasi Perkantoran menuju Persaingan Global"; (2) Prof. Djemari Mardapi, Ph.D. — "Uji Kompetensi sebagai Tuntutan Profesional Lulusan SMK"; (3) Prof. Dr. Furqon Hidayatullah, M.Pd. — "Sinergi LPTK dalam Mendukung ASPAPI"; (4) Drs. Edy Ramon Torong, MM. — "Peran Dunia Usaha dalam Uji Kompetensi Lulusan SMK".',
                    'Disampaikan pula materi-materi yang akan dibahas oleh Komisi Kongres: (1) Membangun Jaringan Kerjasama antara ASPAPI dengan lembaga lain — pembahas dari UNESA dan UM; (2) Pola Pengembangan PAP ke Depan — pembahas dari UNJ; (3) Penyusunan Rambu-Rambu Uji Kompetensi PAP — pembahas dari UPI, dipimpin Prof. Dr. Tjutju Yuniarsih, M.Pd.; (4) Pembahasan AD&ART, logo, dan bendera ASPAPI — pembahas dari UNS dan UNY, dipimpin Prof. Muhyadi dan Dr. Wiedy.',
                ],
            ],
        ];
        @endphp

        <div class="space-y-8">
            @foreach ($meetings as $m)
            <div class="relative flex gap-6 md:gap-10">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold z-10"
                         style="background:{{ $m['bg'] }};color:{{ $m['dot_text'] }};font-family:'DM Serif Display',serif;">
                        {{ $m['no'] }}
                    </div>
                </div>
                <div class="card flex-1 mb-2" style="border-top:4px solid {{ $m['border'] }};">
                    <div class="p-6">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <span class="badge" style="background:{{ $m['tbg'] }};color:{{ $m['tc'] }};">Pertemuan {{ $m['no'] }}</span>
                            <span class="text-2xs font-medium" style="color:#B0CCDF;">{{ $m['date'] }}</span>
                        </div>
                        <h3 class="text-sm font-bold text-navy mb-1 leading-snug">{{ $m['title'] }}</h3>
                        <p class="text-2xs mb-4 flex items-center gap-1" style="color:#B0CCDF;">
                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $m['loc'] }}
                        </p>
                        <div class="space-y-3">
                            @foreach ($m['content'] as $para)
                            <p class="text-sm leading-relaxed" style="color:#4A6580;">{{ $para }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- PERTEMUAN 9 — special --}}
            <div class="relative flex gap-6 md:gap-10">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold z-10"
                         style="background:#E8B84B;color:#1A2A3A;font-family:'DM Serif Display',serif;">9</div>
                </div>
                <div class="card flex-1 mb-2" style="border-top:4px solid #E8B84B;">
                    <div class="p-6">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <span class="badge" style="background:#FEF8EC;color:#B8860B;">Pertemuan 9</span>
                            <span class="text-2xs font-medium" style="color:#B0CCDF;">16–17 Oktober 2010</span>
                        </div>
                        <h3 class="text-sm font-bold text-navy mb-1 leading-snug">Pelaksanaan Kongres dan Seminar ASPAPI — ASPAPI Resmi Berdiri</h3>
                        <p class="text-2xs mb-4 flex items-center gap-1" style="color:#B0CCDF;">
                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Graha Solo Raya, Surakarta
                        </p>
                        <div class="space-y-3 mb-6">
                            <p class="text-sm leading-relaxed" style="color:#4A6580;">Dengan semangat perjuangan dan kerja keras para inisiator, perjalanan panjang dalam pembentukan Asosiasi Sarjana dan Praktisi Administrasi Perkantoran (ASPAPI) berhasil mencapai puncaknya melalui penyelenggaraan Kongres dan Seminar Nasional pada tanggal 16-17 Oktober 2010. Pertemuan-pertemuan yang diadakan sebelumnya menjadi fondasi yang kokoh, membangun landasan bagi terbentuknya ASPAPI sebagai wadah formal yang menghimpun para sarjana dan praktisi administrasi perkantoran.</p>
                            <p class="text-sm leading-relaxed" style="color:#4A6580;">Kongres dan Seminar tersebut tidak hanya menjadi tonggak bersejarah dalam membentuk ASPAPI, tetapi juga menjadi momentum untuk menyatukan pemikiran, pertukaran ide, dan pengembangan profesi administrasi perkantoran di Indonesia. Dengan melibatkan berbagai pihak terkait — termasuk akademisi, praktisi, dan pemangku kepentingan lainnya — acara ini memberikan kontribusi positif dalam merespons dinamika perkembangan bidang administrasi perkantoran, baik di tingkat pendidikan maupun industri.</p>
                            <p class="text-sm leading-relaxed" style="color:#4A6580;">Sebagai hasil dari perjalanan yang penuh dedikasi ini, ASPAPI menjadi lebih dari sekadar asosiasi profesi. Ia menjadi suatu entitas yang mewakili suara dan kepentingan bersama para sarjana dan praktisi administrasi perkantoran. Dengan semangat kolaboratif yang ditanamkan sejak awal, ASPAPI terus melangkah maju untuk memajukan standar dan kualitas dalam dunia administrasi perkantoran, menciptakan jaringan yang kuat, serta memberikan kontribusi berarti dalam mencetak para profesional yang siap menghadapi tantangan global.</p>
                        </div>

                        {{-- Narasumber --}}
                        <div class="pt-5 border-t border-neutral-100">
                            <p class="text-2xs font-bold tracking-widest uppercase mb-4" style="color:#2A7FC1;">Narasumber Seminar</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ([
                                    ['Prof. Muhyadi, M.Pd.', 'Asosiasi Profesi sebagai Sarana Aktualisasi dan Eksistensi Pendidikan Administrasi Perkantoran menuju Persaingan Global'],
                                    ['Prof. Djemari Mardapi, Ph.D.', 'Uji Kompetensi sebagai Tuntutan Profesional Lulusan SMK'],
                                    ['Prof. Dr. Furqon Hidayatullah, M.Pd.', 'Sinergi LPTK dalam Mendukung ASPAPI'],
                                    ['Drs. Edy Ramon Torong, MM.', 'Peran Dunia Usaha dalam Uji Kompetensi Lulusan SMK'],
                                ] as $i => $ns)
                                <div class="flex items-start gap-3 p-3 rounded" style="background:#EEF4FB;">
                                    <div class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center text-xs font-bold text-white" style="background:#2A7FC1;">{{ $i + 1 }}</div>
                                    <div>
                                        <p class="text-xs font-bold" style="color:#1A2A3A;">{{ $ns[0] }}</p>
                                        <p class="text-2xs mt-0.5" style="color:#4A6580;font-style:italic;">{{ $ns[1] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <span class="badge" style="background:#FEF8EC;color:#B8860B;">ASPAPI Resmi Berdiri — 7 Oktober 2010</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CLOSING ── --}}
<section class="py-14 bg-white">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <p class="section-label">Warisan Para Inisiator</p>
        <h2 class="section-title mt-1">ASPAPI Hari Ini</h2>
        <div class="section-divider mx-auto"></div>
        <p class="text-sm leading-relaxed max-w-2xl mx-auto mb-8" style="color:#4A6580;">
            Dikukuhkan melalui Akta Notaris Sri Purwanti, S.H. Nomor 01 tanggal 7 Oktober 2010,
            ASPAPI terus melangkah maju memajukan standar dan kualitas profesi administrasi perkantoran Indonesia.
            Dengan semangat kolaboratif yang ditanamkan sejak awal, ASPAPI hadir di berbagai daerah,
            menghimpun ribuan sarjana dan praktisi yang berdedikasi untuk kemajuan bangsa.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('profile.initiators') }}" class="btn btn-primary">Lihat Para Inisiator</a>
            <a href="{{ route('profile.congress') }}" class="btn btn-outline">Sejarah Kongres</a>
        </div>
    </div>
</section>

@endsection
