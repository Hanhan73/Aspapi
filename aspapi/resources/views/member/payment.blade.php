@extends('layouts.member')
@php $title = 'Pembayaran'; @endphp

@section('content')

{{-- Info Rekening --}}
<div style="background:linear-gradient(135deg,#1A5F9A,#2A7FC1);border-radius:8px;padding:1.5rem;margin-bottom:1.5rem;color:#fff;">
    <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#A8D4F5;margin-bottom:0.5rem;">Rekening Tujuan Pembayaran</p>
    <p style="font-size:1.5rem;font-family:'DM Serif Display',serif;letter-spacing:0.1em;">1661531545</p>
    <p style="font-size:0.875rem;color:#A8D4F5;margin-top:0.25rem;">Bank BNI — Sitti Hardiyanti Arhas</p>
    <div style="display:flex;gap:2rem;margin-top:1rem;">
        <div>
            <p style="font-size:0.65rem;color:#A8D4F5;letter-spacing:0.08em;text-transform:uppercase;">Anggota Baru</p>
            <p style="font-size:1rem;font-weight:700;">Rp 250.000</p>
            <p style="font-size:0.72rem;color:#A8D4F5;">Uang Pangkal</p>
        </div>
        <div>
            <p style="font-size:0.65rem;color:#A8D4F5;letter-spacing:0.08em;text-transform:uppercase;">Anggota Lama / Iuran</p>
            <p style="font-size:1rem;font-weight:700;">Rp 120.000</p>
            <p style="font-size:0.72rem;color:#A8D4F5;">Iuran Tahunan</p>
        </div>
    </div>
</div>

@if ($member?->biodata_status !== 'verified')
<div style="background:#FEF8EC;border-left:4px solid #E8B84B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.875rem;color:#8B6914;">
    Biodata Anda belum diverifikasi oleh Admin. Upload bukti pembayaran hanya bisa dilakukan setelah biodata diverifikasi.
</div>
@else

{{-- Form Upload --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;margin-bottom:1.5rem;">
    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1.25rem;">Upload Bukti Pembayaran</p>

    <form method="POST" action="{{ route('member.payment.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Jenis Pembayaran *</label>
                <select name="type" required
                        style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                    @if ($member->registration_type === 'baru' && !$member->hasPaidUangPangkal())
                    <option value="uang_pangkal">Uang Pangkal (Rp 250.000)</option>
                    @endif
                    @if (!$member->hasPaidIuranTahunan())
                    <option value="iuran_tahunan">Iuran Tahunan (Rp 120.000)</option>
                    @endif
                </select>
            </div>
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Bukti Transfer *</label>
                <input type="file" name="receipt" accept="image/*,.pdf" required
                       style="width:100%;font-size:0.8rem;color:#4A6580;padding:0.5rem 0;"/>
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.25rem;">JPG, PNG, PDF. Maks 3MB.</p>
            </div>
        </div>

        <button type="submit"
                style="padding:0.75rem 2rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
            Upload Bukti Pembayaran
        </button>
    </form>
</div>
@endif

{{-- Riwayat --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;">
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Riwayat Pembayaran</p>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jenis</th>
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Jumlah</th>
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Metode</th>
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Status</th>
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tanggal</th>
                <th style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Bukti</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $pay)
            <tr style="border-bottom:1px solid #F8FAFC;">
                <td style="padding:0.75rem 1rem;font-size:0.825rem;color:#1A2A3A;">{{ $pay->type_label }}</td>
                <td style="padding:0.75rem 1rem;font-size:0.825rem;font-weight:600;color:#1A2A3A;">{{ $pay->amount_formatted }}</td>
                <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;">{{ ucfirst($pay->payment_method) }}</td>
                <td style="padding:0.75rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $pay->status === 'verified' ? 'background:#F0FFF4;color:#276749;' : ($pay->status === 'rejected' ? 'background:#FDECEA;color:#C0392B;' : 'background:#FEF8EC;color:#B8860B;') }}">
                        {{ $pay->status === 'verified' ? 'Terverifikasi' : ($pay->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                    </span>
                </td>
                <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;">{{ $pay->created_at->format('d M Y') }}</td>
                <td style="padding:0.75rem 1rem;">
                    @if ($pay->receipt_path)
                    <a href="{{ Storage::url($pay->receipt_path) }}" target="_blank"
                       style="font-size:0.7rem;color:#2A7FC1;font-weight:700;">Lihat</a>
                    @else <span style="color:#B0CCDF;font-size:0.8rem;">—</span> @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="padding:2rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">Belum ada riwayat pembayaran.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection