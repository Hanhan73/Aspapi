@extends('layouts.member')
@php $title = 'Pembayaran'; @endphp

@section('content')

{{-- Info Rekening --}}
<div
    style="background:linear-gradient(135deg,#1A5F9A,#2A7FC1);border-radius:8px;padding:1.5rem;margin-bottom:1.5rem;color:#fff;">
    <p
        style="font-size:0.65rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#A8D4F5;margin-bottom:0.5rem;">
        Rekening Tujuan Pembayaran</p>
    <p style="font-size:1.5rem;font-family:'DM Serif Display',serif;letter-spacing:0.1em;">1661531545</p>
    <p style="font-size:0.875rem;color:#A8D4F5;margin-top:0.25rem;">Bank BNI — Sitti Hardiyanti Arhas</p>
    <div style="display:flex;gap:2rem;margin-top:1rem;flex-wrap:wrap;">
        <div>
            <p style="font-size:0.65rem;color:#A8D4F5;letter-spacing:0.08em;text-transform:uppercase;">Anggota Baru</p>
            <p style="font-size:1rem;font-weight:700;">Rp 130.000</p>
            <p style="font-size:0.72rem;color:#A8D4F5;">Uang Pangkal</p>
        </div>
        <div>
            <p style="font-size:0.65rem;color:#A8D4F5;letter-spacing:0.08em;text-transform:uppercase;">Iuran Tahunan</p>
            <p style="font-size:1rem;font-weight:700;">Rp 120.000</p>
            <p style="font-size:0.72rem;color:#A8D4F5;">Per Tahun</p>
        </div>
        <div style="border-left:1px solid rgba(255,255,255,0.2);padding-left:2rem;">
            <p style="font-size:0.65rem;color:#A8D4F5;letter-spacing:0.08em;text-transform:uppercase;">Anggota Baru
                (Gabungan)</p>
            <p style="font-size:1rem;font-weight:700;">Rp 250.000</p>
            <p style="font-size:0.72rem;color:#A8D4F5;">Pangkal + Iuran sekaligus</p>
        </div>
    </div>
</div>

@if ($member?->biodata_status !== 'verified')
<div
    style="background:#FEF8EC;border-left:4px solid #E8B84B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.875rem;color:#8B6914;">
    Biodata Anda belum diverifikasi oleh Admin. Upload bukti pembayaran hanya bisa dilakukan setelah biodata
    diverifikasi.
</div>
@else

@php
$bisaPangkal = $member->registration_type === 'baru' && !$member->hasPaidUangPangkal();
$bisaIuran = !$member->hasPaidIuranTahunan();
$bisaGabungan = $bisaPangkal && $bisaIuran;
$adaOpsi = $bisaPangkal || $bisaIuran;

// Untuk banner peringatan: iuran aktif tapi hampir habis
$hampirKadaluarsa = !$bisaIuran && $member->isDuesExpiringSoon(30);
@endphp

{{-- Banner: iuran hampir kadaluarsa (aktif tapi <= 30 hari lagi) --}}
@if ($hampirKadaluarsa)
<div
    style="background:#FEF8EC;border-left:4px solid #E8B84B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.875rem;color:#8B6914;">
    ⚠ Masa aktif iuran Anda akan berakhir pada
    <strong>{{ $member->active_until->format('d M Y') }}</strong>.
    Silakan siapkan pembayaran perpanjangan sebelum kadaluarsa.
</div>
@endif

@if (!$adaOpsi)
<div
    style="background:#F0FFF4;border-left:4px solid #276749;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.875rem;color:#276749;">
    ✓ Iuran Anda aktif hingga <strong>{{ $member->active_until?->format('d M Y') ?? '-' }}</strong>.
    Semua kewajiban pembayaran Anda sudah lunas.
</div>
@else

{{-- Form Upload --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;margin-bottom:1.5rem;">
    <p
        style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1.25rem;">
        Upload Bukti Pembayaran</p>

    <form method="POST" action="{{ route('member.payment.store') }}" enctype="multipart/form-data"
        x-data="paymentForm()" x-init="init()">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
            <div>
                <label
                    style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Jenis Pembayaran *
                </label>
                <select name="type" required x-model="selectedType"
                    style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;background:#fff;">
                    @if ($bisaGabungan)
                    <option value="gabungan">Uang Pangkal + Iuran Tahunan (Rp 250.000) — Sekaligus</option>
                    @endif
                    @if ($bisaPangkal)
                    <option value="uang_pangkal">Uang Pangkal saja (Rp 130.000)</option>
                    @endif
                    @if ($bisaIuran)
                    <option value="iuran_tahunan">Iuran Tahunan saja (Rp 120.000)</option>
                    @endif
                </select>
            </div>
            <div>
                <label
                    style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Bukti Transfer *
                </label>
                <input type="file" name="receipt" accept="image/*,.pdf" required
                    style="width:100%;font-size:0.8rem;color:#4A6580;padding:0.5rem 0;" />
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.25rem;">JPG, PNG, PDF. Maks 3MB.</p>
            </div>
        </div>

        {{-- Ringkasan jumlah dinamis --}}
        <div x-show="selectedType"
            style="background:#EEF4FB;border-radius:6px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p
                    style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;">
                    Total yang harus ditransfer</p>
                <p x-text="getLabel()" style="font-size:0.8rem;color:#4A6580;margin-top:0.125rem;"></p>
            </div>
            <p x-text="getAmount()"
                style="font-size:1.5rem;font-weight:800;color:#1A5F9A;font-family:'DM Serif Display',serif;"></p>
        </div>

        <button type="submit"
            style="padding:0.75rem 2rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
            Upload Bukti Pembayaran
        </button>
    </form>
</div>
@endif
@endif

{{-- Riwayat --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;">
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;">Riwayat
            Pembayaran</p>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#EEF4FB;">
                    <th
                        style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">
                        Jenis</th>
                    <th
                        style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">
                        Jumlah</th>
                    <th
                        style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">
                        Metode</th>
                    <th
                        style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">
                        Status</th>
                    <th
                        style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">
                        Tanggal</th>
                    <th
                        style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">
                        Bukti</th>
                    <th
                        style="padding:0.625rem 1rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;white-space:nowrap;">
                        Kwitansi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $pay)
                <tr style="border-bottom:1px solid #F8FAFC;">
                    <td style="padding:0.75rem 1rem;font-size:0.825rem;color:#1A2A3A;">{{ $pay->type_label }}</td>
                    <td style="padding:0.75rem 1rem;font-size:0.825rem;font-weight:600;color:#1A2A3A;">
                        {{ $pay->amount_formatted }}</td>
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;">{{ ucfirst($pay->payment_method) }}
                    </td>
                    <td style="padding:0.75rem 1rem;">
                        @php
                        $statusStyle = match($pay->status) {
                        'verified' => 'background:#F0FFF4;color:#276749;',
                        'rejected' => 'background:#FDECEA;color:#C0392B;',
                        default => 'background:#FEF8EC;color:#B8860B;',
                        };
                        $statusLabel = match($pay->status) {
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu',
                        };
                        @endphp
                        <span
                            style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;{{ $statusStyle }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td style="padding:0.75rem 1rem;font-size:0.8rem;color:#4A6580;white-space:nowrap;">
                        {{ $pay->created_at->format('d M Y') }}</td>
                    <td style="padding:0.75rem 1rem;">
                        @if ($pay->receipt_path)
                        <a href="{{ Storage::url($pay->receipt_path) }}" target="_blank"
                            style="font-size:0.7rem;color:#2A7FC1;font-weight:700;">Lihat</a>
                        @else
                        <span style="color:#B0CCDF;font-size:0.8rem;">—</span>
                        @endif
                    </td>
                    <td style="padding:0.75rem 1rem;">
                        @if ($pay->status === 'verified')
                        @php
                        $receipt = \App\Models\Receipt::where('source_type', 'payment')
                        ->whereJsonContains('payment_id_list', $pay->id)
                        ->first();
                        @endphp
                        @if ($receipt)
                        <br>
                        <a href="{{ route('member.kwitansi', $receipt->id) }}" target="_blank"
                            style="font-size:0.7rem;color:#276749;font-weight:700;">🧾 Kwitansi</a>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:2rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                        Belum ada riwayat pembayaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
function paymentForm() {
    return {
        selectedType: '{{ $bisaGabungan ?? false ? "gabungan" : (($bisaPangkal ?? false) ? "uang_pangkal" : "iuran_tahunan") }}',
        init() {},
        getAmount() {
            const map = {
                'gabungan': 'Rp 250.000',
                'uang_pangkal': 'Rp 130.000',
                'iuran_tahunan': 'Rp 120.000',
            };
            return map[this.selectedType] ?? '';
        },
        getLabel() {
            const map = {
                'gabungan': 'Uang Pangkal + Iuran Tahunan',
                'uang_pangkal': 'Uang Pangkal',
                'iuran_tahunan': 'Iuran Tahunan',
            };
            return map[this.selectedType] ?? '';
        },
    }
}
</script>
@endpush