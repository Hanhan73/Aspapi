@extends('layouts.daerah')
@php $title = 'Riwayat Batch Pembayaran'; @endphp

@section('content')

{{-- ── Daftar Batch ── --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;margin:0;">
            Riwayat Batch Iuran Kolektif
        </p>
        <a href="{{ route('daerah.pay.form') }}"
           style="padding:0.4rem 0.875rem;background:#2A7FC1;color:#fff;border-radius:4px;font-size:0.7rem;font-weight:700;text-decoration:none;">
            + Ajukan Batch Baru
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#EEF4FB;">
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Tanggal</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Jumlah Anggota</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Total</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Status</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($batches as $batch)
                @php
                    $st = $batch->status;
                    $stStyle = $st === 'verified' ? 'background:#F0FFF4;color:#276749;'
                             : ($st === 'rejected' ? 'background:#FDECEA;color:#C0392B;'
                             : 'background:#FEF8EC;color:#B8860B;');
                    $stLabel = $st === 'verified' ? '✓ Terverifikasi'
                             : ($st === 'rejected' ? '✗ Ditolak' : '⏳ Menunggu');
                @endphp
                <tr style="border-bottom:1px solid #EEF4FB;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                    <td style="padding:0.75rem 1rem;font-size:0.82rem;color:#4A6580;white-space:nowrap;">
                        {{ $batch->created_at->format('d M Y') }}
                    </td>
                    <td style="padding:0.75rem 1rem;font-size:0.82rem;color:#1A2A3A;font-weight:600;">
                        {{ $batch->member_count }} anggota
                    </td>
                    <td style="padding:0.75rem 1rem;font-size:0.82rem;font-weight:700;color:#1A2A3A;white-space:nowrap;">
                        Rp {{ number_format($batch->total_amount, 0, ',', '.') }}
                    </td>
                    <td style="padding:0.75rem 1rem;">
                        <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;white-space:nowrap;{{ $stStyle }}">
                            {{ $stLabel }}
                        </span>
                        @if ($batch->reject_reason)
                        <p style="font-size:0.7rem;color:#C0392B;margin:0.25rem 0 0;">{{ $batch->reject_reason }}</p>
                        @endif
                    </td>
                    <td style="padding:0.75rem 1rem;">
                        <a href="{{ route('daerah.pay.batch.show', $batch->id) }}"
                           style="font-size:0.7rem;font-weight:700;color:#2A7FC1;text-decoration:none;padding:0.3rem 0.75rem;border:1.5px solid #D6E8F7;border-radius:3px;white-space:nowrap;">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                        Belum ada riwayat batch pembayaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($batches->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $batches->links() }}</div>
@endif

@endsection