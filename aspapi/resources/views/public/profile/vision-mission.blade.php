@extends('layouts.app')

@php
$title = 'Visi dan Misi';
$description = 'Visi, misi, dan tujuan ASPAPI dalam membangun SDM administrasi perkantoran Indonesia yang kompeten, kompetitif, dan kolaboratif.';
@endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div style="background: linear-gradient(135deg, #111E2A, #1A5F9A, #2A7FC1); position: relative; padding-top: 3rem; padding-bottom: 2.5rem; padding-left: 1.5rem; padding-right: 1.5rem; overflow: hidden;">
    <div style="position:absolute;inset-x:0;top:0;height:4px;background:linear-gradient(90deg,#C0392B,#E8B84B);"></div>
    <div class="max-w-7xl mx-auto">
        <nav style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#A8D4F5;margin-bottom:1rem;">
            <a href="{{ route('home') }}" style="color:#A8D4F5;">Beranda</a>
            <span>›</span>
            <span style="color:#fff;font-weight:600;">Visi dan Misi</span>
        </nav>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#E8B84B;margin-bottom:0.5rem;">Profil Organisasi</p>
        <h1 style="font-family:'DM Serif Display',serif;color:#ffffff;font-size:clamp(1.75rem,4vw,2.75rem);line-height:1.2;">
            Visi dan Misi ASPAPI
        </h1>
        <p style="color:#A8D4F5;font-size:0.875rem;margin-top:0.75rem;max-width:600px;line-height:1.7;">
            Arah dan komitmen ASPAPI dalam membangun profesionalisme administrasi perkantoran Indonesia.
        </p>
    </div>
</div>

{{-- ── VISI ── --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6">

        <div class="flex flex-col md:flex-row gap-10 items-start">

            {{-- Label kiri --}}
            <div class="flex-shrink-0 md:w-48">
                <div style="width:4px;height:48px;background:linear-gradient(180deg,#2A7FC1,#E8B84B);border-radius:2px;margin-bottom:1rem;"></div>
                <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#C0392B;">Visi</p>
                <h2 style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#1A2A3A;line-height:1.2;margin-top:0.25rem;">Tujuan Besar Kami</h2>
            </div>

            {{-- Konten visi --}}
            <div class="flex-1">
                <div style="background:#EEF4FB;border-left:4px solid #2A7FC1;border-radius:0 6px 6px 0;padding:2rem;">
                    <p style="font-family:'DM Serif Display',serif;font-size:1.1rem;color:#1A2A3A;line-height:1.8;font-style:italic;">
                        "Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia (ASPAPI) menjadi organisasi profesi yang unggul dan berwatak sosial, dalam rangka pembangunan sumber daya manusia Indonesia yang kompeten dan profesional di bidang administrasi/manajemen perkantoran."
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ── MISI ── --}}
<section class="py-16" style="background:#F8FAFC;">
    <div class="max-w-4xl mx-auto px-6">

        <div class="flex flex-col md:flex-row gap-10 items-start">

            {{-- Label kiri --}}
            <div class="flex-shrink-0 md:w-48">
                <div style="width:4px;height:48px;background:linear-gradient(180deg,#C0392B,#E8B84B);border-radius:2px;margin-bottom:1rem;"></div>
                <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#C0392B;">Misi</p>
                <h2 style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#1A2A3A;line-height:1.2;margin-top:0.25rem;">Langkah Nyata Kami</h2>
            </div>

            {{-- Konten misi --}}
            <div class="flex-1 space-y-4">

                @php
                $misi = [
                    [
                        'no'   => '01',
                        'text' => 'Mengembangkan ASPAPI sebagai organisasi profesi yang mampu mewadahi berbagai pemikiran yang digali secara spekulatif dan/atau empirik, baik diperoleh melalui pengalaman penyelenggaraan pendidikan maupun melalui berbagai dialog atau pertemuan ilmiah.',
                    ],
                    [
                        'no'   => '02',
                        'text' => 'Mengembangkan wacana, paradigma, kerangka berpikir, model dan konsensus akademis atau praktis bidang administrasi/manajemen perkantoran yang potensial diterapkan dalam konteks Indonesia.',
                    ],
                    [
                        'no'   => '03',
                        'text' => 'Menyebarluaskan berbagai hasil pemikiran melalui penerapan teknologi informasi dan komunikasi, sehingga terjadi proses difusi dalam berbagai konteks.',
                    ],
                    [
                        'no'   => '04',
                        'text' => 'Memfasilitasi para ahli, praktisi dan pemerhati ilmu administrasi/manajemen untuk mengkaji lebih lanjut atau menerapkan berbagai hasil pemikiran, sehingga secara individual maupun kelompok dapat memberikan kontribusi yang bermakna bagi perkembangan keilmuan dan bidang kajian dalam program pendidikan.',
                    ],
                    [
                        'no'   => '05',
                        'text' => 'Memberi masukan kepada Pemerintah, baik melalui Kementerian Kebudayaan dan Pendidikan Dasar dan Menengah, Kementerian Riset dan Teknologi serta Pendidikan Tinggi, maupun Kementerian lain yang relevan, serta kepada Pemerintah Daerah berupa pertimbangan akademis dan profesional serta rekomendasi untuk peningkatan mutu pendidikan.',
                    ],
                    [
                        'no'   => '06',
                        'text' => 'Mengembangkan diri menjadi Lembaga Sertifikasi Profesi untuk melakukan uji kompetensi bidang administrasi dan manajemen perkantoran, serta kesekretarisan.',
                    ],
                ];
                @endphp

                @foreach ($misi as $item)
                <div class="flex items-start gap-4 p-5 bg-white rounded-md" style="border:1px solid #6bbcff;border-left:4px solid #C0392B;">
                    <span style="font-family:'DM Serif Display',serif;font-size:1.5rem;color:#6bbcff;line-height:1;flex-shrink:0;min-width:2.5rem;">{{ $item['no'] }}</span>
                    <p style="font-size:0.875rem;color:#4A6580;line-height:1.8;">{{ $item['text'] }}</p>
                </div>
                @endforeach

            </div>
        </div>

    </div>
</section>

{{-- ── TUJUAN ── --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6">

        <div class="flex flex-col md:flex-row gap-10 items-start">

            {{-- Label kiri --}}
            <div class="flex-shrink-0 md:w-48">
                <div style="width:4px;height:48px;background:linear-gradient(180deg,#E8B84B,#2A7FC1);border-radius:2px;margin-bottom:1rem;"></div>
                <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#C0392B;">Tujuan</p>
                <h2 style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#1A2A3A;line-height:1.2;margin-top:0.25rem;">Kontribusi Kami</h2>
            </div>

            {{-- Konten tujuan --}}
            <div class="flex-1">
                <div style="background:#FEF8EC;border-left:4px solid #E8B84B;border-radius:0 6px 6px 0;padding:2rem;">
                    <p style="font-size:0.9rem;color:#4A6580;line-height:1.9;">
                        Menyumbangkan pemikiran untuk pembangunan nasional secara profesional dalam penataan, perlindungan, pemberdayaan administrasi dan manajemen perkantoran sehingga lebih terarah, berhasil, dan berdaya guna melalui penelitian, pengembangan dan penerapan ilmu pengetahuan dan teknologi dalam upaya mencerdaskan kehidupan bangsa.
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ── CTA ── --}}
<section style="background:linear-gradient(135deg,#111E2A,#1A5F9A);padding:4rem 1.5rem;position:relative;">
    <div style="position:absolute;inset-x:0;top:0;height:3px;background:linear-gradient(90deg,#E8B84B,#C0392B);"></div>
    <div class="max-w-4xl mx-auto text-center">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#E8B84B;margin-bottom:0.5rem;">Bergabung Bersama Kami</p>
        <h2 style="font-family:'DM Serif Display',serif;color:#fff;font-size:1.75rem;margin-bottom:1rem;">Wujudkan Visi Bersama ASPAPI</h2>
        <p style="color:#A8D4F5;font-size:0.875rem;line-height:1.7;max-width:500px;margin:0 auto 2rem;">
            Jadilah bagian dari organisasi profesi yang berkomitmen membangun SDM administrasi perkantoran Indonesia yang kompeten dan profesional.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('members.register') }}" class="btn btn-accent-yellow">Daftar Anggota</a>
            <a href="{{ route('profile.history') }}" class="btn" style="border:2px solid rgba(255,255,255,0.3);color:#fff;">Sejarah ASPAPI</a>
        </div>
    </div>
</section>

@endsection