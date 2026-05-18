@extends('layouts.bendahara')
@php $title = 'Status Iuran Anggota'; @endphp

@section('content')

{{-- ── Summary cards ─────────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #2A7FC1;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Total Anggota Aktif</p>
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.25rem;">{{ $totalAktif }}</p>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #276749;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Sudah Bayar {{ $year }}</p>
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#276749;margin-top:0.25rem;">{{ $totalSudahBayar }}</p>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #C0392B;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Belum Bayar {{ $year }}</p>
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#C0392B;margin-top:0.25rem;">{{ $totalBelumBayar }}</p>
    </div>
    @if ($totalAktif > 0)
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #E8B84B;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Tingkat Kepatuhan</p>
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.25rem;">
            {{ round(($totalSudahBayar / $totalAktif) * 100) }}%
        </p>
    </div>
    @endif
</div>

{{-- ── Filter ─────────────────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('bendahara.iuran') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">

        <select name="year" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            @for ($y = now()->year; $y >= 2020; $y--)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
            @endfor
        </select>

        <select name="status_iuran" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Status</option>
            <option value="sudah" {{ request('status_iuran') === 'sudah' ? 'selected' : '' }}>✓ Sudah Bayar</option>
            <option value="belum" {{ request('status_iuran') === 'belum' ? 'selected' : '' }}>✗ Belum Bayar</option>
        </select>

        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / NIA / email..."
               style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;width:220px;"/>

        <button type="submit"
                style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">
            Filter
        </button>
        <a href="{{ route('bendahara.iuran') }}"
           style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">
            Reset
        </a>
    </form>

    @if (request()->hasAny(['status_iuran', 'search', 'year']))
    <p style="font-size:0.75rem;color:#4A6580;margin-top:0.625rem;">
        Menampilkan <strong>{{ $members->total() }}</strong> anggota
        @if(request('search')) — "<em>{{ request('search') }}</em>" @endif
    </p>
    @endif
</div>

{{-- ── Tabel ─────────────────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#EEF4FB;">
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Anggota</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">NIA</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Iuran {{ $year }}</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tanggal Bayar</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Due Date</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Kartu Aktif s.d.</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                @php
                    $sudahBayar = in_array($member->id, $sudahBayarIds);
                    // Pembayaran iuran tahun ini (sudah di-eager load)
                    $iuranTahunIni = $member->payments->first();

                    // Due date: 31 Desember tahun berjalan
                    $dueDate = \Carbon\Carbon::create($year, 12, 31);
                    $isOverdue = !$sudahBayar && now()->isAfter($dueDate);
                @endphp
                <tr style="border-bottom:1px solid #EEF4FB;"
                    onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                    <td style="padding:0.75rem 1rem;">
                        <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0;">{{ $member->full_name }}</p>
                        <p style="font-size:0.72rem;color:#B0CCDF;margin:0;">{{ $member->email }}</p>
                    </td>
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;font-family:monospace;white-space:nowrap;">
                        {{ $member->member_number ?? '—' }}
                    </td>
                    <td style="padding:0.75rem 1rem;">
                        @if ($sudahBayar)
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#F0FFF4;color:#276749;">
                                ✓ Lunas
                            </span>
                        @elseif ($isOverdue)
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#FDECEA;color:#C0392B;">
                                ✗ Jatuh Tempo
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#FEF8EC;color:#B8860B;">
                                Belum Bayar
                            </span>
                        @endif
                    </td>
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;white-space:nowrap;">
                        @if ($iuranTahunIni && $iuranTahunIni->verified_at)
                            {{ $iuranTahunIni->verified_at->format('d M Y') }}
                        @else
                            —
                        @endif
                    </td>
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;white-space:nowrap;">
                        <span style="color:{{ $isOverdue ? '#C0392B' : '#4A6580' }};font-weight:{{ $isOverdue ? '700' : '400' }};">
                            {{ $dueDate->format('d M Y') }}
                        </span>
                    </td>
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;white-space:nowrap;">
                        {{ $member->active_until ? $member->active_until->format('d M Y') : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                        Tidak ada anggota yang cocok dengan filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($members->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $members->links() }}</div>
@endif

@endsection