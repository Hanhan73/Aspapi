@extends('layouts.admin')
@php $title = 'Verifikasi Anggota'; @endphp

@section('content')

{{-- Summary --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #E8B84B;">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;">{{ $pendingCount }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.25rem;">Menunggu Verifikasi</p>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #C0392B;">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;">{{ $oldClaimCount }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.25rem;">Klaim Anggota Lama</p>
    </div>
</div>

{{-- Filter --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..."
               style="flex:1;min-width:180px;padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;"/>
        <select name="status" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <select name="type" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Tipe</option>
            <option value="baru" {{ request('type') === 'baru' ? 'selected' : '' }}>Anggota Baru</option>
            <option value="lama" {{ request('type') === 'lama' ? 'selected' : '' }}>Anggota Lama</option>
        </select>
        <button type="submit" style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">Filter</button>
        <a href="{{ route('admin.member.verify.index') }}" style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">Reset</a>
    </form>
</div>

{{-- Table --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Anggota</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tipe</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Klaim Lama</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status Biodata</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Daftar</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $member)
            <tr style="border-bottom:1px solid #EEF4FB;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">

                {{-- Anggota --}}
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        @if ($member->photo)
                        <img src="{{ Storage::url($member->photo) }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #D6E8F7;flex-shrink:0;"/>
                        @else
                        <div style="width:36px;height:36px;border-radius:50%;background:#EEF4FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:0.85rem;font-weight:700;color:#2A7FC1;">{{ strtoupper(substr($member->full_name, 0, 1)) }}</span>
                        </div>
                        @endif
                        <div>
                            <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;">{{ $member->full_name }}</p>
                            <p style="font-size:0.72rem;color:#B0CCDF;">{{ $member->email }}</p>
                        </div>
                    </div>
                </td>

                {{-- Tipe --}}
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $member->registration_type === 'baru' ? 'background:#EEF4FB;color:#2A7FC1;' : 'background:#FEF8EC;color:#B8860B;' }}">
                        {{ $member->registration_type === 'baru' ? 'Baru' : 'Lama' }}
                    </span>
                </td>

                {{-- Klaim Lama --}}
                <td style="padding:0.875rem 1rem;">
                    @if ($member->claims_old_member)
                    <span style="font-size:0.72rem;color:#B8860B;font-weight:600;">Klaim sejak {{ $member->claimed_join_year }}</span>
                    @else
                    <span style="color:#B0CCDF;font-size:0.8rem;">—</span>
                    @endif
                </td>

                {{-- Status Biodata --}}
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $member->biodata_status === 'verified' ? 'background:#F0FFF4;color:#276749;' : ($member->biodata_status === 'rejected' ? 'background:#FDECEA;color:#C0392B;' : 'background:#FEF8EC;color:#B8860B;') }}">
                        {{ $member->biodata_status === 'verified' ? 'Terverifikasi' : ($member->biodata_status === 'rejected' ? 'Ditolak' : 'Pending') }}
                    </span>
                </td>

                {{-- Tanggal Daftar --}}
                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;">
                    {{ $member->created_at->format('d M Y') }}
                </td>

                {{-- Aksi --}}
                <td style="padding:0.875rem 1rem;">
                    @if ($member->biodata_status === 'pending')
                    <div style="display:flex;flex-wrap:wrap;gap:0.375rem;">

                        {{-- Satu tombol Approve untuk semua jenis anggota --}}
                        <form method="POST" action="{{ route('admin.member.verify.approve', $member->id) }}">
                            @csrf
                            <button type="submit"
                                    style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.5rem;background:#276749;color:#fff;border:none;border-radius:3px;cursor:pointer;">
                                ✓ Approve
                            </button>
                        </form>

                        {{-- Tombol Tolak --}}
                        <button onclick="showRejectModal({{ $member->id }})"
                                style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.5rem;background:transparent;border:1.5px solid #C0392B;color:#C0392B;border-radius:3px;cursor:pointer;">
                            Tolak
                        </button>

                    </div>
                    @endif
                </td>

            </tr>
            @empty
            <tr><td colspan="6" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">Tidak ada data anggota.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($members->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $members->links() }}</div>
@endif

{{-- Modal Reject --}}
<div id="reject-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:8px;padding:2rem;width:440px;max-width:90vw;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:1.25rem;color:#1A2A3A;margin-bottom:1rem;">Tolak Biodata</h3>
        <form id="reject-form" method="POST">
            @csrf
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Alasan Penolakan *</label>
            <textarea name="reason" rows="4" required
                      style="width:100%;padding:0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;resize:vertical;"
                      placeholder="Jelaskan mengapa biodata ditolak..."></textarea>
            <div style="display:flex;gap:0.75rem;margin-top:1.25rem;justify-content:flex-end;">
                <button type="button" onclick="closeRejectModal()"
                        style="padding:0.625rem 1.25rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.75rem;font-weight:700;color:#4A6580;background:transparent;cursor:pointer;">
                    Batal
                </button>
                <button type="submit"
                        style="padding:0.625rem 1.25rem;background:#C0392B;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">
                    Tolak Biodata
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal(memberId) {
    document.getElementById('reject-form').action = '/admin/verifikasi-anggota/' + memberId + '/reject';
    document.getElementById('reject-modal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('reject-modal').style.display = 'none';
}
</script>
@endpush

@endsection