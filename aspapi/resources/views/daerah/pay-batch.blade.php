@extends('layouts.daerah')
@php $title = 'Bayar Iuran Kolektif'; @endphp

@section('content')

<div class="bg-blue-50 border-l-4 border-primary rounded px-4 py-3 mb-6 text-sm text-blue-800">
    Pilih anggota yang iurannya akan dibayarkan secara kolektif, upload satu bukti transfer untuk semua,
    lalu kirim ke Bendahara untuk diverifikasi. <strong>Iuran tahunan: Rp 120.000/anggota.</strong>
    <br>
    <span class="text-xs mt-1 block text-blue-700">
        Anggota bertanda <span class="font-bold text-purple-700">⊕ Baru</span> akan langsung diaktifkan setelah pembayaran diverifikasi.
        Anggota bertanda <span class="font-bold text-orange-600">↻ Perpanjang</span> akan diperpanjang masa aktifnya.
    </span>
</div>

<form method="POST" action="{{ route('daerah.pay.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid gap-6" style="grid-template-columns: 1fr 300px; align-items: start;">

        {{-- Daftar Anggota --}}
        <div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-neutral-100">
                <div>
                    <p class="text-2xs font-bold uppercase tracking-widest text-neutral-500">Pilih Anggota</p>
                    <p class="text-xs text-neutral-400 mt-0.5">
                        {{ $members->where('status', 'pending')->count() }} baru &nbsp;·&nbsp;
                        {{ $members->where('status', 'active')->count() }} perpanjang
                    </p>
                </div>
                <label class="flex items-center gap-2 cursor-pointer text-xs text-neutral-500 select-none">
                    <input type="checkbox" id="select-all" onchange="toggleAll(this)"
                           class="w-4 h-4 accent-primary"/>
                    Pilih Semua
                </label>
            </div>

            <div class="overflow-y-auto" style="max-height:460px;">
                @forelse ($members as $member)
                @php $isNew = $member->status === 'pending'; @endphp
                <label class="flex items-center gap-3.5 px-5 py-3.5 border-b border-neutral-50 cursor-pointer hover:bg-neutral-50 transition-colors {{ $isNew ? 'bg-purple-50/40' : '' }}">
                    <input type="checkbox" name="member_ids[]" value="{{ $member->id }}"
                           class="member-check w-4 h-4 accent-primary flex-shrink-0"/>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-navy truncate">{{ $member->full_name_with_title ?? '—' }}</p>
                            @if ($isNew)
                                <span style="font-size:0.6rem;font-weight:700;padding:0.1rem 0.4rem;border-radius:2px;background:#F5F0FF;color:#9B59B6;white-space:nowrap;">⊕ BARU</span>
                            @else
                                <span style="font-size:0.6rem;font-weight:700;padding:0.1rem 0.4rem;border-radius:2px;background:#FEF3E8;color:#E67E22;white-space:nowrap;">↻ PERPANJANG</span>
                            @endif
                        </div>
                        <p class="text-xs text-neutral-400 truncate">
                            {{ $member->institution ?? '—' }}
                            @if (!$isNew && $member->active_until)
                                &nbsp;· Kadaluarsa {{ $member->active_until->format('d M Y') }}
                            @endif
                        </p>
                    </div>
                    <span class="text-xs font-bold text-red-600 flex-shrink-0">Rp 120.000</span>
                </label>
                @empty
                <div class="px-5 py-10 text-center text-sm text-neutral-400">
                    Tidak ada anggota yang perlu membayar iuran saat ini.<br>
                    <span class="text-xs">Semua anggota sudah lunas atau ada yang sedang menunggu verifikasi.</span>
                </div>
                @endforelse
            </div>

            <div id="total-info" class="hidden px-5 py-3.5 border-t border-neutral-100 bg-neutral-50">
                <p class="text-sm font-bold text-navy">
                    Total: Rp <span id="total-amount">0</span>
                    <span class="font-normal text-neutral-500 text-xs">(<span id="total-count">0</span> anggota)</span>
                </p>
            </div>
        </div>

        {{-- Sidebar: Upload & Info --}}
        <div class="flex flex-col gap-4">

            {{-- Form upload --}}
            <div class="bg-white border border-neutral-200 rounded-lg p-5">
                <div>
                    <label class="block text-2xs font-bold tracking-widest uppercase text-neutral-500 mb-1.5">
                        Bukti Transfer *
                    </label>
                    <input type="file" name="receipt" accept="image/*,.pdf" required
                           class="w-full text-sm text-neutral-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-neutral-100 file:text-neutral-600 hover:file:bg-neutral-200 cursor-pointer"/>
                    <p class="text-xs text-neutral-400 mt-1">JPG, PNG, PDF. Maks 5MB.</p>
                </div>
            </div>

            {{-- Rekening tujuan --}}
            <div class="bg-primary rounded-lg p-5 text-white text-center">
                <p class="text-2xs font-bold uppercase tracking-widest text-blue-200 mb-1">Rekening Tujuan</p>
                <p class="text-xl font-bold tracking-widest mt-1">1661531545</p>
                <p class="text-xs text-blue-200 mt-1.5">Bank BNI</p>
                <p class="text-xs text-blue-200">Sitti Hardiyanti Arhas</p>
            </div>

            {{-- Error --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <button type="submit"
                    class="w-full py-3 rounded font-bold text-xs uppercase tracking-widest transition-opacity hover:opacity-90"
                    style="background:#E8B84B;color:#1A2A3A;border:none;cursor:pointer;">
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
document.querySelectorAll('.member-check').forEach(cb => cb.addEventListener('change', updateTotal));

function updateTotal() {
    const checked = document.querySelectorAll('.member-check:checked').length;
    document.getElementById('total-count').textContent = checked;
    document.getElementById('total-amount').textContent = (checked * 120000).toLocaleString('id-ID');
    document.getElementById('total-info').classList.toggle('hidden', checked === 0);
}
</script>
@endpush

@endsection