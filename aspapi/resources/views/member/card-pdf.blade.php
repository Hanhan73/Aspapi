<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #fff;
        font-family: Arial, sans-serif;
    }

    @page {
        size: 85.6mm 53.98mm landscape;
        margin: 0;
    }

    /* ── CR80 landscape: 85.6mm × 53.98mm ─────────────────────── */
    .card {
        width: 85.6mm;
        height: 53.98mm;
        position: relative;
        overflow: hidden;
        page-break-after: always;
        page-break-inside: avoid;
    }

    /* Background gambar mengisi seluruh kartu */
    .card-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 85.6mm;
        height: 53.98mm;
        display: block;
    }

    /* ── SISI DEPAN ──────────────────────────────────────────────── */

    /* Foto anggota: pojok kanan, tidak terlalu ke bawah */
    .photo-box {
        position: absolute;
        top: 10mm;
        right: 3mm;
        width: 14mm;
        height: 18mm;
        overflow: hidden;
        background: #eee;
    }

    .photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
    }

    /* QR Code */
    .qr-box {
        position: absolute;
        bottom: 4mm;
        right: 3mm;
        width: 12mm;
        height: 12mm;
    }

    .qr-box img {
        width: 100%;
        height: 100%;
    }

    /* Nama anggota */
    .member-name {
        position: absolute;
        top: 28mm;
        left: 20mm;
        right: 20mm;
        font-family: Arial, sans-serif;
        font-size: 7pt;
        font-weight: 900;
        color: #1A2A3A;
        letter-spacing: 0.02em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* NIA */
    .member-nia {
        position: absolute;
        top: 33mm;
        left: 20mm;
        right: 20mm;
        font-family: Arial, sans-serif;
        font-size: 5.5pt;
        font-weight: 700;
        color: #1A2A3A;
    }

    /* Masa berlaku — kotak merah, teks putih */
    .member-valid {
        position: absolute;
        top: 37.5mm;
        left: 20mm;
        font-family: Arial, sans-serif;
        font-size: 5pt;
        font-weight: 700;
        color: #fff;
        background: #C0392B;
        padding: 1mm 2mm;
        display: inline-block;
    }
    </style>
</head>

<body>

    {{-- ════ SISI DEPAN ════ --}}
    <div class="card">
        <img class="card-bg"
            src="data:image/png;base64,{{ $frontBase64 }}"
            alt="" />

        {{-- Foto anggota --}}
        <div class="photo-box">
            @if (!empty($photoBase64))
                <img src="data:image/jpeg;base64,{{ $photoBase64 }}" />
            @endif
        </div>

        {{-- QR Code --}}
        <div class="qr-box">
            @if (!empty($qrBase64))
                <img src="data:image/png;base64,{{ $qrBase64 }}" />
            @endif
        </div>

        {{-- Nama --}}
        <div class="member-name">{{ strtoupper($member->full_name) }}</div>

        {{-- NIA --}}
        <div class="member-nia">NIA. {{ $member->member_number }}</div>

        {{-- Berlaku sampai --}}
        <div class="member-valid">
            Berlaku s/d: {{ $member->active_until
                ? $member->active_until->format('d M Y')
                : now()->addYear()->format('d M Y') }}
        </div>
    </div>

    {{-- ════ SISI BELAKANG ════ --}}
    <div class="card">
        <img class="card-bg"
            src="data:image/png;base64,{{ $backBase64 }}"
            alt="" />
    </div>

</body>
</html>