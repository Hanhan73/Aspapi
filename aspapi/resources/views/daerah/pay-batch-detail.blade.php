@extends('layouts.daerah')
@php $title = 'Detail Batch #' . $batch->id; @endphp

@section('content')

{{-- Back --}}
<a href="{{ route('daerah.pay.batches') }}"
    style="display:inline-flex;align-items:center;gap:0.375rem;font-size:0.75rem;font-weight:700;color:#4A6580;text-decoration:none;margin-bottom:1.25rem;">
    ← Kembali ke Riwayat Batch
</a>

<div class="grid gap-5" style="grid-template-columns:1fr 300px;align-items:start;">

    {{-- ── KIRI: Bukti + Anggota ── --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Bukti Transfer --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
            <div
                style="padding:0.875rem 1.25rem;border-bottom:1px solid #EEF4FB;display:flex;align-items:center;justify-content:space-between;">
                <p
                    style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;margin:0;">
                    Bukti Transfer</p>
                <a href="{{ Storage::url($batch->receipt_path) }}" download
                    style="font-size:0.7rem;font-weight:700;color:#2A7FC1;text-decoration:none;padding:0.3rem 0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;">
                    ↓ Download
                </a>
            </div>
            <div style="padding:1rem;background:#F8FAFC;">
                @php $ext = pathinfo($batch->receipt_path, PATHINFO_EXTENSION); @endphp
                @if (in_array(strtolower($ext), ['jpg','jpeg','png','webp']))
                <img src="{{ Storage::url($batch->receipt_path) }}" alt="Bukti Transfer"
                    style="max-width:100%;border-radius:4px;display:block;margin:0 auto;box-shadow:0 1px 6px rgba(0,0,0,0.08);">
                @elseif (strtolower($ext) === 'pdf')
                <iframe src="{{ Storage::url($batch->receipt_path) }}"
                    style="width:100%;height:480px;border:none;border-radius:4px;"></iframe>
                @else
                <p style="text-align:center;color:#B0CCDF;font-size:0.875rem;padding:2rem 0;">
                    Format tidak dapat ditampilkan —
                    <a href="{{ Storage::url($batch->receipt_path) }}" download style="color:#2A7FC1;">download file</a>
                </p>
                @endif
            </div>
        </div>

        {{-- Daftar Anggota --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
            <div style="padding:0.875rem 1.25rem;border-bottom:1px solid #EEF4FB;">
                <p
                    style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;margin:0;">
                    Anggota dalam Batch ({{ $batch->payments->count() }})
                </p>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#EEF4FB;">
                            <th
                                style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">
                                Anggota</th>
                            <th
                                style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">
                                NIA</th>
                            <th
                                style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">
                                Jumlah</th>
                            <th
                                style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">
                                Status Akun</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batch->payments as $payment)
                        <tr style="border-bottom:1px solid #EEF4FB;" onmouseover="this.style.background='#F8FAFC'"
                            onmouseout="this.style.background='#fff'">
                            <td style="padding:0.75rem 1rem;">
                                <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0;">
                                    {{ $payment->member->full_name_with_title ?? '—' }}</p>
                                <p style="font-size:0.72rem;color:#B0CCDF;margin:0;">{{ $payment->member->email ?? '' }}
                                </p>
                            </td>
                            <td
                                style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;font-family:monospace;white-space:nowrap;">
                                {{ $payment->member->member_number ?? '—' }}
                            </td>
                            <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#1A2A3A;white-space:nowrap;">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td style="padding:0.75rem 1rem;">
                                @php $s = $payment->member->status ?? 'pending'; @endphp
                                @if ($s === 'active')
                                <span
                                    style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;background:#F0FFF4;color:#276749;">✓
                                    Aktif</span>
                                @else
                                <span
                                    style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;background:#F5F0FF;color:#9B59B6;">⏳
                                    Belum Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ── KANAN: Info Batch ── --}}
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
        <p
            style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">
            Info Batch</p>

        @php
        $st = $batch->status;
        $stStyle = $st === 'verified' ? 'background:#F0FFF4;color:#276749;'
        : ($st === 'rejected' ? 'background:#FDECEA;color:#C0392B;'
        : 'background:#FEF8EC;color:#B8860B;');
        $stLabel = $st === 'verified' ? '✓ Terverifikasi' : ($st === 'rejected' ? '✗ Ditolak' : '⏳ Menunggu
        Verifikasi');
        @endphp

        <div style="display:flex;flex-direction:column;gap:0.75rem;font-size:0.82rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="color:#4A6580;">Status</span>
                <span
                    style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:2px;{{ $stStyle }}">{{ $stLabel }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:#4A6580;">Tanggal Diajukan</span>
                <span style="color:#1A2A3A;">{{ $batch->created_at->format('d M Y') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:#4A6580;">Diajukan Oleh</span>
                <span style="font-weight:600;color:#1A2A3A;">{{ $batch->submitter->name ?? '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:#4A6580;">Jumlah Anggota</span>
                <span style="font-weight:600;color:#1A2A3A;">{{ $batch->member_count }} orang</span>
            </div>
            <div
                style="display:flex;justify-content:space-between;border-top:1px solid #EEF4FB;padding-top:0.75rem;margin-top:0.25rem;">
                <span style="color:#4A6580;">Total</span>
                <span style="font-weight:700;color:#1A2A3A;font-size:0.9rem;">Rp
                    {{ number_format($batch->total_amount, 0, ',', '.') }}</span>
            </div>
            @if ($batch->verified_at)
            <div style="display:flex;justify-content:space-between;">
                <span style="color:#4A6580;">Diverifikasi</span>
                <span style="color:#1A2A3A;">{{ $batch->verified_at->format('d M Y') }}</span>
            </div>
            @endif
            @if ($batch->status === 'verified')
            @php
            $batchReceipt = \App\Models\Receipt::where('source_type', 'payment_batch')
            ->where('source_id', $batch->id)
            ->first();
            @endphp
            @if ($batchReceipt)
            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #EEF4FB;">
                <a href="{{ route('daerah.kwitansi', $batchReceipt->id) }}" target="_blank"
                    style="display:block;text-align:center;padding:0.6rem;background:#2A7FC1;color:#fff;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">
                    🧾 Cetak Kwitansi ({{ $batchReceipt->receipt_number }})
                </a>
            </div>
            @endif
            @endif
            @if ($batch->reject_reason)
            <div style="background:#FDECEA;border-radius:4px;padding:0.75rem;margin-top:0.25rem;">
                <p style="font-size:0.7rem;font-weight:700;color:#C0392B;margin:0 0 0.25rem;">Alasan Penolakan</p>
                <p style="font-size:0.8rem;color:#922B21;margin:0;">{{ $batch->reject_reason }}</p>
            </div>
            @endif
        </div>

        @if ($batch->status === 'pending')
        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #EEF4FB;">
            <p style="font-size:0.72rem;color:#B0CCDF;text-align:center;">Menunggu verifikasi dari Bendahara.</p>
        </div>
        @endif
    </div>

</div>

@endsection