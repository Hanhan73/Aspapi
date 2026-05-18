@extends('layouts.bendahara')
@php $title = 'Rekap Pemasukan'; @endphp

@section('content')

{{-- Filter tahun --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('bendahara.rekap') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <select name="year" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            @for ($y = now()->year; $y >= 2020; $y--)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit"
                style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">
            Tampilkan
        </button>
    </form>
</div>

{{-- ── Summary cards ─────────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #2A7FC1;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Total Pemasukan {{ $year }}</p>
        <p style="font-size:1.5rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.25rem;">
            Rp {{ number_format($totalTahun, 0, ',', '.') }}
        </p>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #E8B84B;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Uang Pangkal</p>
        <p style="font-size:1.5rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.25rem;">
            Rp {{ number_format($totalPangkal, 0, ',', '.') }}
        </p>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #276749;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Iuran Tahunan</p>
        <p style="font-size:1.5rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.25rem;">
            Rp {{ number_format($totalIuran, 0, ',', '.') }}
        </p>
    </div>
</div>

{{-- ── Tabel per bulan ─────────────────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;margin-bottom:1.5rem;">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;">
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;">Rincian Per Bulan — {{ $year }}</p>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#EEF4FB;">
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Bulan</th>
                    <th style="padding:0.75rem 1rem;text-align:right;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Uang Pangkal</th>
                    <th style="padding:0.75rem 1rem;text-align:right;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Iuran Tahunan</th>
                    <th style="padding:0.75rem 1rem;text-align:right;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perBulan as $bln)
                <tr style="border-bottom:1px solid #EEF4FB;{{ $bln['total'] > 0 ? '' : 'opacity:0.45;' }}"
                    onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                    <td style="padding:0.75rem 1rem;font-size:0.85rem;color:#1A2A3A;font-weight:{{ $bln['total'] > 0 ? '600' : '400' }};">
                        {{ $bln['nama_bulan'] }}
                    </td>
                    <td style="padding:0.75rem 1rem;text-align:right;font-size:0.825rem;color:#4A6580;">
                        {{ $bln['uang_pangkal'] > 0 ? 'Rp ' . number_format($bln['uang_pangkal'], 0, ',', '.') : '—' }}
                    </td>
                    <td style="padding:0.75rem 1rem;text-align:right;font-size:0.825rem;color:#4A6580;">
                        {{ $bln['iuran_tahunan'] > 0 ? 'Rp ' . number_format($bln['iuran_tahunan'], 0, ',', '.') : '—' }}
                    </td>
                    <td style="padding:0.75rem 1rem;text-align:right;font-size:0.825rem;font-weight:700;color:#1A2A3A;">
                        {{ $bln['total'] > 0 ? 'Rp ' . number_format($bln['total'], 0, ',', '.') : '—' }}
                    </td>
                </tr>
                @endforeach

                {{-- Row total --}}
                <tr style="background:#EEF4FB;border-top:2px solid #D6E8F7;">
                    <td style="padding:0.875rem 1rem;font-size:0.825rem;font-weight:700;color:#1A2A3A;">TOTAL</td>
                    <td style="padding:0.875rem 1rem;text-align:right;font-size:0.825rem;font-weight:700;color:#1A2A3A;">
                        Rp {{ number_format($totalPangkal, 0, ',', '.') }}
                    </td>
                    <td style="padding:0.875rem 1rem;text-align:right;font-size:0.825rem;font-weight:700;color:#1A2A3A;">
                        Rp {{ number_format($totalIuran, 0, ',', '.') }}
                    </td>
                    <td style="padding:0.875rem 1rem;text-align:right;font-size:0.825rem;font-weight:700;color:#2A7FC1;">
                        Rp {{ number_format($totalTahun, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ── Daftar Transaksi ─────────────────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.75rem;">
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;">
            Daftar Transaksi — {{ $year }}
        </p>
        {{-- Filter transaksi --}}
        <form method="GET" action="{{ route('bendahara.rekap') }}" style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
            <input type="hidden" name="year" value="{{ $year }}"/>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / email..."
                   style="padding:0.375rem 0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.8rem;color:#1A2A3A;outline:none;width:180px;"/>
            <select name="month"
                    style="padding:0.375rem 0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.8rem;color:#1A2A3A;outline:none;">
                <option value="">Semua Bulan</option>
                @foreach ($perBulan as $bln)
                <option value="{{ $bln['bulan'] }}" {{ request('month') == $bln['bulan'] ? 'selected' : '' }}>
                    {{ $bln['nama_bulan'] }}
                </option>
                @endforeach
            </select>
            <select name="type"
                    style="padding:0.375rem 0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.8rem;color:#1A2A3A;outline:none;">
                <option value="">Semua Jenis</option>
                <option value="uang_pangkal"  {{ request('type') === 'uang_pangkal'  ? 'selected' : '' }}>Uang Pangkal</option>
                <option value="iuran_tahunan" {{ request('type') === 'iuran_tahunan' ? 'selected' : '' }}>Iuran Tahunan</option>
            </select>
            <button type="submit"
                    style="padding:0.375rem 0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">
                Filter
            </button>
            <a href="{{ route('bendahara.rekap', ['year' => $year]) }}"
               style="padding:0.375rem 0.75rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">
                Reset
            </a>
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#EEF4FB;">
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Anggota</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jenis</th>
                    <th style="padding:0.75rem 1rem;text-align:right;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jumlah</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Diverifikasi Oleh</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksi as $trx)
                <tr style="border-bottom:1px solid #EEF4FB;"
                    onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                    <td style="padding:0.75rem 1rem;">
                        <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0;">{{ $trx->member->full_name }}</p>
                        <p style="font-size:0.72rem;color:#B0CCDF;margin:0;">{{ $trx->member->member_number ?? $trx->member->email }}</p>
                    </td>
                    <td style="padding:0.75rem 1rem;font-size:0.825rem;color:#4A6580;white-space:nowrap;">{{ $trx->type_label }}</td>
                    <td style="padding:0.75rem 1rem;text-align:right;font-size:0.825rem;font-weight:700;color:#1A2A3A;white-space:nowrap;">
                        {{ $trx->amount_formatted }}
                    </td>
                    <td style="padding:0.75rem 1rem;font-size:0.825rem;color:#4A6580;">{{ $trx->verifier->name ?? '—' }}</td>
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;white-space:nowrap;">
                        {{ $trx->verified_at ? $trx->verified_at->format('d M Y') : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                        Tidak ada transaksi yang cocok.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($transaksi->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $transaksi->links() }}</div>
@endif

@endsection