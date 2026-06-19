{{-- resources/views/daerah/verify.blade.php --}}
@extends('layouts.daerah')
@php $title = 'Verifikasi Biodata'; @endphp

@section('content')

{{-- Summary --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #E8B84B;">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;line-height:1;">{{ $pendingCount }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.375rem;">Menunggu Verifikasi</p>
    </div>
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;border-top:3px solid #C0392B;">
        <p style="font-size:2rem;font-family:'DM Serif Display',serif;color:#1A2A3A;line-height:1;">{{ $oldClaimCount }}</p>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-top:0.375rem;">Klaim Anggota Lama</p>
    </div>
</div>

{{-- Filter pencarian --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..."
               style="flex:1;min-width:180px;padding:0.5rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;"/>
        <button type="submit" style="padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">Cari</button>
        @if(request('search'))
        <a href="{{ route('daerah.verify.index') }}" style="padding:0.5rem 1rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">Reset</a>
        @endif
    </form>
</div>

{{-- Info --}}
<p style="font-size:0.8rem;color:#4A6580;margin-bottom:0.875rem;">
    Menampilkan <strong style="color:#1A2A3A;">{{ $members->total() }}</strong> anggota yang menunggu verifikasi biodata.
</p>

{{-- Table --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Anggota</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Tipe</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Klaim Lama</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Didaftarkan</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $member)
            <tr style="border-bottom:1px solid #EEF4FB;"
                onmouseover="this.style.background='#F8FAFC'"
                onmouseout="this.style.background='#fff'">

                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        @if ($member->photo)
                        <img src="{{ Storage::url($member->photo) }}"
                             style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #D6E8F7;flex-shrink:0;"/>
                        @else
                        <div style="width:36px;height:36px;border-radius:50%;background:#EEF4FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:0.85rem;font-weight:700;color:#2A7FC1;">{{ strtoupper(substr($member->full_name ?? '?', 0, 1)) }}</span>
                        </div>
                        @endif
                        <div>
                            <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0;">{{ $member->full_name }}</p>
                            <p style="font-size:0.72rem;color:#B0CCDF;margin:0;">{{ $member->email }}</p>
                        </div>
                    </div>
                </td>

                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;
                        {{ $member->registration_type === 'baru' ? 'background:#EEF4FB;color:#2A7FC1;' : 'background:#FEF8EC;color:#B8860B;' }}">
                        {{ $member->registration_type === 'baru' ? 'Baru' : 'Lama' }}
                    </span>
                </td>

                <td style="padding:0.875rem 1rem;">
                    @if ($member->claims_old_member)
                    <span style="font-size:0.72rem;color:#B8860B;font-weight:600;">Sejak {{ $member->claimed_join_year }}</span>
                    @else
                    <span style="color:#B0CCDF;font-size:0.8rem;">—</span>
                    @endif
                </td>

                <td style="padding:0.875rem 1rem;font-size:0.8rem;color:#4A6580;">
                    {{ $member->created_at->format('d M Y') }}
                </td>

                <td style="padding:0.875rem 1rem;">
                    <button onclick="openVerifyModal({{ $member->id }})"
                            style="font-size:0.65rem;font-weight:700;padding:0.3rem 0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:3px;cursor:pointer;">
                        Lihat &amp; Verifikasi
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                    {{ request('search') ? 'Tidak ada hasil untuk pencarian ini.' : '🎉 Semua biodata sudah diverifikasi.' }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($members->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:flex-end;">{{ $members->links() }}</div>
@endif

{{-- ══ DATA EMBED ══ --}}
<script>
const VERIFY_DATA = {
    @foreach ($members as $member)
    {{ $member->id }}: {
        id:                  {{ $member->id }},
        full_name:           @json($member->full_name ?? '—'),
        email:               @json($member->email ?? '—'),
        photo:               @json($member->photo ? Storage::url($member->photo) : null),
        nik:                 @json($member->nik ?? null),
        gender:              @json($member->gender ?? null),
        birth_place:         @json($member->birth_place ?? null),
        birth_date:          @json($member->birth_date ? $member->birth_date->format('d M Y') : null),
        last_education:      @json($member->last_education_label ?? $member->last_education ?? null),
        phone:               @json($member->phone ?? null),
        institution:         @json($member->institution ?? null),
        occupation:          @json($member->occupation ?? null),
        position:            @json($member->position ?? null),
        province:            @json($member->provinceModel?->name ?? $member->province ?? null),
        city:                @json($member->cityModel?->name ?? $member->city ?? null),
        address:             @json($member->address ?? null),
        member_type:         @json($member->member_type_label ?? null),
        registration_type:   @json($member->registration_type),
        claims_old_member:   {{ $member->claims_old_member ? 'true' : 'false' }},
        claimed_join_year:   @json($member->claimed_join_year ?? null),
        is_batch:            {{ $member->is_batch ? 'true' : 'false' }},
        registered_at:       @json($member->created_at->format('d M Y')),
        biodata_complete:    {{ $member->isBiodataComplete() ? 'true' : 'false' }},
        approve_url:         @json($member->claims_old_member
                                ? route('daerah.verify.approve-old', $member->id)
                                : route('daerah.verify.approve', $member->id)),
        reject_url:          @json(route('daerah.verify.reject', $member->id)),
    },
    @endforeach
};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
</script>

{{-- ══ MODAL VERIFIKASI ══ --}}
<div id="modal-verify"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:200;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#fff;border-radius:10px;width:680px;max-width:98vw;max-height:92vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.2);">

        {{-- Header modal --}}
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #EEF4FB;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div id="vm-avatar" style="width:48px;height:48px;border-radius:50%;background:#EEF4FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;border:2px solid #D6E8F7;"></div>
                <div>
                    <p id="vm-name" style="font-family:'DM Serif Display',serif;font-size:1.1rem;color:#1A2A3A;margin:0;"></p>
                    <p id="vm-email" style="font-size:0.78rem;color:#5C6B78;margin:0.1rem 0 0;"></p>
                </div>
            </div>
            <button onclick="closeVerifyModal()"
                    style="width:32px;height:32px;border-radius:50%;border:none;background:#EEF4FB;color:#4A6580;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;">✕</button>
        </div>

        {{-- Body modal --}}
        <div style="overflow-y:auto;flex:1;padding:1.5rem;">

            {{-- Banner klaim lama --}}
            <div id="vm-banner-old" style="display:none;background:#FEF8EC;border:1px solid #E8B84B;border-radius:6px;padding:0.875rem 1rem;margin-bottom:1.25rem;">
                <p style="font-size:0.8rem;font-weight:700;color:#B8860B;margin:0;">⚠ Klaim Anggota Lama</p>
                <p id="vm-banner-old-text" style="font-size:0.78rem;color:#8B6914;margin:0.25rem 0 0;"></p>
            </div>

            {{-- Banner biodata tidak lengkap --}}
            <div id="vm-banner-incomplete" style="display:none;background:#FFF8EE;border:1px solid #E8B84B;border-left:3px solid #E8B84B;border-radius:4px;padding:0.75rem 1rem;margin-bottom:1.25rem;">
                <p style="font-size:0.8rem;color:#8B6914;margin:0;">⚠ Anggota belum melengkapi semua data biodata yang diperlukan.</p>
            </div>

            {{-- Grid data --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

                {{-- Identitas Diri --}}
                <div style="background:#F8FAFC;border:1px solid #EEF4FB;border-radius:8px;padding:1rem;">
                    <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#8A97A4;margin:0 0 0.875rem;">Identitas Diri</p>
                    <div id="vm-identity" style="display:flex;flex-direction:column;gap:0.5rem;"></div>
                </div>

                {{-- Kontak & Lokasi --}}
                <div style="background:#F8FAFC;border:1px solid #EEF4FB;border-radius:8px;padding:1rem;">
                    <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#8A97A4;margin:0 0 0.875rem;">Kontak &amp; Lokasi</p>
                    <div id="vm-contact" style="display:flex;flex-direction:column;gap:0.5rem;"></div>
                </div>

                {{-- Keanggotaan --}}
                <div style="background:#F8FAFC;border:1px solid #EEF4FB;border-radius:8px;padding:1rem;">
                    <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#8A97A4;margin:0 0 0.875rem;">Keanggotaan</p>
                    <div id="vm-membership" style="display:flex;flex-direction:column;gap:0.5rem;"></div>
                </div>

                {{-- Pekerjaan --}}
                <div style="background:#F8FAFC;border:1px solid #EEF4FB;border-radius:8px;padding:1rem;">
                    <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#8A97A4;margin:0 0 0.875rem;">Pekerjaan</p>
                    <div id="vm-work" style="display:flex;flex-direction:column;gap:0.5rem;"></div>
                </div>

            </div>
        </div>

        {{-- Footer modal — tombol aksi --}}
        <div style="padding:1rem 1.5rem;border-top:1px solid #EEF4FB;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;background:#F8FAFC;gap:0.75rem;">
            <button onclick="closeVerifyModal()"
                    style="padding:0.625rem 1.25rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.75rem;font-weight:700;color:#4A6580;background:#fff;cursor:pointer;">
                Tutup
            </button>
            <div style="display:flex;gap:0.625rem;">
                <button id="vm-btn-reject" onclick="showRejectForm()"
                        style="padding:0.625rem 1.25rem;background:transparent;border:1.5px solid #C0392B;color:#C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">
                    ✕ Tolak Biodata
                </button>
                <button id="vm-btn-approve" onclick="submitApprove()"
                        style="padding:0.625rem 1.5rem;background:#276749;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">
                    ✓ Verifikasi
                </button>
            </div>
        </div>

    </div>
</div>

{{-- ══ MODAL TOLAK ══ --}}
<div id="modal-reject"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:300;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#fff;border-radius:8px;padding:2rem;width:440px;max-width:90vw;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:1.2rem;color:#1A2A3A;margin:0 0 1rem;">Tolak Biodata</h3>
        <p id="mr-name" style="font-size:0.82rem;color:#4A6580;margin:0 0 1rem;"></p>
        <form id="reject-form" method="POST">
            @csrf
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Alasan Penolakan *</label>
            <textarea name="reason" rows="4" required
                      style="width:100%;padding:0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;resize:vertical;"
                      placeholder="Jelaskan alasan biodata ditolak..."></textarea>
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
let currentMember = null;

const row = (label, value) => `
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;font-size:0.8rem;">
        <span style="color:#8A97A4;white-space:nowrap;flex-shrink:0;">${label}</span>
        <span style="color:${value ? '#1A2A3A' : '#D0D8E0'};font-weight:${value ? '500' : '400'};text-align:right;">${value || '—'}</span>
    </div>`;

function openVerifyModal(id) {
    const m = VERIFY_DATA[id];
    if (!m) return;
    currentMember = m;

    // Avatar
    const avatar = document.getElementById('vm-avatar');
    avatar.innerHTML = m.photo
        ? `<img src="${m.photo}" style="width:100%;height:100%;object-fit:cover;"/>`
        : `<span style="font-size:1.3rem;font-weight:700;color:#2A7FC1;">${(m.full_name||'?')[0].toUpperCase()}</span>`;

    document.getElementById('vm-name').textContent  = m.full_name;
    document.getElementById('vm-email').textContent = m.email;

    // Banner klaim lama
    const bannerOld = document.getElementById('vm-banner-old');
    if (m.claims_old_member) {
        document.getElementById('vm-banner-old-text').textContent =
            `Mengklaim bergabung sejak tahun ${m.claimed_join_year}. Verifikasi ini sekaligus mengkonfirmasi status anggota lama.`;
        bannerOld.style.display = 'block';
    } else {
        bannerOld.style.display = 'none';
    }

    // Banner biodata tidak lengkap
    document.getElementById('vm-banner-incomplete').style.display =
        m.biodata_complete ? 'none' : 'block';

    const genderLabel = m.gender === 'L' ? 'Laki-laki' : m.gender === 'P' ? 'Perempuan' : null;

    // Identitas
    document.getElementById('vm-identity').innerHTML = [
        row('NIK',           m.nik),
        row('Jenis Kelamin', genderLabel),
        row('Tempat Lahir',  m.birth_place),
        row('Tanggal Lahir', m.birth_date),
        row('Pendidikan',    m.last_education),
    ].join('');

    // Kontak & lokasi
    document.getElementById('vm-contact').innerHTML = [
        row('No. Telepon', m.phone),
        row('Provinsi',    m.province),
        row('Kota',        m.city),
        row('Alamat',      m.address),
    ].join('');

    // Keanggotaan
    document.getElementById('vm-membership').innerHTML = [
        row('Jenis Anggota',   m.member_type),
        row('Tipe Registrasi', m.registration_type === 'lama' ? 'Anggota Lama' : 'Anggota Baru'),
        row('Sumber',          m.is_batch ? 'Pendaftaran Batch' : 'Mandiri'),
        row('Tgl. Daftar',     m.registered_at),
    ].join('');

    // Pekerjaan
    document.getElementById('vm-work').innerHTML = [
        row('Institusi',   m.institution),
        row('Pekerjaan',   m.occupation),
        row('Jabatan',     m.position),
    ].join('');

    // Label tombol approve sesuai tipe
    document.getElementById('vm-btn-approve').textContent =
        m.claims_old_member ? `✓ Konfirmasi Anggota Lama (${m.claimed_join_year})` : '✓ Verifikasi';

    document.getElementById('modal-verify').style.display = 'flex';
}

function closeVerifyModal() {
    document.getElementById('modal-verify').style.display = 'none';
    currentMember = null;
}

function submitApprove() {
    if (!currentMember) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = currentMember.approve_url;
    form.innerHTML = `<input type="hidden" name="_token" value="${CSRF}">`;
    document.body.appendChild(form);
    form.submit();
}

function showRejectForm() {
    if (!currentMember) return;
    document.getElementById('mr-name').textContent = 'Anggota: ' + currentMember.full_name;
    document.getElementById('reject-form').action  = currentMember.reject_url;
    document.getElementById('modal-reject').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('modal-reject').style.display = 'none';
}

// Klik backdrop tutup modal
document.getElementById('modal-verify').addEventListener('click', function(e) {
    if (e.target === this) closeVerifyModal();
});
document.getElementById('modal-reject').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush

@endsection