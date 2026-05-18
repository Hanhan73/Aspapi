<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>

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
       KOORDINAT PIXEL-AKURAT dari referensi desain Rasto
       ══════════════════════════════════════════════════════════════════
       QR:      left=10.6mm, top=37.2mm, size=9mm
       Nama:    left=20.5mm, top=37.2mm   (sejajar QR)
       NIA:     left=20.5mm, top=41mm
       Berlaku: left=20.5mm, top=44.5mm
       Foto:    left=67mm,   top=28mm, w=14mm, h=18mm (crop)
       ══════════════════════════════════════════════════════════════════ */

    /* ── Foto: dikecilkan & digeser kiri, crop tengah ─────────────── */
    .photo-wrap {
        position: absolute;
        left: 67mm;
        top: 28mm;
        width: 14mm;
        height: 18mm;
        overflow: hidden;         /* crop jika foto terlalu besar/panjang */
        background: #c0c8d0;
    }
    .photo-wrap img {
        width: 14mm;              /* fixed width — tidak stretch horizontal */
        height: auto;             /* proporsional */
        display: block;
        /* Crop dari atas: foto pas foto biasanya wajah di atas */
    }
    .photo-placeholder {
        width: 100%;
        height: 100%;
        background: #b0bac5;
        display: block;
    }

    /* ── QR: dikecilkan agar tidak nutupin nama ────────────────────── */
    .qr-wrap {
        position: absolute;
        left: 10.6mm;
        top: 37.2mm;
        width: 9mm;
        height: 9mm;
        overflow: hidden;
    }
    .qr-wrap img {
        width: 9mm;
        height: 9mm;
        display: block;
    }

    /* ── Nama: mulai setelah QR (left=20.5mm) ─────────────────────── */
    .member-name {
        position: absolute;
        top: 37mm;
        left: 20.5mm;
        right: 16mm;              /* beri ruang foto kanan */
        font-size: 7.5pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── NIA ──────────────────────────────────────────────────────── */
    .member-nia {
        position: absolute;
        top: 41mm;
        left: 20.5mm;
        right: 16mm;
        font-size: 5.5pt;
        font-weight: 700;
        color: #0D2240;
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 0.04em;
        line-height: 1;
    }

    /* ── Berlaku: strip merah sesuai referensi ─────────────────────
       Di referensi: background merah penuh dari kiri, teks putih bold
       top=44.5mm, tinggi sekitar 4-5mm
       ──────────────────────────────────────────────────────────────── */
    .member-valid {
        position: absolute;
        top: 44.5mm;
        left: 17mm;              /* mulai sedikit dari kiri seperti referensi */
        right: 16mm;
        font-size: 5.5pt;
        font-weight: 700;
        color: #ffffff;
        background: #C0392B;
        padding: 0.9mm 2mm;
        display: block;
        white-space: nowrap;
        line-height: 1.2;
    }

    </style>
</head>
<body>

    {{-- ════════════════ SISI DEPAN ════════════════ --}}
    <div class="card">

        @if($frontBase64)
        <img class="card-bg" src="{{ $frontBase64 }}" alt=""/>
        @endif

        {{-- Foto: dikecilkan, crop dari atas --}}
        <div class="photo-wrap">
            @if($photoBase64)
                <img src="{{ $photoBase64 }}" alt=""/>
            @else
                <span class="photo-placeholder"></span>
            @endif
        </div>

        {{-- QR: dikecilkan 9mm, tidak nutupin nama --}}
        @if($qrBase64)
        <div class="qr-wrap">
            <img src="{{ $qrBase64 }}" alt="QR"/>
        </div>
        @endif

        {{-- Nama: mulai left=20.5mm, setelah QR --}}
        <div class="member-name">{{ strtoupper($member->full_name) }}</div>

        {{-- NIA --}}
        <div class="member-nia">NIA. {{ $member->member_number }}</div>

        {{-- Berlaku: strip merah sesuai referensi --}}
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