@extends('layouts.bendahara')
@php $title = 'Batch Kolektif'; @endphp

@section('content')

<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <select name="status" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">Filter</button>
        <a href="{{ route('bendahara.batches') }}" style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">Reset</a>
    </form>
</div>

<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Wilayah</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Diajukan Oleh</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jumlah Anggota</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Total</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tanggal</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($batches as $batch)
            <tr style="border-bottom:1px solid #EEF4FB;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                <td style="padding:0.875rem 1rem;">
                    <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0;">{{ $batch->region->name ?? '—' }}</p>
                </td>
                <td style="padding:0.875rem 1rem;">
                    <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0;">{{ $batch->submitter->name ?? '—' }}</p>
                    <p style="font-size:0.72rem;color:#B0CCDF;margin:0;">{{ $batch->submitter->email ?? '' }}</p>
                </td>
                <td style="padding:0.875rem 1rem;font-size:0.825rem;color:#4A6580;">{{ $batch->member_count }} anggota</td>
                <td style="padding:0.875rem 1rem;font-size:0.825rem;font-weight:700;color:#1A2A3A;">
                    Rp {{ number_format($batch->total_amount ?? 0, 0, ',', '.') }}
                </td>
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $batch->status === 'verified' ? 'background:#F0FFF4;color:#276749;' : ($batch->status === 'rejected' ? 'background:#FDECEA;color:#C0392B;' : 'background:#FEF8EC;color:#B8860B;') }}">
                        {{ $batch->status === 'verified' ? 'Terverifikasi' : ($batch->status === 'rejected' ? 'Ditolak' : 'Pending') }}
                    </span>
                </td>
                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;">{{ $batch->created_at->format('d M Y') }}</td>
                <td style="padding:0.875rem 1rem;">
                    @if ($batch->status === 'pending')
                    <form method="POST" action="{{ route('bendahara.batch.verify', $batch->id) }}">
                        @csrf
                        <button type="submit"
                                style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.5rem;background:#276749;color:#fff;border:none;border-radius:3px;cursor:pointer;">
                            Verifikasi Semua
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">Tidak ada data batch.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($batches->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $batches->links() }}</div>
@endif

@endsection