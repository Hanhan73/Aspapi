@extends('layouts.daerah')
@php $title = 'Data Anggota'; @endphp

@section('content')

{{-- Filter --}}
<div class="bg-white border border-neutral-200 rounded-lg px-5 py-4 mb-5">
    <form method="GET" action="{{ route('daerah.members') }}" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama, email, institusi..."
               class="px-3 py-2 border border-neutral-200 rounded text-sm text-navy outline-none focus:border-primary w-64"/>

        <select name="status"
                class="px-3 py-2 border border-neutral-200 rounded text-sm text-navy outline-none focus:border-primary">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>

        <select name="dues"
                class="px-3 py-2 border border-neutral-200 rounded text-sm text-navy outline-none focus:border-primary">
            <option value="">Semua Iuran</option>
            <option value="lunas" {{ request('dues') === 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="belum" {{ request('dues') === 'belum' ? 'selected' : '' }}>Belum Lunas</option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('daerah.members') }}" class="btn btn-ghost btn-sm">Reset</a>

        <div class="ml-auto flex gap-2">
            <a href="{{ route('daerah.batch.form') }}" class="btn btn-sm"
               style="background:#E8B84B;color:#1A2A3A;border:none;">
                + Daftar Batch
            </a>
        </div>
    </form>
</div>

{{-- Info hasil filter --}}
@if (request()->hasAny(['search', 'status', 'dues']))
<div class="mb-4 text-sm text-neutral-500">
    Menampilkan <strong class="text-navy">{{ $members->total() }}</strong> anggota
    @if(request('search')) dengan kata kunci "<em>{{ request('search') }}</em>" @endif
    @if(request('status')) — status: <em>{{ request('status') }}</em> @endif
    @if(request('dues')) — iuran: <em>{{ request('dues') }}</em> @endif
</div>
@endif

{{-- Tabel --}}
<div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-neutral-50">
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Nama</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Institusi</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Kontak</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Iuran</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Status</th>
                <th class="px-4 py-3 text-left text-2xs font-bold tracking-widest uppercase text-primary">Terdaftar</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse ($members as $member)
            <tr class="hover:bg-blue-50 transition-colors cursor-pointer"
                onclick="openMemberModal({{ $member->id }})">
                <td class="px-4 py-3.5">
                    <p class="text-sm font-semibold text-navy">{{ $member->full_name }}</p>
                    <p class="text-xs text-neutral-400">{{ $member->email }}</p>
                </td>
                <td class="px-4 py-3.5 text-sm text-neutral-600">{{ $member->institution ?? '—' }}</td>
                <td class="px-4 py-3.5 text-sm text-neutral-600">{{ $member->phone ?? '—' }}</td>
                <td class="px-4 py-3.5">
                    @if ($member->dues_paid)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold bg-green-50 text-green-700">Lunas</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold bg-red-50 text-red-700">Belum</span>
                    @endif
                </td>
                <td class="px-4 py-3.5">
                    @php
                        $statusClass = match($member->status) {
                            'active'   => 'bg-green-50 text-green-700',
                            'pending'  => 'bg-yellow-50 text-yellow-700',
                            'inactive' => 'bg-neutral-100 text-neutral-500',
                            default    => 'bg-neutral-100 text-neutral-500',
                        };
                        $statusLabel = match($member->status) {
                            'active'   => 'Aktif',
                            'pending'  => 'Pending',
                            'inactive' => 'Tidak Aktif',
                            default    => ucfirst($member->status),
                        };
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </td>
                <td class="px-4 py-3.5 text-sm text-neutral-500">{{ $member->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-sm text-neutral-400">
                    @if (request()->hasAny(['search', 'status', 'dues']))
                        Tidak ada anggota yang cocok dengan filter.
                        <a href="{{ route('daerah.members') }}" class="text-primary font-semibold">Reset filter</a>
                    @else
                        Belum ada anggota di wilayah ini.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($members->hasPages())
<div class="mt-4 flex justify-end">{{ $members->links() }}</div>
@endif

{{-- ══════════════════════════════════════════════════════════════════
     DATA ANGGOTA — embed sebagai JSON untuk modal
══════════════════════════════════════════════════════════════════ --}}
<script>
const MEMBERS_DATA = {
    @foreach ($members as $member)
    {{ $member->id }}: {
        id:              {{ $member->id }},
        full_name:       @json($member->full_name ?? '—'),
        email:           @json($member->email ?? '—'),
        member_number:   @json($member->member_number ?? null),
        photo:           @json($member->photo ? Storage::url($member->photo) : null),
        nik:             @json($member->nik ?? '—'),
        gender:          @json($member->gender ?? '—'),
        birth_place:     @json($member->birth_place ?? '—'),
        birth_date:      @json($member->birth_date ? $member->birth_date->format('d M Y') : '—'),
        last_education:  @json($member->last_education_label ?? '—'),
        phone:           @json($member->phone ?? '—'),
        institution:     @json($member->institution ?? '—'),
        occupation:      @json($member->occupation ?? '—'),
        position:        @json($member->position ?? '—'),
        province:        @json($member->provinceModel?->name ?? $member->province ?? '—'),
        city:            @json($member->cityModel?->name ?? $member->city ?? '—'),
        address:         @json($member->address ?? '—'),
        member_type:     @json($member->member_type_label ?? '—'),
        registration_type: @json($member->registration_type === 'lama' ? 'Anggota Lama' : 'Anggota Baru'),
        status:          @json($member->status ?? '—'),
        status_label:    @json($member->status_label ?? '—'),
        biodata_status:  @json($member->biodata_status ?? '—'),
        biodata_label:   @json($member->biodata_status_label ?? '—'),
        dues_paid:       {{ $member->dues_paid ? 'true' : 'false' }},
        registered_at:   @json($member->registered_at ? $member->registered_at->format('d M Y') : '—'),
        is_batch:        {{ $member->is_batch ? 'true' : 'false' }},
        payments: [
            @foreach ($member->payments as $payment)
            @php
                $paymentTypeLabel   = $payment->type === 'uang_pangkal' ? 'Uang Pangkal' : 'Iuran Tahunan ' . $payment->payment_year;
                $paymentAmountLabel = 'Rp ' . number_format($payment->amount, 0, ',', '.');
                $paymentStatusLabel = $payment->status === 'verified' ? 'Terverifikasi' : ($payment->status === 'rejected' ? 'Ditolak' : 'Pending');
            @endphp
            {
                type:         @json($paymentTypeLabel),
                amount:       @json($paymentAmountLabel),
                status:       @json($payment->status),
                status_label: @json($paymentStatusLabel),
                date:         @json($payment->created_at->format('d M Y')),
                reject_reason:@json($payment->reject_reason ?? null),
            },
            @endforeach
        ],
    },
    @endforeach
};
</script>

{{-- ══════════════════════════════════════════════════════════════════
     MODAL: Detail Anggota
══════════════════════════════════════════════════════════════════ --}}
<div id="modal-member-detail"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:200;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#fff;border-radius:10px;width:720px;max-width:98vw;max-height:90vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.2);">

        {{-- Modal Header --}}
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #EEF4FB;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div id="md-avatar" style="width:48px;height:48px;border-radius:50%;background:#EEF4FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;border:2px solid #D6E8F7;"></div>
                <div>
                    <p id="md-name" style="font-family:'DM Serif Display',serif;font-size:1.1rem;color:#1A2A3A;margin:0;"></p>
                    <p id="md-email" style="font-size:0.78rem;color:#5C6B78;margin:0.1rem 0 0;"></p>
                    <p id="md-nomer" style="font-size:0.72rem;font-family:monospace;color:#2A7FC1;font-weight:700;margin:0.1rem 0 0;letter-spacing:0.08em;display:none;"></p>
                </div>
            </div>
            <button onclick="closeMemberModal()"
                    style="background:none;border:none;font-size:1.3rem;color:#8A97A4;cursor:pointer;line-height:1;">✕</button>
        </div>

        {{-- Modal Body --}}
        <div style="overflow-y:auto;flex:1;padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

                {{-- Kolom Kiri --}}
                <div style="display:flex;flex-direction:column;gap:1.25rem;">

                    {{-- Identitas --}}
                    <div>
                        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;margin:0 0 0.75rem;padding-bottom:0.4rem;border-bottom:1px solid #EEF4FB;">
                            Identitas
                        </p>
                        <div style="display:flex;flex-direction:column;gap:0.6rem;" id="md-identity"></div>
                    </div>

                    {{-- Kontak & Lokasi --}}
                    <div>
                        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;margin:0 0 0.75rem;padding-bottom:0.4rem;border-bottom:1px solid #EEF4FB;">
                            Kontak & Lokasi
                        </p>
                        <div style="display:flex;flex-direction:column;gap:0.6rem;" id="md-contact"></div>
                    </div>

                    {{-- Keanggotaan --}}
                    <div>
                        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;margin:0 0 0.75rem;padding-bottom:0.4rem;border-bottom:1px solid #EEF4FB;">
                            Keanggotaan
                        </p>
                        <div style="display:flex;flex-direction:column;gap:0.6rem;" id="md-membership"></div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div style="display:flex;flex-direction:column;gap:1.25rem;">

                    {{-- Status badge --}}
                    <div style="background:#F8FAFC;border:1px solid #EEF4FB;border-radius:8px;padding:1rem;">
                        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#8A97A4;margin:0 0 0.75rem;">
                            Status
                        </p>
                        <div style="display:flex;flex-direction:column;gap:0.5rem;" id="md-status-section"></div>
                    </div>

                    {{-- Riwayat Pembayaran --}}
                    <div>
                        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;margin:0 0 0.75rem;padding-bottom:0.4rem;border-bottom:1px solid #EEF4FB;">
                            Riwayat Pembayaran
                        </p>
                        <div id="md-payments"></div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div style="padding:1rem 1.5rem;border-top:1px solid #EEF4FB;display:flex;justify-content:flex-end;flex-shrink:0;background:#F8FAFC;">
            <button onclick="closeMemberModal()"
                    style="padding:0.625rem 1.5rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.75rem;font-weight:700;color:#4A6580;background:#fff;cursor:pointer;">
                Tutup
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
function openMemberModal(id) {
    const m = MEMBERS_DATA[id];
    if (!m) return;

    // ── Avatar
    const avatar = document.getElementById('md-avatar');
    if (m.photo) {
        avatar.innerHTML = `<img src="${m.photo}" style="width:100%;height:100%;object-fit:cover;"/>`;
    } else {
        avatar.innerHTML = `<span style="font-size:1.3rem;font-weight:700;color:#2A7FC1;">${(m.full_name || '?')[0].toUpperCase()}</span>`;
    }

    // ── Header
    document.getElementById('md-name').textContent  = m.full_name;
    document.getElementById('md-email').textContent = m.email;
    const nomerEl = document.getElementById('md-nomer');
    if (m.member_number) {
        nomerEl.textContent = 'No. ' + m.member_number;
        nomerEl.style.display = 'block';
    } else {
        nomerEl.style.display = 'none';
    }

    // ── Helper
    const genderLabel = m.gender === 'L' ? 'Laki-laki' : m.gender === 'P' ? 'Perempuan' : '—';
    const row = (label, value) => `
        <div style="display:flex;gap:0.5rem;justify-content:space-between;font-size:0.82rem;">
            <span style="color:#8A97A4;white-space:nowrap;flex-shrink:0;">${label}</span>
            <span style="color:#1A2A3A;font-weight:500;text-align:right;">${value || '—'}</span>
        </div>`;

    // ── Identitas
    document.getElementById('md-identity').innerHTML = [
        row('NIK',           m.nik),
        row('Jenis Kelamin', genderLabel),
        row('Tempat Lahir',  m.birth_place),
        row('Tanggal Lahir', m.birth_date),
        row('Pendidikan',    m.last_education),
    ].join('');

    // ── Kontak & Lokasi
    document.getElementById('md-contact').innerHTML = [
        row('No. Telepon',    m.phone),
        row('Institusi',      m.institution),
        row('Jabatan/Prodi',  m.position || m.occupation),
        row('Provinsi',       m.province),
        row('Kota',           m.city),
        row('Alamat',         m.address),
    ].join('');

    // ── Keanggotaan
    document.getElementById('md-membership').innerHTML = [
        row('Jenis Anggota',   m.member_type),
        row('Tipe Registrasi', m.registration_type),
        row('Terdaftar',       m.registered_at),
        row('Sumber',          m.is_batch ? 'Pendaftaran Batch' : 'Mandiri'),
    ].join('');

    // ── Status section
    const statusColor = { active: '#276749', pending: '#B8860B', inactive: '#5C6B78', rejected: '#C0392B' };
    const statusBg    = { active: '#F0FFF4', pending: '#FEF8EC', inactive: '#F0F2F4', rejected: '#FDECEA' };
    const bdColor     = { verified: '#276749', rejected: '#C0392B', pending: '#B8860B' };
    const bdBg        = { verified: '#F0FFF4', rejected: '#FDECEA', pending: '#FEF8EC' };

    const badge = (label, color, bg) =>
        `<span style="display:inline-block;font-size:0.7rem;font-weight:700;padding:0.25rem 0.625rem;border-radius:3px;background:${bg};color:${color};">${label}</span>`;

    document.getElementById('md-status-section').innerHTML = `
        <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.82rem;">
            <span style="color:#8A97A4;">Status Anggota</span>
            ${badge(m.status_label, statusColor[m.status] || '#5C6B78', statusBg[m.status] || '#F0F2F4')}
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.82rem;">
            <span style="color:#8A97A4;">Biodata</span>
            ${badge(m.biodata_label, bdColor[m.biodata_status] || '#5C6B78', bdBg[m.biodata_status] || '#F0F2F4')}
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.82rem;">
            <span style="color:#8A97A4;">Iuran</span>
            ${m.dues_paid
                ? badge('Lunas', '#276749', '#F0FFF4')
                : badge('Belum Lunas', '#C0392B', '#FDECEA')}
        </div>`;

    // ── Pembayaran
    const paymentsEl = document.getElementById('md-payments');
    if (m.payments.length === 0) {
        paymentsEl.innerHTML = `<p style="font-size:0.82rem;color:#B0CCDF;text-align:center;padding:1rem 0;">Belum ada riwayat pembayaran.</p>`;
    } else {
        paymentsEl.innerHTML = m.payments.map(p => {
            const pc = { verified: '#276749', rejected: '#C0392B', pending: '#B8860B' };
            const pb = { verified: '#F0FFF4', rejected: '#FDECEA', pending: '#FEF8EC' };
            return `
            <div style="border:1px solid #EEF4FB;border-radius:6px;padding:0.75rem;margin-bottom:0.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;">
                    <div>
                        <p style="font-size:0.82rem;font-weight:600;color:#1A2A3A;margin:0 0 0.2rem;">${p.type}</p>
                        <p style="font-size:0.75rem;color:#5C6B78;margin:0;">${p.amount} &nbsp;·&nbsp; ${p.date}</p>
                        ${p.reject_reason ? `<p style="font-size:0.72rem;color:#C0392B;margin:0.25rem 0 0;">Ditolak: ${p.reject_reason}</p>` : ''}
                    </div>
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:3px;white-space:nowrap;background:${pb[p.status] || '#F0F2F4'};color:${pc[p.status] || '#5C6B78'};">
                        ${p.status_label}
                    </span>
                </div>
            </div>`;
        }).join('');
    }

    document.getElementById('modal-member-detail').style.display = 'flex';
}

function closeMemberModal() {
    document.getElementById('modal-member-detail').style.display = 'none';
}

// Tutup modal klik backdrop
document.getElementById('modal-member-detail').addEventListener('click', function(e) {
    if (e.target === this) closeMemberModal();
});
</script>
@endpush

@endsection