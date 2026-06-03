<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    @page {
        size: A4 landscape;
        margin: 0;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        width: 297mm;
        height: 210mm;
        font-family: 'DejaVu Sans', Arial, sans-serif;
        position: relative;
        overflow: hidden;
        background: #ffffff;
    }

    .bg-template {
        position: absolute;
        top: 0;
        left: 0;
        width: 297mm;
        height: 210mm;
    }

    .nomor {
        position: absolute;
        top: 66mm;
        left: 52mm;
        font-size: 10pt;
        color: #1a1a1a;
        letter-spacing: 0.3px;
    }

    .diberikan-label {
        position: absolute;
        top: 80mm;
        left: 52mm;
        font-size: 10pt;
        color: #1a1a1a;
    }

    .recipient-name {
        position: absolute;
        top: 87mm;
        left: 52mm;
        right: 20mm;
        font-size: 20pt;
        font-weight: bold;
        color: #CC1A1A;
        line-height: 1.2;
    }

    .atas-partisipasi {
        position: absolute;
        top: 110mm;
        left: 52mm;
        right: 22mm;
        font-size: 10pt;
        color: #1a1a1a;
        line-height: 1.6;
    }

    .tema-value {
        position: absolute;
        top: 132mm;
        left: 52mm;
        right: 22mm;
        font-size: 11pt;
        font-weight: bold;
        color: #1a1a1a;
        line-height: 1.4;
    }

    .kota-tanggal {
        position: absolute;
        bottom: 42mm;
        right: 52mm;
        font-size: 9pt;
        color: #1a1a1a;
        text-align: right;
    }

    .footer-left {
        position: absolute;
        bottom: 28mm;
        left: 52mm;
        font-size: 8pt;
        color: #444444;
        line-height: 1.7;
    }
</style>
</head>
<body>

    {{-- Background: gambar template ASPAPI --}}
    <img class="bg-template" src="data:image/jpeg;base64,{{ $templateBase64 }}" alt="">

    {{-- Nomor sertifikat --}}
    <p class="nomor">Nomor:&nbsp;&nbsp;{{ $certificate->certificate_number }}</p>

    {{-- Diberikan kepada --}}
    <p class="diberikan-label">Diberikan kepada :</p>

    {{-- Nama penerima (merah bold, besar) --}}
    <p class="recipient-name">{{ $memberData->full_name }}</p>

    {{-- Kalimat partisipasi --}}
    <p class="atas-partisipasi">
        Atas Partisipasinya sebagai <strong>PESERTA</strong> dalam Kegiatan <strong>Webinar Series</strong>
        Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia (ASPAPI) dengan Tema:
    </p>

    {{-- Judul seminar --}}
    <p class="tema-value">{{ $enrollment->seminar->title }}</p>

    {{-- Kota & tanggal di kanan atas area tanda tangan --}}
    <p class="kota-tanggal">
        Surakarta,&nbsp;{{ \Carbon\Carbon::parse($certificate->issued_at)->translatedFormat('d F Y') }}
    </p>

    {{-- Info kiri bawah --}}
    <div class="footer-left">
        <p>No: {{ $certificate->certificate_number }}</p>
        <p>Nilai: {{ $certificate->score }}</p>
    </div>

</body>
</html>