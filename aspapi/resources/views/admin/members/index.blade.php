@extends('layouts.admin')
@php $title = 'Manajemen Anggota'; @endphp

@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;margin-bottom:1.5rem;">
    @php
        $statCards = [
            ['label' => 'Total Anggota',  'value' => App\Models\Member::count(),                            'color' => '#2A7FC1'],
            ['label' => 'Aktif',          'value' => App\Models\Member::where('status','active')->count(),   'color' => '#276749'],
            ['label' => 'Pending',        'value' => App\Models\Member::where('status','pending')->count(),  'color' => '#E8B84B'],
            ['label' => 'Ditolak',        'value' => App\Models\Member::where('status','rejected')->count(), 'color' => '#C0392B'],
        ];
    @endphp
    @foreach ($statCards as $card)
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid {{ $card['color'] }};">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;line-height:1;">{{ $card['value'] }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.375rem;">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari nama / email / nomor anggota..."
               style="flex:1;min-width:200px;padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;"
               onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>

        <select name="status" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>

        <select name="type" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Tipe</option>
            <option value="baru" {{ request('type') === 'baru' ? 'selected' : '' }}>Anggota Baru</option>
            <option value="lama" {{ request('type') === 'lama' ? 'selected' : '' }}>Anggota Lama</option>
        </select>

        <select name="biodata" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Biodata</option>
            <option value="pending"  {{ request('biodata') === 'pending'  ? 'selected' : '' }}>Biodata Pending</option>
            <option value="verified" {{ request('biodata') === 'verified' ? 'selected' : '' }}>Biodata Verified</option>
            <option value="rejected" {{ request('biodata') === 'rejected' ? 'selected' : '' }}>Biodata Ditolak</option>
        </select>

        <button type="submit"
                style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
            Filter
        </button>
        <a href="{{ route('admin.members.index') }}"
           style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">
            Reset
        </a>
    </form>
</div>

{{-- Table --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Anggota</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">No. Anggota</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tipe</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">ASPAPI Daerah</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Biodata</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Daftar</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $member)
            <tr style="border-bottom:1px solid #EEF4FB;"
                onmouseover="this.style.background='#F8FAFC'"
                onmouseout="this.style.background='#fff'">

                {{-- Nama --}}
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        @if ($member->photo)
                            <img src="{{ Storage::url($member->photo) }}"
                                 style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #D6E8F7;flex-shrink:0;"/>
                        @else
                            <div style="width:36px;height:36px;border-radius:50%;background:#EEF4FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <span style="font-size:0.85rem;font-weight:700;color:#2A7FC1;">
                                    {{ strtoupper(substr($member->full_name ?? $member->user?->name ?? '?', 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;">
                                {{ $member->full_name ?? $member->user?->name ?? '—' }}
                            </p>
                            <p style="font-size:0.72rem;color:#B0CCDF;">{{ $member->email ?? $member->user?->email }}</p>
                        </div>
                    </div>
                </td>

                {{-- Nomor --}}
                <td style="padding:0.875rem 1rem;">
                    @if ($member->member_number)
                        <span style="font-size:0.78rem;font-weight:700;color:#1A2A3A;font-family:monospace;letter-spacing:0.06em;">
                            {{ $member->member_number }}
                        </span>
                    @else
                        <span style="color:#B0CCDF;font-size:0.8rem;">—</span>
                    @endif
                </td>

                {{-- Tipe --}}
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $member->registration_type === 'baru' ? 'background:#EEF4FB;color:#2A7FC1;' : 'background:#FEF8EC;color:#B8860B;' }}">
                        {{ $member->registration_type === 'baru' ? 'Baru' : 'Lama' }}
                    </span>
                    @if ($member->claims_old_member)
                        <span style="font-size:0.6rem;color:#B8860B;display:block;margin-top:2px;">
                            Klaim sejak {{ $member->claimed_join_year }}
                        </span>
                    @endif
                </td>

                <td style="padding:0.875rem 1rem;">
                    @if ($member->registeredByRegion)
                        <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;background:#D6E8F7;color:#2A7FC1;">
                            {{ $member->registeredByRegion->name }}
                        </span>
                    @else
                        <span style="color:#B0CCDF;font-size:0.8rem;">—</span>
                    @endif
                </td>

                {{-- Biodata --}}
                <td style="padding:0.875rem 1rem;">
                    @php
                        $bdColor = match($member->biodata_status) {
                            'verified' => 'background:#F0FFF4;color:#276749;',
                            'rejected' => 'background:#FDECEA;color:#C0392B;',
                            default    => 'background:#FEF8EC;color:#B8860B;',
                        };
                        $bdLabel = match($member->biodata_status) {
                            'verified' => 'Terverifikasi',
                            'rejected' => 'Ditolak',
                            default    => 'Pending',
                        };
                    @endphp
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;{{ $bdColor }}">
                        {{ $bdLabel }}
                    </span>
                </td>

                {{-- Status --}}
                <td style="padding:0.875rem 1rem;">
                    @php
                        $stColor = match($member->status) {
                            'active'   => 'background:#F0FFF4;color:#276749;',
                            'rejected' => 'background:#FDECEA;color:#C0392B;',
                            'inactive' => 'background:#F0F2F4;color:#5C6B78;',
                            default    => 'background:#FEF8EC;color:#B8860B;',
                        };
                        $stLabel = match($member->status) {
                            'active'   => 'Aktif',
                            'rejected' => 'Ditolak',
                            'inactive' => 'Tidak Aktif',
                            default    => 'Pending',
                        };
                    @endphp
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;{{ $stColor }}">
                        {{ $stLabel }}
                    </span>
                </td>

                {{-- Tanggal Daftar --}}
                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;">
                    {{ $member->created_at->format('d M Y') }}
                </td>

                {{-- Aksi --}}
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;gap:0.375rem;align-items:center;">
                        <a href="{{ route('admin.members.show', $member) }}"
                           style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.625rem;background:#EEF4FB;color:#2A7FC1;border-radius:3px;text-decoration:none;">
                            Detail
                        </a>

                        {{-- Verifikasi biodata shortcut --}}
                        @if ($member->biodata_status === 'pending' && $member->full_name)
                            <a href="{{ route('admin.members.show', $member) }}#biodata"
                               style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.625rem;background:#276749;color:#fff;border-radius:3px;text-decoration:none;">
                                Verifikasi
                            </a>
                        @endif

                        <form method="POST" action="{{ route('admin.members.destroy', $member) }}"
                              onsubmit="return confirm('Hapus anggota {{ addslashes($member->full_name ?? '') }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.625rem;background:transparent;border:1.5px solid #C0392B;color:#C0392B;border-radius:3px;cursor:pointer;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                    Tidak ada data anggota.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($members->hasPages())
    <div style="margin-top:1rem;display:flex;justify-content:flex-end;">
        {{ $members->links() }}
    </div>
@endif

@endsection