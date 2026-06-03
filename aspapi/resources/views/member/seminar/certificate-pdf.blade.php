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
        top: 0; left: 0;
        width: 297mm;
        height: 210mm;
    }

    /* Nomor — sejajar dengan label "Nomor:" di template */
    .nomor {
        position: absolute;
        top: 69mm;
        left: 65mm;
        font-size: 10pt;
        color: #1a1a1a;
    }

    /* "Diberikan kepada :" */
    .diberikan-label {
        position: absolute;
        top: 80mm;
        left: 65mm;
        font-size: 10pt;
        color: #1a1a1a;
    }

    /* Nama penerima */
    .recipient-name {
        position: absolute;
        top: 90mm;
        left: 65mm;
        right: 20mm;
        font-size: 28pt;
        font-weight: bold;
        color: #CC1A1A;
        line-height: 1.2;
        border-bottom: 0.5mm solid #941212;
        text-align: center;
    }

    /* Kalimat partisipasi */
    .atas-partisipasi {
        position: absolute;
        top: 112mm;
        left: 65mm;
        right: 20mm;
        font-size: 10pt;
        color: #1a1a1a;
        line-height: 1.6;
    }

    /* Judul seminar */
    .tema-value {
        position: absolute;
        top: 128mm;
        left: 65mm;
        right: 20mm;
        font-size: 11pt;
        font-weight: bold;
        color: #1a1a1a;
        line-height: 1.4;
    }

    /* Surakarta, tanggal — di atas nama penandatangan, tidak menutupi ttd */
    .kota-tanggal {
        position: absolute;
        bottom: 55mm;
        right: 58mm;
        font-size: 10pt;
        color: #1a1a1a;
        font-weight: normal;
        text-align: center;
    }
</style>
</head>
<body>

    {{-- Background: gambar template ASPAPI --}}
    <img class="bg-template" src="data:image/jpeg;base64,{{ $templateBase64 }}" alt="">

    {{-- Nomor sertifikat --}}
    <p class="nomor">Nomor: {{ $certificate->certificate_number }}</p>

    {{-- Diberikan kepada --}}
    <p class="diberikan-label">Diberikan kepada :</p>

    {{-- Nama penerima --}}
    <p class="recipient-name">{{ $memberData->full_name }}</p>

    {{-- Kalimat partisipasi --}}
    <p class="atas-partisipasi">
        Atas Partisipasinya sebagai <strong>PESERTA</strong> dalam Kegiatan <strong>Webinar Series</strong>
        Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia (ASPAPI) dengan Tema:
    </p>

    {{-- Judul seminar --}}
    <p class="tema-value">{{ $enrollment->seminar->title }}</p>

    {{-- Surakarta, tanggal --}}
    <p class="kota-tanggal">
        Surakarta,&nbsp;{{ \Carbon\Carbon::parse($certificate->issued_at)->locale('id')->translatedFormat('d F Y') }}
    </p>

</body>
</html>