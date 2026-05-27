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
        background: #ffffff;
        color: #1a2a3a;
        position: relative;
        overflow: hidden;
    }

    /* ── Background dekoratif ── */
    .bg-top-bar {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 18mm;
        background: #1B3A6B; /* navy */
    }
    .bg-bottom-bar {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 10mm;
        background: #1B3A6B;
    }
    .accent-left {
        position: absolute;
        top: 0; left: 0;
        width: 28mm;
        height: 210mm;
        background: #1B3A6B;
        opacity: 0.06;
    }
    .accent-right {
        position: absolute;
        top: 0; right: 0;
        width: 8mm;
        height: 210mm;
        background: #2563EB;
        opacity: 0.12;
    }
    .top-stripe {
        position: absolute;
        top: 18mm; left: 0; right: 0;
        height: 3mm;
        background: #2563EB;
    }

    /* ── Konten utama ── */
    .content {
        position: absolute;
        top: 25mm;
        left: 20mm;
        right: 15mm;
        bottom: 15mm;
    }

    .org-name {
        font-size: 8.5pt;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #2563EB;
        margin-bottom: 2mm;
    }

    .cert-title {
        font-size: 28pt;
        font-weight: bold;
        color: #1B3A6B;
        letter-spacing: 3px;
        text-transform: uppercase;
        line-height: 1;
        margin-bottom: 1mm;
    }

    .cert-subtitle {
        font-size: 9pt;
        color: #8A97A4;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 8mm;
    }

    .presented-to {
        font-size: 8pt;
        color: #8A97A4;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 2mm;
    }

    .recipient-name {
        font-size: 22pt;
        font-weight: bold;
        color: #1B3A6B;
        border-bottom: 0.5mm solid #2563EB;
        padding-bottom: 2mm;
        margin-bottom: 4mm;
        display: inline-block;
    }

    .seminar-label {
        font-size: 7.5pt;
        color: #8A97A4;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1.5mm;
    }

    .seminar-title {
        font-size: 12pt;
        font-weight: bold;
        color: #1a2a3a;
        margin-bottom: 6mm;
    }

    /* ── Footer row ── */
    .footer-row {
        position: absolute;
        bottom: 15mm;
        left: 20mm;
        right: 15mm;
        display: table;
        width: calc(297mm - 35mm);
    }
    .footer-col {
        display: table-cell;
        vertical-align: bottom;
    }
    .footer-col.center {
        text-align: center;
    }
    .footer-col.right {
        text-align: right;
    }

    .cert-number {
        font-size: 7pt;
        color: #8A97A4;
        letter-spacing: 0.5px;
    }
    .cert-score {
        font-size: 7.5pt;
        color: #8A97A4;
    }
    .cert-score strong {
        color: #1B3A6B;
        font-size: 9pt;
    }
    .cert-date {
        font-size: 7.5pt;
        color: #8A97A4;
    }

    /* Placeholder tanda tangan */
    .sign-area {
        display: inline-block;
        text-align: center;
    }
    .sign-line {
        width: 45mm;
        border-bottom: 0.4mm solid #1B3A6B;
        margin-bottom: 1.5mm;
        height: 12mm;
    }
    .sign-name {
        font-size: 7.5pt;
        font-weight: bold;
        color: #1B3A6B;
    }
    .sign-title {
        font-size: 6.5pt;
        color: #8A97A4;
    }

    /* Logo placeholder di header */
    .header-logo {
        position: absolute;
        top: 3mm;
        left: 20mm;
        color: white;
        font-size: 9pt;
        font-weight: bold;
        letter-spacing: 1.5px;
    }
    .header-right {
        position: absolute;
        top: 5mm;
        right: 15mm;
        color: rgba(255,255,255,0.6);
        font-size: 7pt;
        letter-spacing: 1px;
    }
</style>
</head>
<body>

    {{-- Background elements --}}
    <div class="bg-top-bar"></div>
    <div class="bg-bottom-bar"></div>
    <div class="accent-left"></div>
    <div class="accent-right"></div>
    <div class="top-stripe"></div>

    {{-- Header text --}}
    <div class="header-logo">ASPAPI</div>
    <div class="header-right">ASOSIASI SARJANA DAN PRAKTISI ADMINISTRASI PERKANTORAN INDONESIA</div>

    {{-- Konten utama --}}
    <div class="content">
        <p class="org-name">Asosiasi Sarjana dan Praktisi Administrasi Perkantoran Indonesia</p>
        <p class="cert-title">Sertifikat</p>
        <p class="cert-subtitle">Penyelesaian Seminar</p>

        <p class="presented-to">Diberikan kepada</p>
        <p class="recipient-name">{{ $memberData->full_name }}</p>

        <p class="seminar-label">Telah menyelesaikan seminar</p>
        <p class="seminar-title">{{ $enrollment->seminar->title }}</p>
    </div>

    {{-- Footer --}}
    <div class="footer-row">
        <div class="footer-col">
            <p class="cert-number">No: {{ $certificate->certificate_number }}</p>
            <p class="cert-date">Diterbitkan: {{ \Carbon\Carbon::parse($certificate->issued_at)->translatedFormat('d F Y') }}</p>
        </div>
        <div class="footer-col center">
            <div class="sign-area">
                <div class="sign-line"></div>
                <p class="sign-name">Ketua Umum ASPAPI</p>
                <p class="sign-title">[Nama Ketua]</p>
            </div>
        </div>
        <div class="footer-col right">
            <p class="cert-score">Nilai: <strong>{{ $certificate->score }}</strong></p>
        </div>
    </div>

</body>
</html>