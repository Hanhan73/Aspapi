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

    .card {
        position: relative;
        width: 85.6mm;
        height: 53.98mm;
        overflow: hidden;
        page-break-after: always;
    }
    .card:last-child { page-break-after: avoid; }

    .card-bg {
        position: absolute;
        top: 0; left: 0;
        width: 85.6mm;
        height: 53.98mm;
        display: block;
    }

    /* ══════════════════════════════════════════════════════════════════
       KOORDINAT DIUKUR PIXEL-AKURAT DARI GAMBAR REFERENSI
       Card: 85.6mm × 53.98mm
       ══════════════════════════════════════════════════════════════════

       Foto:    left=66.8mm, top=27.9mm, w=18.8mm, h=22.5mm
       QR:      left=2.4mm,  top=37.2mm, size=9.2mm
       Nama:    left=17.9mm, top=37.2mm
       NIA:     left=17.9mm, top=40.9mm
       Berlaku: left=17.9mm, top=44.5mm, h=5.2mm
       ══════════════════════════════════════════════════════════════════ */

    /* ── Foto anggota ─────────────────────────────────────────────────
       Posisi kanan atas area info, sejajar sedikit di atas nama
       DomPDF: width fixed, height auto untuk cegah stretch
       overflow hidden untuk crop jika foto terlalu panjang
       ──────────────────────────────────────────────────────────────── */
    .photo-wrap {
        position: absolute;
        left: 66.8mm;
        top: 27.9mm;
        width: 18.8mm;
        height: 22.5mm;
        overflow: hidden;
        background: #c8d0d8;
    }
    .photo-wrap img {
        width: 18.8mm;    /* fixed width — tidak stretch */
        height: auto;     /* auto height mengikuti proporsi asli */
        display: block;
    }
    .photo-placeholder {
        width: 100%;
        height: 100%;
        background: #b8c2cc;
        display: block;
    }

    /* ── QR Code ──────────────────────────────────────────────────────
       Sejajar dengan nama (sama top)
       ──────────────────────────────────────────────────────────────── */
    .qr-wrap {
        position: absolute;
        left: 2.4mm;
        top: 37.2mm;
        width: 9.2mm;
        height: 9.2mm;
        overflow: hidden;
    }
    .qr-wrap img {
        width: 9.2mm;
        height: 9.2mm;
        display: block;
    }

    /* ── Nama anggota ─────────────────────────────────────────────────
       Sejajar dengan QR (sama top), mulai setelah QR + gap
       ──────────────────────────────────────────────────────────────── */
    .member-name {
        position: absolute;
        top: 36.8mm;       /* sedikit di atas baseline QR agar teks terlihat */
        left: 17.9mm;
        right: 20mm;       /* beri ruang foto di kanan */
        font-size: 7.5pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── NIA ──────────────────────────────────────────────────────────
       Di bawah nama
       ──────────────────────────────────────────────────────────────── */
    .member-nia {
        position: absolute;
        top: 40.9mm;
        left: 17.9mm;
        right: 20mm;
        font-size: 5.5pt;
        font-weight: 700;
        color: #0D2240;
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 0.05em;
        line-height: 1;
    }

    /* ── Berlaku sampai — strip merah ─────────────────────────────────
       Di bawah NIA, tinggi ±5mm
       ──────────────────────────────────────────────────────────────── */
    .member-valid {
        position: absolute;
        top: 44.5mm;
        left: 17.9mm;
        right: 20mm;
        font-size: 5.5pt;
        font-weight: 700;
        color: #ffffff;
        background: #C0392B;
        padding: 1mm 2mm;
        display: block;
        white-space: nowrap;
        line-height: 1.3;
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

        {{-- Foto — kanan, di atas area info --}}
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