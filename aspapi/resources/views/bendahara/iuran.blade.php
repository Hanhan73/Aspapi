@extends('layouts.bendahara')
@php $title = 'Status Iuran Anggota'; @endphp

@section('content')

{{-- ── Summary cards ─────────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">

    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #2A7FC1;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Anggota Aktif</p>
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.25rem;">{{ $totalAktifMember }}</p>
        <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.125rem;">status = active</p>
    </div>

    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #276749;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Iuran Masih Aktif</p>
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#276749;margin-top:0.25rem;">{{ $totalIuranAktif }}</p>
        <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.125rem;">active_until > sekarang</p>
    </div>

    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #C0392B;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Iuran Kadaluarsa</p>
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#C0392B;margin-top:0.25rem;">{{ $totalKadaluarsa }}</p>
        <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.125rem;">pernah bayar, sudah lewat</p>
    </div>

    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #9B59B6;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Belum Aktif</p>
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#9B59B6;margin-top:0.25rem;">{{ $totalBelumAktif }}</p>
        <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.125rem;">biodata verified, belum bayar</p>
    </div>

    @if ($totalAktifMember > 0)
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #E8B84B;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Tingkat Kepatuhan</p>
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;margin-top:0.25rem;">
            {{ round(($totalIuranAktif / $totalMember) * 100) }}%
        </p>
        <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.125rem;">dari anggota aktif</p>
    </div>
    @endif

</div>

{{-- ── Filter ─────────────────────────────────────────────────────── --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('bendahara.iuran') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">

        <select name="region" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;min-width:180px;">
            <option value="">Semua Daerah</option>
            @foreach ($regions as $r)
            <option value="{{ $r->id }}" {{ $regionId == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
            @endforeach
        </select>

        <select name="status_iuran" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Status</option>
            <option value="aktif"       {{ $filterStatus === 'aktif'       ? 'selected' : '' }}>Iuran Aktif</option>
            <option value="kadaluarsa"  {{ $filterStatus === 'kadaluarsa'  ? 'selected' : '' }}>Iuran Kadaluarsa</option>
            <option value="belum_aktif" {{ $filterStatus === 'belum_aktif' ? 'selected' : '' }}>Belum Aktif</option>
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

        {{-- Export --}}
        <a href="{{ route('bendahara.iuran.export', array_filter(request()->only(['region','status_iuran','search']))) }}"
           style="padding:0.5rem 1rem;background:#276749;color:#fff;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:0.375rem;">
            ↓ Export Excel
        </a>
    </form>

    @if (request()->hasAny(['region', 'status_iuran', 'search']))
    <p style="font-size:0.75rem;color:#4A6580;margin-top:0.625rem;">
        Menampilkan <strong>{{ $members->total() }}</strong> anggota
        @if($regionId) — {{ $regions->firstWhere('id', $regionId)?->name }} @endif
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
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Daerah</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status Akun</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status Iuran</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Terakhir Bayar</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Aktif Hingga</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Sisa Hari</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                @php
                    $isActive      = $member->status === 'active';
                    $isPending     = $member->status === 'pending'; // belum aktif
                    $isAktif       = in_array($member->id, $aktifIds);
                    $isKadaluarsa  = in_array($member->id, $kadaluarsaIds);
                    $iuranTerakhir = $member->payments->first();
                    $activeUntil   = $member->active_until;

                    // Sisa hari — null jika belum pernah bayar
                    $sisaHari = $activeUntil ? (int) now()->diffInDays($activeUntil, false) : null;

                    // Segera kadaluarsa: aktif tapi <= 30 hari lagi
                    $isExpiringSoon = $isAktif && $sisaHari !== null && $sisaHari <= 30;
                @endphp
                <tr style="border-bottom:1px solid #EEF4FB;{{ $isPending ? 'background:#FDFAFF;' : '' }}"
                    onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='{{ $isPending ? '#FDFAFF' : '#fff' }}'">

                    {{-- Nama & Email --}}
                    <td style="padding:0.75rem 1rem;">
                        <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0;">{{ $member->full_name }}</p>
                        <p style="font-size:0.72rem;color:#B0CCDF;margin:0;">{{ $member->email }}</p>
                    </td>

                    {{-- NIA --}}
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;font-family:monospace;white-space:nowrap;">
                        {{ $member->member_number ?? '—' }}
                    </td>

                    <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;white-space:nowrap;">
                        {{ $member->region->name ?? '—' }}
                    </td>

                    {{-- Status Akun --}}
                    <td style="padding:0.75rem 1rem;">
                        @if ($isActive)
                            <span style="font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#F0FFF4;color:#276749;">
                                Aktif
                            </span>
                        @else
                            <span style="font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#F5F0FF;color:#9B59B6;">
                                Belum Aktif
                            </span>
                        @endif
                    </td>

                    {{-- Status Iuran --}}
                    <td style="padding:0.75rem 1rem;">
                        @if ($isPending)
                            {{-- Belum aktif = belum bayar sama sekali --}}
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#F5F0FF;color:#9B59B6;">
                                ⏳ Menunggu Pembayaran
                            </span>
                        @elseif ($isAktif && !$isExpiringSoon)
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#F0FFF4;color:#276749;">
                                ✓ Aktif
                            </span>
                        @elseif ($isExpiringSoon)
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#FEF8EC;color:#B8860B;">
                                ⚠ Segera Kadaluarsa
                            </span>
                        @elseif ($isKadaluarsa)
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#FDECEA;color:#C0392B;">
                                ✗ Kadaluarsa
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;background:#F5F5F5;color:#888;">
                                — Belum Pernah Bayar
                            </span>
                        @endif
                    </td>

                    {{-- Terakhir Bayar --}}
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;white-space:nowrap;">
                        @if ($iuranTerakhir && $iuranTerakhir->verified_at)
                            {{ $iuranTerakhir->verified_at->format('d M Y') }}
                        @else
                            —
                        @endif
                    </td>

                    {{-- Aktif Hingga --}}
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;white-space:nowrap;">
                        @if ($activeUntil)
                            <span style="color:{{ $isAktif ? ($isExpiringSoon ? '#B8860B' : '#276749') : '#C0392B' }};font-weight:{{ $isAktif ? '400' : '700' }};">
                                {{ $activeUntil->format('d M Y') }}
                            </span>
                        @else
                            <span style="color:#B0CCDF;">—</span>
                        @endif
                    </td>

                    {{-- Sisa Hari --}}
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;white-space:nowrap;">
                        @if ($isPending)
                            <span style="color:#B0CCDF;">—</span>
                        @elseif ($sisaHari === null)
                            <span style="color:#B0CCDF;">—</span>
                        @elseif ($sisaHari > 30)
                            <span style="color:#276749;">{{ $sisaHari }} hari</span>
                        @elseif ($sisaHari > 0)
                            <span style="color:#B8860B;font-weight:700;">{{ $sisaHari }} hari</span>
                        @else
                            <span style="color:#C0392B;font-weight:700;">{{ abs($sisaHari) }} hari lalu</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
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