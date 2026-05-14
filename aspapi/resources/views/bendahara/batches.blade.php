@extends('layouts.bendahara')
@php $title = 'Batch Kolektif'; @endphp

@section('content')

{{-- Filter --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('bendahara.batches') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">

        <select name="status" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>

        {{-- FIX: tambah filter wilayah --}}
        <select name="region_id" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;max-width:200px;">
            <option value="">Semua Wilayah</option>
            @foreach ($regions as $region)
            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                {{ $region->province }}
            </option>
            @endforeach
        </select>

        {{-- FIX: tambah filter tahun --}}
        <select name="year" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Tahun</option>
            @for ($y = now()->year; $y >= 2020; $y--)
            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>

        <button type="submit" style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">
            Filter
        </button>
        <a href="{{ route('bendahara.batches') }}"
           style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">
            Reset
        </a>
    </form>

    @if (request()->hasAny(['status', 'region_id', 'year']))
    <p style="font-size:0.75rem;color:#4A6580;margin-top:0.625rem;">
        Menampilkan <strong>{{ $batches->total() }}</strong> batch
    </p>
    @endif
</div>

<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#EEF4FB;">
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Wilayah</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Diajukan Oleh</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Jumlah Anggota</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Total</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Status</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Tanggal</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($batches as $batch)
                <tr style="border-bottom:1px solid #EEF4FB;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                    <td style="padding:0.875rem 1rem;">
                        <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0;">{{ $batch->region->province ?? '—' }}</p>
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0;">{{ $batch->submitter->name ?? '—' }}</p>
                        <p style="font-size:0.72rem;color:#B0CCDF;margin:0;">{{ $batch->submitter->email ?? '' }}</p>
                    </td>
                    <td style="padding:0.875rem 1rem;font-size:0.825rem;color:#4A6580;white-space:nowrap;">{{ $batch->member_count }} anggota</td>
                    <td style="padding:0.875rem 1rem;font-size:0.825rem;font-weight:700;color:#1A2A3A;white-space:nowrap;">
                        Rp {{ number_format($batch->total_amount ?? 0, 0, ',', '.') }}
                    </td>
                    <td style="padding:0.875rem 1rem;">
                        @php
                            $st = $batch->status;
                            $stStyle = $st === 'verified' ? 'background:#F0FFF4;color:#276749;'
                                     : ($st === 'rejected' ? 'background:#FDECEA;color:#C0392B;'
                                     : 'background:#FEF8EC;color:#B8860B;');
                            $stLabel = $st === 'verified' ? 'Terverifikasi'
                                     : ($st === 'rejected' ? 'Ditolak' : 'Pending');
                        @endphp
                        <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;white-space:nowrap;{{ $stStyle }}">
                            {{ $stLabel }}
                        </span>
                    </td>
                    <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;white-space:nowrap;">{{ $batch->created_at->format('d M Y') }}</td>
                    <td style="padding:0.875rem 1rem;">
                        @if ($batch->status === 'pending')
                        <form method="POST" action="{{ route('bendahara.batch.verify', $batch->id) }}">
                            @csrf
                            <button type="submit"
                                    style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.5rem;background:#276749;color:#fff;border:none;border-radius:3px;cursor:pointer;white-space:nowrap;">
                                Verifikasi Semua
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                        @if (request()->hasAny(['status', 'region_id', 'year']))
                            Tidak ada batch yang cocok dengan filter.
                        @else
                            Tidak ada data batch.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($batches->hasPages())
{{-- withQueryString() di controller memastikan filter dipertahankan --}}
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $batches->links() }}</div>
@endif

@endsection