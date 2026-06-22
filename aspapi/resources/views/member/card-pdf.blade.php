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

    .photo-wrap {
        position: absolute;
        right: 12.5mm;
        top: 28mm;
        width: 14.5mm;
        height: 18.7mm;
        overflow: hidden;
        border-radius: 12px;
        border: 1px solid #b0bac5;
    }
    .photo-wrap img {
        width: 16.6mm;
        height: 20.7mm;
        display: block;
        margin-left: -1mm;
    }

    .photo-placeholder {
        width: 100%;
        height: 100%;
        background: #b0bac5;
        display: block;
    }

    .qr-wrap {
        position: absolute;
        left: 7.3mm;
        top: 30.1mm;
        width: 14.5mm;
        height: 14.5mm;
        overflow: hidden;
    }
    .qr-wrap img {
        width: 14.5mm;
        height: 14.5mm;
        display: block;
    }

    /* Nama — right dibatasi agar tidak tabrak foto */
    .member-name {
        position: absolute;
        top: 32.5mm;
        left: 24.9mm;
        right: 18.7mm;   /* batas kanan = lebar foto + margin */
        font-size: 5.5pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.25;
        word-wrap: break-word;
        overflow: hidden;
    }

    /* Nama pendek (≤28 karakter): 1 baris, font sedikit lebih besar */
    .member-name.short {
        font-size: 6pt;
        top: 34.2mm;
        line-height: 1.1;
    }

    .member-nia {
        position: absolute;
        left: 24.9mm;
        right: 18.7mm;
        font-size: 5.5pt;
        font-weight: 400;
        color: #0D2240;
        letter-spacing: 0.06em;
        line-height: 1.1;
    }

    .member-nia.short {
        font-size: 6pt;
    }

    .member-valid {
        position: absolute;
        top: 41.9mm;
        left: 0;
        right: 0;
        font-size: 4pt;
        font-weight: 700;
        color: #ffffff;
        padding: 1mm 7.3mm 1mm 24.9mm;
        display: block;
        white-space: nowrap;
        line-height: 1.4;
        text-align: left;
    }

    </style>
</head>
<body>

@php
    $displayName = $member->display_name;
    $isLong      = mb_strlen($displayName) > 28;
    $nameClass   = $isLong ? 'member-name' : 'member-name short';
    $niaClass    = $isLong ? 'member-nia' : 'member-nia short';
    // top untuk NIA: jika nama panjang (2 baris ~2×1.25×5.5pt ≈ 4.5mm), NIA lebih bawah
    $niaTop      = $isLong ? '37.5mm' : '36.8mm';
@endphp

    {{-- SISI DEPAN --}}
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

        <div class="{{ $nameClass }}">{{ $displayName }}</div>
        <div class="{{ $niaClass }}" style="top:{{ $niaTop }};">NIA. {{ $member->member_number }}</div>
        <div class="member-valid">Berlaku Sampai: {{ $member->active_until
            ? $member->active_until->translatedFormat('d F Y')
            : now()->addYear()->translatedFormat('d F Y') }}</div>

    </div>

    {{-- SISI BELAKANG --}}
    <div class="card">
        @if($backBase64)
        <img class="card-bg" src="{{ $backBase64 }}" alt=""/>
        @endif
    </div>

</body>
</html>