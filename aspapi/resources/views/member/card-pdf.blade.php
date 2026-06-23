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

    /* ── QR: pojok kanan atas ── */
    .qr-wrap {
        position: absolute;
        right: 2mm;
        top: 2mm;
        width: 13mm;
        height: 13mm;
        overflow: hidden;
    }
    .qr-wrap img {
        width: 13mm;
        height: 13mm;
        display: block;
    }

    /* ── Foto: kiri bawah, sejajar area identitas ── */
    .photo-wrap {
        position: absolute;
        left: 2.5mm;
        top: 18mm;
        width: 18mm;
        height: 22mm;
        overflow: hidden;
        border-radius: 3px;
        border: 1px solid #b0bac5;
    }
    .photo-wrap img {
        width: 18mm;
        height: 22mm;
        display: block;
    }

    .photo-placeholder {
        width: 100%;
        height: 100%;
        background: #b0bac5;
        display: block;
    }

    /* ── Nama ── */
    .member-name.single {
        position: absolute;
        top: 27mm;
        left: 23mm;
        right: 2mm;
        font-size: 6.5pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.1;
        overflow: hidden;
    }

    .member-name.double {
        position: absolute;
        top: 26mm;
        left: 23mm;
        right: 2mm;
        font-size: 6.5pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.3;
        overflow: hidden;
    }

    .member-name.triple {
        position: absolute;
        top: 25mm;
        left: 23mm;
        right: 2mm;
        font-size: 6.5pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.25;
        overflow: hidden;
    }

    /* ── NIA ── */
    .member-nia.single {
        position: absolute;
        top: 35mm;
        left: 23mm;
        right: 2mm;
        font-size: 6.5pt;
        font-weight: 700;
        color: #0D2240;
        letter-spacing: 0.06em;
        line-height: 1.1;
    }

    .member-nia.double {
        position: absolute;
        top: 37mm;
        left: 23mm;
        right: 2mm;
        font-size: 6.5pt;
        font-weight: 700;
        color: #0D2240;
        letter-spacing: 0.06em;
        line-height: 1.1;
    }

    .member-nia.triple {
        position: absolute;
        top: 38.5mm;
        left: 23mm;
        right: 2mm;
        font-size: 6.5pt;
        font-weight: 700;
        color: #0D2240;
        letter-spacing: 0.06em;
        line-height: 1.1;
    }

    /* ── Berlaku Sampai ── */
    .member-valid.single {
        position: absolute;
        top: 40mm;
        left: 23mm;
        right: 2mm;
        font-size: 6.5pt;
        font-weight: 700;
        color: #0D2240;
        line-height: 1.2;
    }

    .member-valid.double {
        position: absolute;
        top: 42mm;
        left: 23mm;
        right: 2mm;
        font-size: 6.5pt;
        font-weight: 700;
        color: #0D2240;
        line-height: 1.2;
    }

    .member-valid.triple {
        position: absolute;
        top: 44.5mm;
        left: 23mm;
        right: 2mm;
        font-size: 6.5pt;
        font-weight: 700;
        color: #0D2240;
        line-height: 1.2;
    }

    </style>
</head>
<body>

@php
    $cardLines = array_values(array_filter($member->card_name_lines));
    $lineCount = count($cardLines);
    $nameClass  = match($lineCount) {
        1       => 'member-name single',
        2       => 'member-name double',
        default => 'member-name triple',
    };
    $niaClass   = match($lineCount) {
        1       => 'member-nia single',
        2       => 'member-nia double',
        default => 'member-nia triple',
    };
    $validClass = match($lineCount) {
        1       => 'member-valid single',
        2       => 'member-valid double',
        default => 'member-valid triple',
    };
@endphp

    {{-- SISI DEPAN --}}
    <div class="card">

        @if($frontBase64)
        <img class="card-bg" src="{{ $frontBase64 }}" alt=""/>
        @endif

        {{-- QR: pojok kanan atas --}}
        @if($qrBase64)
        <div class="qr-wrap">
            <img src="{{ $qrBase64 }}" alt="QR"/>
        </div>
        @endif

        {{-- Foto: kiri bawah --}}
        <div class="photo-wrap">
            @if($photoBase64)
                <img src="{{ $photoBase64 }}" alt=""/>
            @else
                <span class="photo-placeholder"></span>
            @endif
        </div>

        {{-- Nama --}}
        <div class="{{ $nameClass }}">
            @foreach($cardLines as $line)
                <div>{{ $line }}</div>
            @endforeach
        </div>

        {{-- NIA --}}
        <div class="{{ $niaClass }}">NIA. {{ $member->member_number }}</div>

        {{-- Berlaku Sampai --}}
        <div class="{{ $validClass }}">Berlaku Sampai: {{ $member->active_until
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