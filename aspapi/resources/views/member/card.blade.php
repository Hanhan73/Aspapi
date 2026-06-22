@extends('layouts.member')
@php $title = 'Kartu Anggota'; @endphp

@section('content')

@if (!$member?->canGenerateCard())
<div style="background:#FEF8EC;border-left:4px solid #E8B84B;border-radius:8px;padding:1.5rem;margin-bottom:1.5rem;">
    <p style="font-size:0.875rem;font-weight:700;color:#8B6914;margin-bottom:0.5rem;">Belum Bisa Generate Kartu</p>
    <p style="font-size:0.825rem;color:#8B6914;">Pastikan:</p>
    <ul style="font-size:0.825rem;color:#8B6914;margin-top:0.5rem;padding-left:1.25rem;">
        <li>Biodata sudah diverifikasi Admin ✓</li>
        <li>{{ $member?->registration_type === 'baru' ? 'Uang pangkal sudah diverifikasi Bendahara' : 'Iuran tahunan sudah diverifikasi Bendahara' }} ✓</li>
    </ul>
</div>
@endif

@if ($member?->member_number && $member?->canGenerateCard())

{{-- Preferensi Gelar --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
    <span style="font-size:0.75rem;font-weight:700;color:#4A6580;letter-spacing:0.05em;text-transform:uppercase;">Tampilan Nama di Kartu:</span>
    <form method="POST" action="{{ route('member.card.preference') }}" style="display:flex;gap:0.5rem;align-items:center;">
        @csrf
        <select name="show_title_on_card" onchange="this.form.submit()"
                style="padding:0.4rem 0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.8rem;color:#1A2A3A;outline:none;cursor:pointer;">
            <option value="1" {{ $member->show_title_on_card ? 'selected' : '' }}>Dengan Gelar</option>
            <option value="0" {{ !$member->show_title_on_card ? 'selected' : '' }}>Tanpa Gelar</option>
        </select>
    </form>
    <span style="font-size:0.72rem;color:#B0CCDF;">— Perubahan langsung tampil di preview</span>
</div>

{{-- Preview Kartu --}}
<div style="margin-bottom:1.5rem;">
    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Preview Kartu Anggota</p>

    @php
        $cardLines    = $member->card_name_lines;
        $isMultiLine  = count($cardLines) > 1;
        $nameFontSize = $isMultiLine ? '7.5px' : '9px';
        $nameTop      = $isMultiLine ? '119px' : '127px';
        $niaTop       = $isMultiLine ? '146px' : '141px';
    @endphp

    <div style="width:340px;height:194px;border-radius:6px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.18);position:relative;background:#ddeeff;">

        {{-- Background --}}
        <img src="{{ $frontBase64 ?? asset('images/kta-depan.png') }}"
             style="position:absolute;top:0;left:0;width:100%;height:100%;display:block;" alt=""/>

        {{-- Foto --}}
        <div style="position:absolute;right:48px;top:107px;width:54px;height:69px;overflow:hidden;border-radius:5px;border:1px solid #b0bac5;">
            @if ($member->photo)
                <img src="{{ Storage::url($member->photo) }}"
                     style="width:100%;height:100%;object-fit:cover;object-position:center top;display:block;" alt="foto"/>
            @else
                <div style="width:100%;height:100%;background:#b0bac5;display:flex;align-items:center;justify-content:center;">
                    <svg style="width:22px;height:22px;" fill="none" stroke="#fff" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Nama --}}
        <div style="position:absolute;top:{{ $nameTop }};left:90px;right:72px;
                    font-family:Arial,sans-serif;font-size:{{ $nameFontSize }};font-weight:900;
                    color:#0D2240;letter-spacing:0.02em;line-height:1.3;overflow:hidden;">
            @foreach($cardLines as $line)
                <div>{{ $line }}</div>
            @endforeach
        </div>

        {{-- NIA --}}
        <div style="position:absolute;top:{{ $niaTop }};left:90px;right:72px;
                    font-family:'Courier New',monospace;font-size:8px;font-weight:700;
                    color:#0D2240;letter-spacing:0.06em;line-height:1.2;">
            NIA. {{ $member->member_number }}
        </div>

        {{-- Strip Berlaku Sampai --}}
        <div style="position:absolute;bottom:0;left:0;right:0;
                    background-color:#C0272D;
                    font-family:Arial,sans-serif;font-size:8px;font-weight:700;
                    color:#fff;padding:4px 8px 4px 90px;
                    white-space:nowrap;line-height:1.4;">
            Berlaku Sampai: {{ $member->active_until
                ? $member->active_until->translatedFormat('d F Y')
                : now()->addYear()->translatedFormat('d F Y') }}
        </div>
    </div>
</div>

{{-- Download --}}
<div style="display:flex;gap:0.75rem;">
    <a href="{{ route('member.card.download') }}"
       style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.75rem 1.5rem;background:#2A7FC1;color:#fff;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        Download KTA (PDF)
    </a>
</div>

@elseif ($member?->canGenerateCard())

{{-- Generate --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:2rem;text-align:center;">
    <svg style="width:48px;height:48px;color:#2A7FC1;margin:0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
    </svg>
    <h3 style="font-family:'DM Serif Display',serif;font-size:1.25rem;color:#1A2A3A;margin-bottom:0.5rem;">Siap Generate Kartu Anggota!</h3>
    <p style="font-size:0.875rem;color:#4A6580;margin-bottom:1.5rem;">Semua syarat terpenuhi. Klik tombol di bawah untuk membuat nomor anggota dan kartu Anda.</p>
    <form method="POST" action="{{ route('member.card.generate') }}">
        @csrf
        <button type="submit"
                style="padding:0.875rem 2rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
            Generate Kartu Anggota Saya
        </button>
    </form>
</div>

@endif

@endsection