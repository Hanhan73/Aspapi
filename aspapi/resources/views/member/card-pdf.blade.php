<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<style>
/* ── Reset ───────────────────────────────────────────────────── */
* { margin: 0; padding: 0; box-sizing: border-box; }

/*
  Kunci 2 halaman di DomPDF:
  - body height = TEPAT 2 × tinggi kartu
  - Tidak ada overflow → tidak ada halaman ke-3 kosong
*/
html, body {
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
    page-break-after: always;
    page-break-inside: avoid;
}
.card:last-child { page-break-after: auto; }

/* Background image — isi penuh tanpa stretch */
.card-bg {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    display: block;
    /*
      DomPDF tidak support object-fit pada <img>,
      tapi background via <img> yang di-stretch biasanya
      sudah OK karena gambar template memang landscape CR80.
      Pastikan file kta-depan.png beraspek rasio 85.6:53.98 ≈ 1.586:1
      agar tidak tampak stretch.
    */
}

/* ─────────── SISI DEPAN ─────────── */

/* Foto — pojok KANAN atas, tidak stretch */
.photo-box {
    position: absolute;
    top:   14mm;       /* sesuai posisi di desain asli */
    right:  3mm;
    width:  14mm;
    height: 18mm;
    overflow: hidden;
    background: #ccc;
}
/*
  DomPDF tidak support object-fit.
  Agar foto tidak stretch:
  - Tampilkan sebagai background-image pada <div>
  - Gunakan background-size: cover; background-position: top center;
  Tapi DomPDF juga tidak support background-size sepenuhnya.
  Solusi terbaik: crop gambar ke rasio 14:18 = 7:9 sebelum upload.
  Jika tidak bisa, gunakan width:100%; height:auto dan sembunyikan overflow.
*/
.photo-box img {
    width: 100%;
    height: auto;       /* tidak stretch: lebar penuh, tinggi proporsional */
    display: block;
}

/* QR Code — pojok KIRI bawah */
.qr-box {
    position: absolute;
    bottom: 3.5mm;
    left:   2.5mm;
    width:  14mm;
    height: 14mm;
    overflow: hidden;
}
.qr-box img {
    width:  100%;
    height: 100%;
    /* QR biasanya kotak jadi tidak distorsi */
}

/* Nama */
.member-name {
    position: absolute;
    bottom: 14mm;
    left:   19mm;
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
    bottom: 10.5mm;
    left:   19mm;
    right:  19mm;
    font-size: 5.5pt;
    font-weight: 700;
    color: #1A2A3A;
}

/* Masa berlaku — KOTAK MERAH */
.member-valid {
    position: absolute;
    bottom: 4mm;
    left:   19mm;
    font-size: 5pt;
    font-weight: 700;
    color: #fff;
    background: #C0392B;
    padding: 0.8mm 2mm;
    white-space: nowrap;
}
</style>
</head>
<body>

{{-- ════════════ SISI DEPAN ════════════ --}}
<div class="card">

    {{-- Background (embed base64 agar DomPDF bisa render tanpa akses filesystem remote) --}}
    @if ($frontBase64)
        <img class="card-bg" src="{{ $frontBase64 }}" alt=""/>
    @else
        {{-- Fallback: gradasi biru kalau file belum ada --}}
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,#1A5F9A,#2A7FC1);"></div>
    @endif

    {{-- Foto --}}
    <div class="photo-box">
        @if ($photoBase64)
            <img src="{{ $photoBase64 }}" alt="Foto"/>
        @endif
    </div>

    {{-- QR --}}
    @if ($qrBase64)
    <div class="qr-box">
        <img src="{{ $qrBase64 }}" alt="QR"/>
    </div>
    @endif

    {{-- Nama --}}
    <div class="member-name">{{ strtoupper($member->full_name) }}</div>

    {{-- NIA --}}
    <div class="member-nia">NIA. {{ $member->member_number }}</div>

    {{-- Masa berlaku — kotak merah --}}
    <div class="member-valid">
        Berlaku s.d.:
        {{ $member->active_until
            ? $member->active_until->format('d M Y')
            : now()->addYear()->format('d M Y') }}
    </div>

</div>

{{-- ════════════ SISI BELAKANG ════════════ --}}
<div class="card">

    @if ($backBase64)
        <img class="card-bg" src="{{ $backBase64 }}" alt=""/>
    @else
        <div style="position:absolute;inset:0;background:#1A2A3A;"></div>
    @endif

</div>

</body>
</html>