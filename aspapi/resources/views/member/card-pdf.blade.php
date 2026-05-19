<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <style>

    @page {
        size: 88.9mm 50.8mm;
        margin: 0;
    }

    html, body {
        margin: 0;
        padding: 0;
        width: 88.9mm;
        height: 50.8mm;
        overflow: hidden;
        font-family: Arial, Helvetica, sans-serif;
    }

    * { box-sizing: border-box; }

    .card {
        position: relative;
        width: 88.9mm;
        height: 50.8mm;
        overflow: hidden;
        page-break-after: always;
    }
    .card:last-child { page-break-after: avoid; }

    .card-bg {
        position: absolute;
        top: 0; left: 0;
        width: 88.9mm;
        height: 50.8mm;
        display: block;
    }

    /* ── Foto: pojok kanan atas ─────────────────────────────────────── */
    .photo-wrap {
        position: absolute;
        right: 12.5mm;   /* 12 × 1.03855 */
        top: 28mm;       /* 27 × 1.03716 */
        width: 14.5mm;   /* 14 × 1.03855 */
        height: 18.7mm;  /* 18 × 1.03716 */
        overflow: hidden;
        border-radius: 12px;
        border: 1px solid #b0bac5;
    }
    .photo-wrap img {
        width: 16.6mm;   /* 16 × 1.03855 */
        height: 20.7mm;  /* 20 × 1.03716 */
        display: block;
        margin-left: -1mm;
    }

    .photo-placeholder {
        width: 100%;
        height: 100%;
        background: #b0bac5;
        display: block;
    }

    /* ── QR: kiri bawah ─────────────────────────────────────────────── */
    .qr-wrap {
        position: absolute;
        left: 7.3mm;     /* 7 × 1.03855 */
        top: 30.1mm;     /* 29 × 1.03716 */
        width: 14.5mm;   /* 14 × 1.03855 */
        height: 14.5mm;  /* 14 × 1.03716 */
        overflow: hidden;
    }
    .qr-wrap img {
        width: 14.5mm;
        height: 14.5mm;
        display: block;
    }

    /* ── Nama ────────────────────────────────────────────────────────── */
    .member-name {
        position: absolute;
        top: 34.2mm;     /* 33 × 1.03716 */
        left: 24.9mm;    /* 24 × 1.03855 */
        right: 18.7mm;   /* 18 × 1.03855 */
        font-size: 6pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── NIA ─────────────────────────────────────────────────────────── */
    .member-nia {
        position: absolute;
        top: 36.8mm;     /* 35.5 × 1.03716 */
        left: 24.9mm;    /* 24 × 1.03855 */
        right: 18.7mm;   /* 18 × 1.03855 */
        font-size: 6pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.06em;
        line-height: 1.1;
    }

    /* ── Strip merah "Berlaku Sampai" ────────────────────────────────── */
    .member-valid {
        position: absolute;
<<<<<<< Updated upstream
        top: 39.8mm;
=======
        top: 40.9mm;     /* 39.5 × 1.03716 */
>>>>>>> Stashed changes
        left: 0;
        right: 0;
        font-size: 4pt;
        font-weight: 700;
        color: #ffffff;
        padding: 1mm 7.3mm 1mm 24.9mm;  /* right: 7×1.03855, left: 24×1.03855 */
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

        <div class="photo-wrap">
            @if($photoBase64)
                <img src="{{ $photoBase64 }}" alt=""/>
            @else
                <span class="photo-placeholder"></span>
            @endif
        </div>

        @if($qrBase64)
        <div class="qr-wrap">
            <img src="{{ $qrBase64 }}" alt="QR"/>
        </div>
        @endif

        <div class="member-name">{{ strtoupper($member->full_name) }}</div>
        <div class="member-nia">NIA. {{ $member->member_number }}</div>
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