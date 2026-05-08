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
{{-- Preview Kartu --}}
<div style="margin-bottom:1.5rem;">
    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Preview Kartu Anggota</p>

    {{-- KTA Preview --}}
    <div style="width:340px;height:204px;border-radius:8px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.15);position:relative;
                background:{{ $member->member_type === 'luar_biasa' ? 'linear-gradient(135deg,#8B1A1A,#C0392B)' : ($member->member_type === 'kehormatan' ? 'linear-gradient(135deg,#4A4A4A,#1A1A1A)' : 'linear-gradient(135deg,#1A5F9A,#2A7FC1)') }};">

        {{-- Top bar --}}
        <div style="position:absolute;top:0;inset-x:0;height:4px;background:linear-gradient(90deg,#E8B84B,#C0392B);"></div>

        {{-- Logo area --}}
        <div style="position:absolute;top:12px;left:12px;display:flex;align-items:center;gap:8px;">
            <img src="{{ asset('images/logo-aspapi.png') }}" style="height:28px;filter:brightness(0) invert(1);" onerror="this.style.display='none'"/>
            <div>
                <p style="color:#fff;font-size:0.55rem;font-weight:700;letter-spacing:0.06em;">KARTU TANDA ANGGOTA</p>
                <p style="color:rgba(255,255,255,0.7);font-size:0.45rem;letter-spacing:0.04em;margin-top:1px;">ASOSIASI SARJANA DAN PRAKTISI</p>
                <p style="color:rgba(255,255,255,0.7);font-size:0.45rem;letter-spacing:0.04em;">ADMINISTRASI PERKANTORAN INDONESIA</p>
            </div>
        </div>

        {{-- Foto --}}
        <div style="position:absolute;right:12px;top:12px;width:56px;height:72px;border:2px solid rgba(255,255,255,0.4);border-radius:3px;overflow:hidden;background:rgba(255,255,255,0.1);">
            @if ($member->photo)
            <img src="{{ Storage::url($member->photo) }}" style="width:100%;height:100%;object-fit:cover;"/>
            @else
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                <svg style="width:24px;height:24px;color:rgba(255,255,255,0.4);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            @endif
        </div>

        {{-- Info --}}
        <div style="position:absolute;bottom:0;left:0;right:0;padding:12px;background:rgba(0,0,0,0.25);">
            <p style="color:#E8B84B;font-size:0.55rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">
                {{ $member->member_type_label }}
            </p>
            <p style="color:#fff;font-size:0.85rem;font-weight:700;margin-top:2px;line-height:1.2;">
                {{ $member->full_name }}
            </p>
            <p style="color:rgba(255,255,255,0.7);font-size:0.55rem;margin-top:2px;">
                {{ $member->institution ?? 'ASPAPI' }}
            </p>
            <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-top:8px;">
                <div>
                    <p style="color:rgba(255,255,255,0.6);font-size:0.45rem;letter-spacing:0.08em;text-transform:uppercase;">No. Anggota</p>
                    <p style="color:#fff;font-size:0.7rem;font-weight:700;letter-spacing:0.12em;font-family:monospace;">
                        {{ $member->member_number }}
                    </p>
                </div>
                <div style="text-align:right;">
                    <p style="color:rgba(255,255,255,0.6);font-size:0.45rem;letter-spacing:0.08em;text-transform:uppercase;">Berlaku s.d.</p>
                    <p style="color:#fff;font-size:0.65rem;font-weight:700;">
                        {{ $member->active_until ? $member->active_until->format('d/m/Y') : now()->addYear()->format('d/m/Y') }}
                    </p>
                </div>
            </div>
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