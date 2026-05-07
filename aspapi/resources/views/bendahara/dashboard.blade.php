@extends('layouts.bendahara')
@php $title = 'Dashboard Bendahara'; @endphp

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #E8B84B;">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;">{{ $pendingPayments }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.25rem;">Pembayaran Pending</p>
        <a href="{{ route('bendahara.payments', ['status' => 'pending']) }}" style="font-size:0.7rem;color:#2A7FC1;font-weight:700;text-decoration:none;margin-top:0.5rem;display:block;">Lihat →</a>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #C0392B;">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;">{{ $pendingBatches }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.25rem;">Batch Kolektif Pending</p>
        <a href="{{ route('bendahara.batches', ['status' => 'pending']) }}" style="font-size:0.7rem;color:#2A7FC1;font-weight:700;text-decoration:none;margin-top:0.5rem;display:block;">Lihat →</a>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #2A7FC1;">
        <p style="font-size:1.5rem;font-family:'DM Serif Display',serif;color:#1A2A3A;">Rp {{ number_format($totalVerified, 0, ',', '.') }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.25rem;">Total Pembayaran Terverifikasi</p>
    </div>
</div>

<div style="display:flex;gap:0.75rem;">
    <a href="{{ route('bendahara.payments') }}"
       style="padding:0.75rem 1.25rem;background:#2A7FC1;color:#fff;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        Kelola Pembayaran Mandiri
    </a>
    <a href="{{ route('bendahara.batches') }}"
       style="padding:0.75rem 1.25rem;background:#fff;border:1.5px solid #2A7FC1;color:#2A7FC1;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        Kelola Batch Kolektif
    </a>
</div>

@endsection