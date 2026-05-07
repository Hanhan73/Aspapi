@extends('layouts.admin')
@php $title = 'Bayar Iuran Kolektif'; @endphp

@section('content')

<div style="background:#EEF4FB;border-left:4px solid #2A7FC1;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.825rem;color:#1A5F9A;">
    Pilih anggota yang iurannya akan dibayarkan secara kolektif, upload satu bukti transfer untuk semua, lalu kirim ke Bendahara untuk diverifikasi.
    <strong>Iuran tahunan: Rp 120.000/anggota.</strong>
</div>

<form method="POST" action="{{ route('daerah.pay.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

        {{-- Daftar Anggota --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;display:flex;align-items:center;justify-content:space-between;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;">Pilih Anggota</p>
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.75rem;color:#4A6580;">
                    <input type="checkbox" id="select-all" onchange="toggleAll(this)"/> Pilih Semua
                </label>
            </div>
            <div style="max-height:400px;overflow-y:auto;">
                @forelse ($members as $member)
                <label style="display:flex;align-items:center;gap:0.875rem;padding:0.875rem 1.25rem;border-bottom:1px solid #F8FAFC;cursor:pointer;"
                       onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#fff'">
                    <input type="checkbox" name="member_ids[]" value="{{ $member->id }}"
                           class="member-check"
                           style="width:16px;height:16px;accent-color:#2A7FC1;"/>
                    <div>
                        <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;">{{ $member->full_name }}</p>
                        <p style="font-size:0.72rem;color:#B0CCDF;">{{ $member->institution ?? '—' }}</p>
                    </div>
                    <span style="margin-left:auto;font-size:0.72rem;font-weight:700;color:#C0392B;">Rp 120.000</span>
                </label>
                @empty
                <div style="padding:2rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                    Semua anggota sudah lunas iuran tahun ini.
                </div>
                @endforelse
            </div>
            <div id="total-info" style="padding:1rem 1.25rem;border-top:1px solid #EEF4FB;background:#F8FAFC;display:none;">
                <p style="font-size:0.875rem;font-weight:700;color:#1A2A3A;">
                    Total: Rp <span id="total-amount">0</span>
                    (<span id="total-count">0</span> anggota)
                </p>
            </div>
        </div>

        {{-- Upload & Submit --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Tahun Iuran *</label>
                <select name="year" required
                        style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;margin-bottom:1rem;">
                    @for ($y = now()->year; $y >= 2010; $y--)
                    <option value="{{ $y }}" {{ $y === now()->year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Bukti Transfer *</label>
                <input type="file" name="receipt" accept="image/*,.pdf" required
                       style="width:100%;font-size:0.8rem;color:#4A6580;margin-bottom:0.375rem;"/>
                <p style="font-size:0.7rem;color:#B0CCDF;">JPG, PNG, PDF. Maks 5MB.</p>
            </div>

            <div style="background:#2A7FC1;border-radius:8px;padding:1.25rem;color:#fff;text-align:center;">
                <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#A8D4F5;margin-bottom:0.25rem;">Rekening Tujuan</p>
                <p style="font-size:1.1rem;font-weight:700;letter-spacing:0.08em;">1661531545</p>
                <p style="font-size:0.75rem;color:#A8D4F5;margin-top:0.25rem;">Bank BNI</p>
                <p style="font-size:0.75rem;color:#A8D4F5;">Sitti Hardiyanti Arhas</p>
            </div>

            <button type="submit"
                    style="width:100%;padding:0.875rem;background:#E8B84B;color:#1A2A3A;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                Kirim Pembayaran Kolektif
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
function toggleAll(master) {
    document.querySelectorAll('.member-check').forEach(cb => cb.checked = master.checked);
    updateTotal();
}

document.querySelectorAll('.member-check').forEach(cb => {
    cb.addEventListener('change', updateTotal);
});

function updateTotal() {
    const checked = document.querySelectorAll('.member-check:checked').length;
    document.getElementById('total-count').textContent = checked;
    document.getElementById('total-amount').textContent = (checked * 120000).toLocaleString('id-ID');
    document.getElementById('total-info').style.display = checked > 0 ? 'block' : 'none';
}
</script>
@endpush

@endsection