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

    /* Nilai nomor — di sebelah kanan label "Nomor:" yg sudah ada di template */
    .nomor {
        position: absolute;
        top: 65mm;
        left: 92mm;
        font-size: 10pt;
        color: #1a1a1a;
    }

    .diberikan-label {
        position: absolute;
        top: 77mm;
        left: 52mm;
        font-size: 10pt;
        color: #1a1a1a;
    }

    .recipient-name {
        position: absolute;
        top: 84mm;
        left: 52mm;
        right: 20mm;
        font-size: 20pt;
        font-weight: bold;
        color: #CC1A1A;
        line-height: 1.2;
    }

    .atas-partisipasi {
        position: absolute;
        top: 106mm;
        left: 52mm;
        right: 20mm;
        font-size: 10pt;
        color: #1a1a1a;
        line-height: 1.6;
    }

    .tema-value {
        position: absolute;
        top: 124mm;
        left: 52mm;
        right: 20mm;
        font-size: 11pt;
        font-weight: bold;
        color: #1a1a1a;
        line-height: 1.4;
    }

    /* Tanggal — di atas stempel, tidak menutupi ttd */
    .kota-tanggal {
        position: absolute;
        top: 148mm;
        left: 195mm;
        font-size: 9pt;
        color: #1a1a1a;
        text-align: center;
        width: 60mm;
    }
</style>
</head>
<body>

    <img class="bg-template" src="data:image/jpeg;base64,{{ $templateBase64 }}" alt="">

    {{-- Nomor: label sudah ada di template, tinggal isi nomornya --}}
    <p class="nomor">{{ $certificate->certificate_number }}</p>

    <p class="diberikan-label">Diberikan kepada :</p>

    <p class="recipient-name">{{ $memberData->full_name }}</p>

    <p class="atas-partisipasi">
        Atas Partisipasinya sebagai <strong>PESERTA</strong> dalam Kegiatan <strong>Webinar Series</strong>
        Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia (ASPAPI) dengan Tema:
    </p>

    <p class="tema-value">{{ $enrollment->seminar->title }}</p>

    <p class="kota-tanggal">
        Surakarta, {{ \Carbon\Carbon::parse($certificate->issued_at)->translatedFormat('d F Y') }}
    </p>

</body>
</html>