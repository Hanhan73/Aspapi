<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>

    @page {
        size: 85.6mm 48.98mm;
        margin: 0;
    }

    html, body {
        margin: 0;
        padding: 0;
        width: 85.6mm;
        height: 48.98mm;
        overflow: hidden;
        font-family: Arial, Helvetica, sans-serif;
    }

    * { box-sizing: border-box; }

    .card {
        position: relative;
        width: 85.6mm;
        height: 48.98mm;
        overflow: hidden;
        page-break-after: always;
    }
    .card:last-child { page-break-after: avoid; }

    .card-bg {
        position: absolute;
        top: 0; left: 0;
        width: 85.6mm;
        height: 48.98mm;
        display: block;
    }

    /* ══════════════════════════════════════════════════════════════════
       KOORDINAT PIXEL-AKURAT dari referensi desain PDF
       
       Berdasarkan analisis PDF referensi:
       - Kartu ukuran: 85.6mm x 48.98mm
       - Foto: pojok kanan, mulai ~top 27mm, right ~2mm, lebar ~14mm
       - QR:  left ~7mm, bottom area ~31mm
       - Nama: left ~28mm, top ~31mm (sejajar dengan QR tengah)
       - NIA:  left ~28mm, top ~36mm
       - Berlaku: full width strip merah, top ~42mm
       ══════════════════════════════════════════════════════════════════ */

    /* ── Foto: pojok kanan atas, ukuran lebih besar ─────────────────── */
    .photo-wrap {
        position: absolute;
        right: 15mm;
        top: 27mm;
        width: 14mm;
        height: 18mm;
        overflow: hidden;
        border-radius: 10px;
        border: 1px solid #b0bac5;
    }
    .photo-wrap img {
        width: 16mm;
        height: 19mm;
        display: block;
        margin-left: -1mm;
        margin-top: -1mm;
    

    .photo-placeholder {
        width: 100%;
        height: 100%;
        background: #b0bac5;
        display: block;
    }

    /* ── QR: kiri bawah, sejajar tengah dengan nama ─────────────────── */
    .qr-wrap {
        position: absolute;
        left: 7mm;
        top: 29mm;
        width: 14mm;
        height: 14mm;
        overflow: hidden;
    }
    .qr-wrap img {
        width: 14mm;
        height: 14mm;
        display: block;
    }

    /* ── Nama: sejajar horizontal dengan QR tengah ──────────────────── */
    .member-name {
        position: absolute;
        top: 33mm;
        left: 24mm;
        right: 18mm;
        font-size: 6pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── NIA: di bawah nama ──────────────────────────────────────────── */
    .member-nia {
        position: absolute;
        top: 35.5mm;
        left: 24mm;
        right: 18mm;
        font-size: 6pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.06em;
        line-height: 1.1;
    }

    /* ── Strip merah "Berlaku Sampai" ────────────────────────────────── */
    .member-valid {
        position: absolute;
        top: 39.5mm;
        left: 0;
        right: 0;
        font-size: 4pt;
        font-weight: 700;
        color: #ffffff;
        padding: 1mm 7mm 1mm 24mm;
        display: block;
        white-space: nowrap;
        line-height: 1.4;
        text-align: left;
    }

    </style>
</head>
<body>

    {{-- ════════════════ SISI DEPAN ════════════════ --}}
    <div class="card">

        @if($frontBase64)
        <img class="card-bg" src="{{ $frontBase64 }}" alt=""/>
        @endif

        {{-- Foto: kanan atas, ukuran 14x18mm --}}
        <div class="photo-wrap">
            @if($photoBase64)
                <img src="{{ $photoBase64 }}" alt=""/>
            @else
                <span class="photo-placeholder"></span>
            @endif
        </div>

        {{-- QR: kiri bawah, 14mm --}}
        @if($qrBase64)
        <div class="qr-wrap">
            <img src="{{ $qrBase64 }}" alt="QR"/>
        </div>
        @endif

        {{-- Nama: setelah QR, sejajar --}}
        <div class="member-name">{{ strtoupper($member->full_name) }}</div>

        {{-- NIA --}}
        <div class="member-nia">NIA. {{ $member->member_number }}</div>

        {{-- Berlaku: strip merah full-width --}}
        <div class="member-valid">Berlaku Sampai: {{ $member->active_until
            ? $member->active_until->translatedFormat('d F Y')
            : now()->addYear()->translatedFormat('d F Y') }}</div>

    </div>

    {{-- ════════════════ SISI BELAKANG ════════════════ --}}
    <div class="card">
        @if($backBase64)
        <img class="card-bg" src="{{ $backBase64 }}" alt=""/>
        @endif
    </div>

</body>
</html>