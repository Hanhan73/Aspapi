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

    /* ── Nama: 1 baris ── */
    .member-name.single {
        position: absolute;
        top: 34.2mm;
        left: 24.9mm;
        right: 18.7mm;
        font-size: 6pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.1;
        overflow: hidden;
    }

    /* ── Nama: 2 baris ── */
    .member-name.double {
        position: absolute;
        top: 31.5mm;
        left: 24.9mm;
        right: 18.7mm;
        font-size: 5.5pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.3;
        overflow: hidden;
    }

    /* ── Nama: 3 baris ── */
    .member-name.triple {
        position: absolute;
        top: 29.5mm;
        left: 24.9mm;
        right: 18.7mm;
        font-size: 5pt;
        font-weight: 900;
        color: #0D2240;
        letter-spacing: 0.02em;
        line-height: 1.25;
        overflow: hidden;
    }

    /* ── NIA ── */
    .member-nia.single {
        position: absolute;
        top: 36.8mm;
        left: 24.9mm;
        right: 18.7mm;
        font-size: 6pt;
        font-weight: 400;
        color: #0D2240;
        letter-spacing: 0.06em;
        line-height: 1.1;
    }

    .member-nia.double {
        position: absolute;
        top: 37.8mm;
        left: 24.9mm;
        right: 18.7mm;
        font-size: 5.5pt;
        font-weight: 400;
        color: #0D2240;
        letter-spacing: 0.06em;
        line-height: 1.1;
    }

    .member-nia.triple {
        position: absolute;
        top: 38.8mm;
        left: 24.9mm;
        right: 18.7mm;
        font-size: 5pt;
        font-weight: 400;
        color: #0D2240;
        letter-spacing: 0.06em;
        line-height: 1.1;
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
    $cardLines = array_values(array_filter($member->card_name_lines));
    $lineCount = count($cardLines);
    $nameClass = match($lineCount) {
        1       => 'member-name single',
        2       => 'member-name double',
        default => 'member-name triple',
    };
    $niaClass  = match($lineCount) {
        1       => 'member-nia single',
        2       => 'member-nia double',
        default => 'member-nia triple',
    };
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

        <div class="{{ $nameClass }}">
            @foreach($cardLines as $line)
                <div>{{ $line }}</div>
            @endforeach
        </div>

        <div class="{{ $niaClass }}">NIA. {{ $member->member_number }}</div>

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