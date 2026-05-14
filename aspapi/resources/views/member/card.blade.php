@extends('layouts.member')
@php $title = 'Kartu Anggota'; @endphp

@section('content')

@if (!$member?->canGenerateCard())
<div style="background:#FEF8EC;border-left:4px solid #E8B84B;border-radius:8px;padding:1.5rem;margin-bottom:1.5rem;">
    <p style="font-size:0.875rem;font-weight:700;color:#8B6914;margin-bottom:0.5rem;">Belum Bisa Generate Kartu</p>
    <p style="font-size:0.825rem;color:#8B6914;">Pastikan:</p>
    <ul style="font-size:0.825rem;color:#8B6914;margin-top:0.5rem;padding-left:1.25rem;">
        <li>Biodata sudah diverifikasi Admin</li>
        <li>{{ $member?->registration_type === 'baru' ? 'Uang pangkal sudah diverifikasi Bendahara' : 'Iuran tahunan sudah diverifikasi Bendahara' }}</li>
    </ul>
</div>
@endif

@if ($member?->member_number && $member?->canGenerateCard())

<p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Preview Kartu Anggota</p>

{{-- ────────────────────────────────────────────────────────────
     KTA PREVIEW — skala 2× dari ukuran cetak CR80 (85.6×53.98mm)
     Tampilan ini mencerminkan layout PDF yang akan didownload
──────────────────────────────────────────────────────────────── --}}
<div style="position:relative;width:342px;height:216px;border-radius:6px;overflow:hidden;
            box-shadow:0 8px 32px rgba(0,0,0,0.18);margin-bottom:1.5rem;">

    {{-- Background KTA (gambar asli dari public/images) --}}
    <img src="{{ asset('images/kta-depan.png') }}"
         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"
         onerror="this.style.display='none'"/>

    {{-- Fallback gradient kalau gambar belum ada --}}
    <div style="position:absolute;inset:0;background:linear-gradient(135deg,#1A5F9A 0%,#2A7FC1 100%);opacity:{{ $member->photo ? 0 : 1 }};z-index:0;"></div>

    {{-- ── Foto anggota — pojok kanan atas ── --}}
    <div style="position:absolute;top:40px;right:12px;width:56px;height:72px;
                border:2px solid rgba(255,255,255,0.5);overflow:hidden;background:#ccc;z-index:10;">
        @if ($member->photo)
            <img src="{{ Storage::url($member->photo) }}"
                 style="width:100%;height:100%;object-fit:cover;object-position:top center;"/>
        @else
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#dde;">
                <svg style="width:20px;height:20px;color:#999;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        @endif
    </div>

    {{-- ── Nama anggota ── --}}
    <div style="position:absolute;top:112px;left:80px;right:80px;z-index:10;">
        <p style="font-size:8.5pt;font-weight:900;color:#1A2A3A;letter-spacing:0.02em;
                  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            {{ strtoupper($member->full_name) }}
        </p>
    </div>

    {{-- ── NIA ── --}}
    <div style="position:absolute;top:132px;left:80px;right:80px;z-index:10;">
        <p style="font-size:6.5pt;font-weight:700;color:#1A2A3A;">
            NIA. {{ $member->member_number }}
        </p>
    </div>

    {{-- ── Masa berlaku — kotak merah teks putih ── --}}
    <div style="position:absolute;top:150px;left:80px;z-index:10;">
        <span style="display:inline-block;background:#C0392B;color:#fff;
                     font-size:5.5pt;font-weight:700;padding:2px 6px;">
            Berlaku s/d:
            {{ $member->active_until
                ? $member->active_until->format('d M Y')
                : now()->addYear()->format('d M Y') }}
        </span>
    </div>

    {{-- ── QR Code placeholder ── --}}
    <div style="position:absolute;bottom:12px;right:12px;width:48px;height:48px;
                background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);
                display:flex;align-items:center;justify-content:center;z-index:10;">
        <svg style="width:20px;height:20px;color:rgba(255,255,255,0.5);" fill="currentColor" viewBox="0 0 24 24">
            <path d="M3 3h7v7H3V3zm1 1v5h5V4H4zm1 1h3v3H5V5zm8-2h7v7h-7V3zm1 1v5h5V4h-5zm1 1h3v3h-3V5zM3 13h7v7H3v-7zm1 1v5h5v-5H4zm1 1h3v3H5v-3zm10 0h-2v-2h2v2zm0 2h-2v2h2v-2zm2-2v2h2v-2h-2zm0 4v-2h-2v2h2zm2 0h-2v2h2v-2zm0-6h-2v2h2v-2z"/>
        </svg>
    </div>
</div>

{{-- ── Download button ── --}}
<div style="display:flex;gap:0.75rem;align-items:center;">
    <a href="{{ route('member.card.download') }}"
       style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.75rem 1.5rem;
              background:#2A7FC1;color:#fff;border-radius:4px;font-size:0.75rem;
              font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Download KTA (PDF)
    </a>
    <p style="font-size:0.72rem;color:#B0CCDF;">Format CR80 siap cetak (85.6×53.98mm)</p>
</div>

@elseif ($member?->canGenerateCard())

{{-- Generate --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:2rem;text-align:center;">
    <svg style="width:48px;height:48px;color:#2A7FC1;margin:0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
    </svg>
    <h3 style="font-family:'DM Serif Display',serif;font-size:1.25rem;color:#1A2A3A;margin-bottom:0.5rem;">
        Siap Generate Kartu Anggota!
    </h3>
    <p style="font-size:0.875rem;color:#4A6580;margin-bottom:1.5rem;">
        Semua syarat terpenuhi. Klik tombol di bawah untuk membuat nomor anggota dan kartu Anda.
    </p>
    <form method="POST" action="{{ route('member.card.generate') }}">
        @csrf
        <button type="submit"
                style="padding:0.875rem 2rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;
                       font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
            Generate Kartu Anggota Saya
        </button>
    </form>
</div>

@endif

@endsection