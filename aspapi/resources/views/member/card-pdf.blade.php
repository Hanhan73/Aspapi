<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>

    /* ══════════════════════════════════════════════════════════════════
       CR80 landscape: 85.6mm × 53.98mm
       2 halaman dalam 1 dokumen = body height 107.96mm
       ══════════════════════════════════════════════════════════════════ */
    @page {
        size: 85.6mm 53.98mm;
        margin: 0;
    }

    html, body {
        margin: 0;
        padding: 0;
        width: 85.6mm;
        height: 107.96mm;
        overflow: hidden;
        font-family: Arial, Helvetica, sans-serif;
    }

    * { box-sizing: border-box; }

    /* ── KARTU wrapper ──────────────────────────────────────────────── */
    .card {
        position: relative;
        width: 85.6mm;
        height: 53.98mm;
        overflow: hidden;
        page-break-after: always;
    }
    .card:last-child { page-break-after: avoid; }

    /* Background penuh */
    .card-bg {
        position: absolute;
        top: 0; left: 0;
        width: 85.6mm;
        height: 53.98mm;
        display: block;
    }

    /* ══════════════════════════════════════════════════════════════════
       POSISI ELEMEN — diukur dari gambar referensi (Rasto)
       Card: 85.6mm × 53.98mm
       ══════════════════════════════════════════════════════════════════ */

    /* ── Foto anggota ─────────────────────────────────────────────────
       Posisi: kanan, sejajar dengan nama & NIA
       ref: right≈2.4mm, top≈28.3mm, w≈12.2mm, h≈22mm
       Gunakan width=height agar tidak stretch:
       DomPDF tidak support object-fit, jadi kita crop via overflow hidden
       ──────────────────────────────────────────────────────────────── */
    .photo-wrap {
        position: absolute;
        top: 28.5mm;
        right: 18.5mm;
        width: 12.5mm;
        height: 16mm;        /* proporsi 3:4 (pas foto standar) */
        overflow: hidden;
        background: #c0c8d0;
    }
    .photo-wrap img {
        width: 12.5mm;       /* fixed width = container width */
        height: auto;        /* auto height — DomPDF respects this */
        display: block;
        min-height: 16mm;
    }
    .photo-placeholder {
        width: 100%;
        height: 100%;
        background: #b0bac5;
        display: block;
    }

    /* ── QR Code ──────────────────────────────────────────────────────
       ref: left≈3.4mm, top≈33.1mm, size≈12.1mm
       ──────────────────────────────────────────────────────────────── */
    .qr-wrap {
        position: absolute;
        left: 3.5mm;
        top: 33mm;
        width: 12mm;
        height: 12mm;
        overflow: hidden;
    }
    .qr-wrap img {
        width: 12mm;
        height: 12mm;
        display: block;
    }

    /* ── Nama anggota ─────────────────────────────────────────────────
       ref: left≈21.6mm, top≈34.0mm
       Sejajar horizontal dengan QR
       ──────────────────────────────────────────────────────────────── */
    .member-name {
        position: absolute;
        top: 33.5mm;
        left: 17mm;
        right: 16.5mm;       /* beri ruang untuk foto */
        font-size: 7pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── NIA ──────────────────────────────────────────────────────────
       ref: left≈21.6mm, top≈39.8mm
       ──────────────────────────────────────────────────────────────── */
    .member-nia {
        position: absolute;
        top: 39mm;
        left: 17mm;
        right: 16.5mm;
        font-size: 5.5pt;
        font-weight: 700;
        color: #0D2240;
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 0.05em;
    }

    /* ── Berlaku sampai — strip merah ─────────────────────────────────
       ref: left≈21.4mm, top≈43.5mm, tinggi≈5mm
       ──────────────────────────────────────────────────────────────── */
    .member-valid {
        position: absolute;
        top: 38mm;
        left: 17mm;
        right: 16.5mm;
        font-size: 5pt;
        font-weight: 700;
        color: #ffffff;
        background: #C0392B;
        padding: 0.8mm 2mm;
        display: block;
        white-space: nowrap;
        line-height: 1.2;
    }

    </style>
</head>
<body>

    {{-- ════════════════ SISI DEPAN ════════════════ --}}
    <div class="card">

        {{-- Background --}}
        @if($frontBase64)
        <img class="card-bg" src="{{ $frontBase64 }}" alt=""/>
        @endif

        {{-- Foto — kanan, sejajar nama & NIA --}}
        <div class="photo-wrap">
            @if($photoBase64)
                <img src="{{ $photoBase64 }}" alt=""/>
            @else
                <span class="photo-placeholder"></span>
            @endif
        </div>

        {{-- QR Code — kiri, sejajar nama --}}
        @if($qrBase64)
        <div class="qr-wrap">
            <img src="{{ $qrBase64 }}" alt="QR"/>
        </div>
        @endif

        {{-- Nama --}}
        <div class="member-name">{{ strtoupper($member->full_name) }}</div>

        {{-- NIA --}}
        <div class="member-nia">NIA. {{ $member->member_number }}</div>

        {{-- Berlaku s.d. --}}
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