<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>

    /* ══════════════════════════════════════════════════════════════════
       CR80 landscape: 85.6mm × 53.98mm
       DomPDF: 2 halaman dalam 1 dokumen = total height 107.96mm
       ══════════════════════════════════════════════════════════════════ */
    @page {
        size: 85.6mm 53.98mm;
        margin: 0;
    }

    html, body {
        margin: 0;
        padding: 0;
        width: 85.6mm;
        height: 107.96mm; /* 2 × 53.98mm — kunci agar pas 2 halaman */
        overflow: hidden;
        font-family: Arial, Helvetica, sans-serif;
    }

    * {
        box-sizing: border-box;
    }

    /* ── KARTU (satu sisi = 1 halaman) ─────────────────────────────── */
    .card {
        position: relative;
        width: 85.6mm;
        height: 53.98mm;
        overflow: hidden;
        page-break-after: always;
    }

    .card:last-child {
        page-break-after: avoid;
    }

    /* Background penuh */
    .card-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 85.6mm;
        height: 53.98mm;
        display: block;
    }

    /* ══════════════════════════════════════════════════════════════════
       SISI DEPAN — posisi elemen di atas background kta-depan.png
       Sesuaikan top/left/right berdasarkan desain background Anda.
       Nilai di bawah adalah estimasi umum untuk layout KTA ASPAPI.
       ══════════════════════════════════════════════════════════════════ */

    /* Foto anggota — pojok kanan tengah */
    .photo-wrap {
        position: absolute;
        top: 13mm;
        right: 4mm;
        width: 16mm;
        height: 20mm;
        overflow: hidden;
        border: 0.3mm solid #ccc;
        background: #eee;
    }
    .photo-wrap img {
        width: 100%;
        height: 100%;
        /* DomPDF tidak support object-fit, gunakan width/height 100% */
        display: block;
    }
    .photo-placeholder {
        width: 100%;
        height: 100%;
        background: #dde;
        display: block;
    }

    /* QR Code — pojok kiri bawah */
    .qr-wrap {
        position: absolute;
        bottom: 3mm;
        left: 3mm;
        width: 14mm;
        height: 14mm;
    }
    .qr-wrap img {
        width: 14mm;
        height: 14mm;
        display: block;
    }

    /* Nama anggota */
    .member-name {
        position: absolute;
        top: 28mm;
        left: 20mm;
        right: 22mm;
        font-size: 6.5pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.03em;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* NIA */
    .member-nia {
        position: absolute;
        top: 33mm;
        left: 20mm;
        right: 22mm;
        font-size: 5pt;
        font-weight: 700;
        color: #0D2240;
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 0.05em;
    }

    /* Jenis anggota */
    .member-type {
        position: absolute;
        top: 37mm;
        left: 20mm;
        right: 22mm;
        font-size: 4.5pt;
        color: #4A6580;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    /* Berlaku sampai — kotak merah */
    .member-valid {
        position: absolute;
        bottom: 4mm;
        left: 20mm;
        right: 22mm;
        font-size: 4.5pt;
        font-weight: 700;
        color: #ffffff;
        background: #C0392B;
        padding: 0.6mm 1.5mm;
        display: inline-block;
        white-space: nowrap;
    }

    /* ══════════════════════════════════════════════════════════════════
       SISI BELAKANG — tidak ada elemen teks, hanya background
       ══════════════════════════════════════════════════════════════════ */

    </style>
</head>
<body>

    {{-- ════════════════ SISI DEPAN ════════════════ --}}
    <div class="card">

        {{-- Background depan --}}
        @if($frontBase64)
        <img class="card-bg" src="{{ $frontBase64 }}" alt=""/>
        @endif

        {{-- Foto anggota --}}
        <div class="photo-wrap">
            @if($photoBase64)
                <img src="{{ $photoBase64 }}" alt="Foto"/>
            @else
                <span class="photo-placeholder"></span>
            @endif
        </div>

        {{-- QR Code --}}
        @if($qrBase64)
        <div class="qr-wrap">
            <img src="{{ $qrBase64 }}" alt="QR"/>
        </div>
        @endif

        {{-- Nama --}}
        <div class="member-name">{{ strtoupper($member->full_name) }}</div>

        {{-- NIA --}}
        <div class="member-nia">NIA. {{ $member->member_number }}</div>

        {{-- Jenis anggota --}}
        <div class="member-type">{{ $member->member_type_label ?? 'Anggota Biasa' }}</div>

        {{-- Berlaku s.d. --}}
        <div class="member-valid">
            Berlaku s.d.:
            {{ $member->active_until
                ? $member->active_until->translatedFormat('d F Y')
                : now()->addYear()->translatedFormat('d F Y') }}
        </div>

    </div>

    {{-- ════════════════ SISI BELAKANG ════════════════ --}}
    <div class="card">

        {{-- Background belakang --}}
        @if($backBase64)
        <img class="card-bg" src="{{ $backBase64 }}" alt=""/>
        @endif

    </div>

</body>
</html>