@extends('layouts.bendahara')
@php $title = 'Verifikasi Pembayaran'; @endphp

@section('content')

<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <select name="status" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <select name="type" style="padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
            <option value="">Semua Jenis</option>
            <option value="uang_pangkal"  {{ request('type') === 'uang_pangkal'  ? 'selected' : '' }}>Uang Pangkal</option>
            <option value="iuran_tahunan" {{ request('type') === 'iuran_tahunan' ? 'selected' : '' }}>Iuran Tahunan</option>
        </select>
        <button type="submit" style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">Filter</button>
        <a href="{{ route('bendahara.payments') }}" style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">Reset</a>
    </form>
</div>

<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Anggota</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jenis</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jumlah</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Bukti</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tanggal</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $pay)
            <tr style="border-bottom:1px solid #EEF4FB;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                <td style="padding:0.875rem 1rem;">
                    <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;">{{ $pay->member->full_name }}</p>
                    <p style="font-size:0.72rem;color:#B0CCDF;">{{ $pay->member->email }}</p>
                </td>
                <td style="padding:0.875rem 1rem;font-size:0.825rem;color:#4A6580;">{{ $pay->type_label }}</td>
                <td style="padding:0.875rem 1rem;font-size:0.825rem;font-weight:700;color:#1A2A3A;">{{ $pay->amount_formatted }}</td>
                <td style="padding:0.875rem 1rem;">
                    @if ($pay->receipt_path)
                    <a href="{{ Storage::url($pay->receipt_path) }}" target="_blank"
                       style="font-size:0.72rem;color:#2A7FC1;font-weight:700;padding:0.25rem 0.5rem;border:1px solid #2A7FC1;border-radius:3px;text-decoration:none;">
                        Lihat Bukti
                    </a>
                    @else <span style="color:#B0CCDF;">—</span> @endif
                </td>
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $pay->status === 'verified' ? 'background:#F0FFF4;color:#276749;' : ($pay->status === 'rejected' ? 'background:#FDECEA;color:#C0392B;' : 'background:#FEF8EC;color:#B8860B;') }}">
                        {{ $pay->status === 'verified' ? 'Terverifikasi' : ($pay->status === 'rejected' ? 'Ditolak' : 'Pending') }}
                    </span>
                </td>
                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;">{{ $pay->created_at->format('d M Y') }}</td>
                <td style="padding:0.875rem 1rem;">
                    @if ($pay->status === 'pending')
                    <div style="display:flex;gap:0.375rem;">
                        <form method="POST" action="{{ route('bendahara.payment.verify', $pay->id) }}">
                            @csrf
                            <button type="submit" style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.5rem;background:#276749;color:#fff;border:none;border-radius:3px;cursor:pointer;">Verifikasi</button>
                        </form>
                        <button onclick="showRejectPayModal({{ $pay->id }})"
                                style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.5rem;background:transparent;border:1.5px solid #C0392B;color:#C0392B;border-radius:3px;cursor:pointer;">
                            Tolak
                        </button>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">Tidak ada data pembayaran.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($payments->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $payments->links() }}</div>
@endif

{{-- Modal Reject --}}
<div id="reject-pay-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:8px;padding:2rem;width:400px;max-width:90vw;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:1.25rem;color:#1A2A3A;margin-bottom:1rem;">Tolak Pembayaran</h3>
        <form id="reject-pay-form" method="POST">
            @csrf
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Alasan *</label>
            <textarea name="reason" rows="3" required
                      style="width:100%;padding:0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;resize:vertical;"></textarea>
            <div style="display:flex;gap:0.75rem;margin-top:1rem;justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('reject-pay-modal').style.display='none'"
                        style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.75rem;font-weight:700;color:#4A6580;background:transparent;cursor:pointer;">Batal</button>
                <button type="submit" style="padding:0.5rem 1rem;background:#C0392B;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">Tolak</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectPayModal(id) {
    document.getElementById('reject-pay-form').action = '/bendahara/pembayaran/' + id + '/reject';
    document.getElementById('reject-pay-modal').style.display = 'flex';
}
</script>
@endpush

@endsection