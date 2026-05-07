@extends('layouts.admin')
@php $title = 'Dashboard ' . $region->name; @endphp

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #2A7FC1;">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;">{{ $memberCount }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.25rem;">Total Anggota</p>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #276749;">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;">{{ $paidCount }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.25rem;">Sudah Bayar Iuran</p>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #C0392B;">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;">{{ $memberCount - $paidCount }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.25rem;">Belum Bayar</p>
    </div>
</div>

<div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
    <a href="{{ route('daerah.members') }}"
       style="padding:0.75rem 1.25rem;background:#fff;border:1.5px solid #2A7FC1;color:#2A7FC1;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        Lihat Data Anggota
    </a>
    <a href="{{ route('daerah.batch.form') }}"
       style="padding:0.75rem 1.25rem;background:#2A7FC1;color:#fff;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        + Daftar Anggota Baru (Batch)
    </a>
    <a href="{{ route('daerah.pay.form') }}"
       style="padding:0.75rem 1.25rem;background:#E8B84B;color:#1A2A3A;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        Bayar Iuran Kolektif
    </a>
</div>

@endsection