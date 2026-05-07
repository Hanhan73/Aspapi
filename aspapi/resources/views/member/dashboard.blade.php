@extends('layouts.member')
@php $title = 'Dashboard'; @endphp

@section('content')

@php
    $steps = [
        ['done' => true,                                         'label' => 'Buat Akun',           'desc' => 'Akun berhasil dibuat'],
        ['done' => $member?->biodata_status === 'verified',     'label' => 'Verifikasi Biodata',  'desc' => 'Lengkapi biodata, tunggu verifikasi Admin'],
        ['done' => $member?->dues_paid,                         'label' => 'Pembayaran',          'desc' => 'Upload bukti transfer ke BNI 1661531545'],
        ['done' => (bool)$member?->member_number,               'label' => 'Generate Kartu',      'desc' => 'Generate dan download KTA'],
    ];
@endphp

{{-- Progress Steps --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;margin-bottom:1.5rem;">
    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1rem;">Progress Keanggotaan</p>
    <div style="display:flex;gap:0;position:relative;">
        @foreach ($steps as $i => $step)
        <div style="flex:1;text-align:center;position:relative;">
            {{-- Line --}}
            @if (!$loop->last)
            <div style="position:absolute;top:16px;left:50%;right:-50%;height:2px;background:{{ $step['done'] ? '#2A7FC1' : '#D6E8F7' }};z-index:0;"></div>
            @endif
            {{-- Circle --}}
            <div style="width:32px;height:32px;border-radius:50%;margin:0 auto;display:flex;align-items:center;justify-content:center;position:relative;z-index:1;
                {{ $step['done'] ? 'background:#2A7FC1;border:2px solid #2A7FC1;' : 'background:#fff;border:2px solid #D6E8F7;' }}">
                @if ($step['done'])
                <svg style="width:14px;height:14px;" fill="none" stroke="#fff" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
                @else
                <span style="font-size:0.75rem;font-weight:700;color:#B0CCDF;">{{ $i + 1 }}</span>
                @endif
            </div>
            <p style="font-size:0.72rem;font-weight:700;color:{{ $step['done'] ? '#1A2A3A' : '#B0CCDF' }};margin-top:0.5rem;">{{ $step['label'] }}</p>
            <p style="font-size:0.65rem;color:#B0CCDF;margin-top:0.125rem;line-height:1.4;">{{ $step['desc'] }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- Status Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">

    {{-- Status Biodata --}}
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid
        {{ $member?->biodata_status === 'verified' ? '#276749' : ($member?->biodata_status === 'rejected' ? '#C0392B' : '#E8B84B') }};">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Status Biodata</p>
        <p style="font-size:1.5rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.375rem;">
            {{ $member?->biodata_status === 'verified' ? 'Terverifikasi' : ($member?->biodata_status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
        </p>
        @if ($member?->biodata_status === 'rejected')
        <p style="font-size:0.72rem;color:#C0392B;margin-top:0.25rem;">{{ $member?->biodata_reject_reason }}</p>
        @endif
    </div>

    {{-- Status Pembayaran --}}
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid {{ $member?->dues_paid ? '#276749' : '#E8B84B' }};">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Status Pembayaran</p>
        <p style="font-size:1.5rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.375rem;">
            {{ $member?->dues_paid ? 'Lunas' : 'Belum Lunas' }}
        </p>
        <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.25rem;">
            {{ $member?->registration_type === 'baru' ? 'Uang Pangkal Rp 250.000' : 'Iuran Tahunan Rp 120.000' }}
        </p>
    </div>

    {{-- Nomor Anggota --}}
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #2A7FC1;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Nomor Anggota</p>
        <p style="font-size:1.25rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.375rem;letter-spacing:0.08em;">
            {{ $member?->member_number ?? '—' }}
        </p>
        <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.25rem;">
            {{ $member?->member_number ? '11 digit' : 'Belum digenerate' }}
        </p>
    </div>

    {{-- Jenis Anggota --}}
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #2A7FC1;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Jenis Anggota</p>
        <p style="font-size:1.25rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.375rem;">
            {{ $member?->member_type_label ?? '—' }}
        </p>
        <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.25rem;">
            {{ $member?->registration_type === 'lama' ? 'Anggota Lama' : 'Anggota Baru' }}
        </p>
    </div>
</div>

{{-- Quick Actions --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;margin-bottom:1.5rem;">
    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Aksi Cepat</p>
    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
        <a href="{{ route('member.biodata') }}"
           style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 1rem;background:#EEF4FB;color:#2A7FC1;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;text-decoration:none;">
            Lengkapi Biodata
        </a>
        @if ($member?->biodata_status === 'verified')
        <a href="{{ route('member.payment') }}"
           style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 1rem;background:#EEF4FB;color:#2A7FC1;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;text-decoration:none;">
            Upload Bukti Bayar
        </a>
        @endif
        @if ($member?->canGenerateCard())
        <a href="{{ route('member.card') }}"
           style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 1rem;background:#2A7FC1;color:#fff;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;text-decoration:none;">
            Generate Kartu Anggota
        </a>
        @endif
    </div>
</div>

{{-- Riwayat Pembayaran --}}
@if ($payments && $payments->count() > 0)
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;">
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;">Riwayat Pembayaran</p>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jenis</th>
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jumlah</th>
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status</th>
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $pay)
            <tr style="border-bottom:1px solid #F8FAFC;">
                <td style="padding:0.75rem 1rem;font-size:0.825rem;color:#1A2A3A;">{{ $pay->type_label }}</td>
                <td style="padding:0.75rem 1rem;font-size:0.825rem;color:#1A2A3A;font-weight:600;">{{ $pay->amount_formatted }}</td>
                <td style="padding:0.75rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $pay->status === 'verified' ? 'background:#F0FFF4;color:#276749;' : ($pay->status === 'rejected' ? 'background:#FDECEA;color:#C0392B;' : 'background:#FEF8EC;color:#B8860B;') }}">
                        {{ $pay->status === 'verified' ? 'Terverifikasi' : ($pay->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                    </span>
                </td>
                <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;">{{ $pay->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection