<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
@font-face {
    font-family: 'Oswald';
    font-style: normal;
    font-weight: bold;
    src: url('{{ storage_path('fonts/Oswald-Bold.ttf') }}') format('truetype');
}

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
        top: 72mm;
        left: 55mm;
        font-size: 12pt;
        color: #1a1a1a;
    }

    /* "Diberikan kepada :" */
    .diberikan-label {
        position: absolute;
        top: 83mm;
        left: 55mm;
        font-size: 12pt;
        color: #1a1a1a;
    }

    /* Nama penerima — font-size & top dinamis dari controller */
    .recipient-name {
        position: absolute;
        top: {{ $nameTop }}mm;
        left: 55mm;
        right: 20mm;
        font-size: {{ $nameFontSize }}pt;
        font-weight: bolder;
        font-family: 'Oswald', sans-serif;
        color: #38B6FF;
        line-height: 1.2;
        text-align: left;
    }

    /* Kalimat partisipasi — top dinamis mengikuti panjang nama */
    .atas-partisipasi {
        position: absolute;
        top: {{ $atasPartisipasiTop }}mm;
        left: 55mm;
        right: 20mm;
        font-size: 12pt;
        color: #1a1a1a;
        line-height: 1.6;
    }

    /* Judul seminar — top dinamis mengikuti panjang nama */
    .tema-value {
        position: absolute;
        top: {{ $temaTop }}mm;
        left: 55mm;
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
        right: 55mm;
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
    <p class="recipient-name">{{ $memberData->full_name_with_title }}</p>

    {{-- Kalimat partisipasi --}}
    <p class="atas-partisipasi">
        atas partisipasinya sebagai PESERTA dalam Kegiatan Webinar Series Asosiasi Sarjana dan Praktisi Administrasi Perkantoran secara online (BISA-Online)
        dengan tema: {{ $enrollment->seminar->title }}
    </p>

    {{-- Surakarta, tanggal --}}
    <p class="kota-tanggal">
        Surakarta,&nbsp;{{ \Carbon\Carbon::parse($certificate->issued_at)->locale('id')->translatedFormat('d F Y') }}
    </p>

</body>
</html>