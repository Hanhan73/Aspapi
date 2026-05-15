<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<style>
/* ── Reset mutlak ───────────────────────────────────────────── */
* { margin: 0; padding: 0; box-sizing: border-box; }

html, body {
    /* tepat 2 kartu CR80 landscape, tidak ada sisa */
    width:  85.6mm;
    height: 107.96mm;   /* 2 × 53.98mm */
    overflow: hidden;
    background: #fff;
    font-family: Arial, sans-serif;
}

/* ── Kartu ─────────────────────────────────────────────────── */
.card {
    width:  85.6mm;
    height: 53.98mm;
    position: relative;
    overflow: hidden;
    display: block;
    /* page-break supaya halaman 2 mulai tepat di bawah */
    page-break-after: always;
    page-break-inside: avoid;
}
/* halaman terakhir tidak perlu break */
.card:last-child { page-break-after: auto; }

/* Background full-bleed */
.bg {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    display: block;
}

/* ══ SISI DEPAN ════════════════════════════════════════════ */

/* Foto — kanan atas */
.photo-box {
    position: absolute;
    top:   7mm;
    right: 3mm;
    width: 14mm;
    height: 18mm;
    overflow: hidden;
    background: #ccc;
    border: 0.4pt solid rgba(255,255,255,0.6);
}
.photo-box img {
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: top center;
}

/* QR Code — kiri bawah */
.qr-box {
    position: absolute;
    bottom: 3.5mm;
    left:   2.5mm;
    width:  14mm;
    height: 14mm;
}
.qr-box img {
    width: 100%; height: 100%;
    object-fit: contain;
}

/* Nama */
.member-name {
    position: absolute;
    bottom: 14mm;
    left:   20mm;
    right:  19mm;
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
    bottom: 10mm;
    left:   20mm;
    right:  19mm;
    font-size: 5.5pt;
    font-weight: 700;
    color: #1A2A3A;
}

/* Masa berlaku — kotak merah teks putih */
.member-valid {
    position: absolute;
    bottom: 3.5mm;
    left:   20mm;
    font-size: 4.5pt;
    font-weight: 700;
    color: #fff;
    background: #C0392B;
    padding: 0.6mm 1.5mm;
    border-radius: 0.8pt;
    white-space: nowrap;
}
</style>
</head>
<body>

{{-- ════ SISI DEPAN ════ --}}
<div class="card">

    {{-- Background (gunakan base64 agar DomPDF tidak fetch ke filesystem) --}}
    @if($frontBase64)
        <img class="bg" src="{{ $frontBase64 }}" alt=""/>
    @else
        {{-- Fallback gradasi biru jika file gambar tidak ada --}}
        <div style="position:absolute;top:0;left:0;width:100%;height:100%;
                    background:linear-gradient(135deg,#1A5F9A,#2A7FC1);"></div>
    @endif

    {{-- Foto --}}
    <div class="photo-box">
        @if($photoBase64)
            <img src="{{ $photoBase64 }}" alt="Foto"/>
        @endif
    </div>

    {{-- QR Code --}}
    @if($qrBase64)
    <div class="qr-box">
        <img src="{{ $qrBase64 }}" alt="QR"/>
    </div>
    @endif

    {{-- Nama --}}
    <div class="member-name">{{ strtoupper($member->full_name) }}</div>

    {{-- NIA --}}
    <div class="member-nia">NIA. {{ $member->member_number }}</div>

    {{-- Masa berlaku --}}
    <div class="member-valid">
        Berlaku s.d.:
        {{ $member->active_until
            ? $member->active_until->format('d M Y')
            : now()->addYear()->format('d M Y') }}
    </div>

</div>

{{-- ════ SISI BELAKANG ════ --}}
<div class="card">

    @if($backBase64)
        <img class="bg" src="{{ $backBase64 }}" alt=""/>
    @else
        <div style="position:absolute;top:0;left:0;width:100%;height:100%;
                    background:#1A2A3A;"></div>
    @endif

</div>

</body>
</html>