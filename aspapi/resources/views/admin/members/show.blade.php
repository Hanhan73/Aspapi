@extends('layouts.admin')
@php $title = 'Detail Anggota'; @endphp

@section('content')

{{-- Flash --}}
@if (session('success'))
<div style="background:#F0FFF4;border-left:4px solid #276749;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;font-size:0.875rem;color:#276749;">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;font-size:0.875rem;color:#922B21;">
    {{ session('error') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

    {{-- Kolom Kiri --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Info Akun --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;">
            <div style="display:flex;align-items:center;gap:1.25rem;margin-bottom:1.5rem;">
                @if ($member->photo)
                    <img src="{{ Storage::url($member->photo) }}"
                         style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid #D6E8F7;flex-shrink:0;"/>
                @else
                    <div style="width:72px;height:72px;border-radius:50%;background:#EEF4FB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span style="font-size:1.75rem;font-weight:700;color:#2A7FC1;">
                            {{ strtoupper(substr($member->full_name ?? '?', 0, 1)) }}
                        </span>
                    </div>
                @endif
                <div>
                    <h2 style="font-family:'DM Serif Display',serif;font-size:1.25rem;color:#1A2A3A;margin:0 0 0.25rem;">
                        {{ $member->full_name ?? '—' }}
                    </h2>
                    <p style="font-size:0.8rem;color:#5C6B78;margin:0;">{{ $member->email ?? $member->user?->email }}</p>
                    @if ($member->member_number)
                        <p style="font-size:0.75rem;font-family:monospace;color:#2A7FC1;font-weight:700;margin:0.25rem 0 0;letter-spacing:0.08em;">
                            No. {{ $member->member_number }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Grid info --}}
            @php
                $infoRows = [
                    ['label' => 'NIK',            'value' => $member->nik ?? '—'],
                    ['label' => 'Jenis Kelamin',   'value' => $member->gender === 'L' ? 'Laki-laki' : ($member->gender === 'P' ? 'Perempuan' : '—')],
                    ['label' => 'No. Telepon',     'value' => $member->phone ?? '—'],
                    ['label' => 'Provinsi',        'value' => $member->provinceModel?->name ?? $member->province ?? '—'],
                    ['label' => 'Kota/Kabupaten',  'value' => $member->cityModel?->name ?? $member->city ?? '—'],
                    ['label' => 'Alamat',          'value' => $member->address ?? '—'],
                    ['label' => 'Institusi',       'value' => $member->institution ?? '—'],
                    ['label' => 'Jabatan/Prodi',   'value' => $member->position ?? $member->study_program ?? '—'],
                    ['label' => 'Jenis Anggota',   'value' => $member->member_type_label],
                    ['label' => 'Tipe Registrasi', 'value' => $member->registration_type === 'lama' ? 'Anggota Lama' : 'Anggota Baru'],
                ];
                if ($member->claims_old_member) {
                    $infoRows[] = ['label' => 'Klaim Sejak', 'value' => $member->claimed_join_year];
                }
            @endphp

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem 1.5rem;">
                @foreach ($infoRows as $row)
                <div>
                    <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#8A97A4;margin:0 0 0.2rem;">
                        {{ $row['label'] }}
                    </p>
                    <p style="font-size:0.85rem;color:#1A2A3A;margin:0;">{{ $row['value'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Verifikasi Biodata --}}
        <div id="biodata" style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin:0;">
                    Verifikasi Biodata
                </p>
                @php
                    $bdColor = match($member->biodata_status) {
                        'verified' => 'background:#F0FFF4;color:#276749;',
                        'rejected' => 'background:#FDECEA;color:#C0392B;',
                        default    => 'background:#FEF8EC;color:#B8860B;',
                    };
                @endphp
                <span style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.625rem;border-radius:2px;{{ $bdColor }}">
                    {{ $member->biodata_status_label }}
                </span>
            </div>

            @if ($member->biodata_status === 'rejected' && $member->biodata_reject_reason)
                <div style="background:#FDECEA;border-left:3px solid #C0392B;border-radius:4px;padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.8rem;color:#922B21;">
                    <strong>Alasan ditolak:</strong> {{ $member->biodata_reject_reason }}
                </div>
            @endif

            @if ($member->biodata_status !== 'verified')
                @if (!$member->isBiodataComplete())
                    <div style="background:#FFF8EE;border-left:3px solid #E8B84B;border-radius:4px;padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.8rem;color:#8B6914;">
                        Anggota belum melengkapi semua field biodata yang diperlukan.
                    </div>
                @endif

                <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                    {{-- Approve --}}
                    <form method="POST" action="{{ route('admin.member.verify.approve', $member->id) }}">
                        @csrf
                        <button type="submit"
                                style="padding:0.625rem 1.25rem;background:#276749;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;cursor:pointer;">
                            Setujui Biodata
                        </button>
                    </form>

                    {{-- Konfirmasi Anggota Lama --}}
                    @if ($member->claims_old_member)
                    <form method="POST" action="{{ route('admin.member.verify.approve-old', $member->id) }}">
                        @csrf
                        <button type="submit"
                                style="padding:0.625rem 1.25rem;background:#B8860B;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;cursor:pointer;">
                            Konfirmasi Anggota Lama
                        </button>
                    </form>
                    @endif

                    {{-- Reject --}}
                    <button onclick="document.getElementById('reject-biodata-modal').style.display='flex'"
                            style="padding:0.625rem 1.25rem;background:transparent;border:1.5px solid #C0392B;color:#C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;cursor:pointer;">
                        Tolak Biodata
                    </button>
                </div>
            @else
                <p style="font-size:0.8rem;color:#276749;">
                    ✓ Diverifikasi pada {{ $member->updated_at->format('d M Y') }}
                </p>
            @endif
        </div>

        {{-- Riwayat Pembayaran --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin:0;">
                    Riwayat Pembayaran
                </p>
            </div>
            @forelse ($member->payments as $payment)
            <div style="padding:1rem 1.25rem;border-bottom:1px solid #F8FAFC;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
                    <div>
                        <p style="font-size:0.85rem;font-weight:600;color:#1A2A3A;margin:0 0 0.25rem;">
                            {{ $payment->type === 'uang_pangkal' ? 'Uang Pangkal' : 'Iuran Tahunan ' . $payment->payment_year }}
                        </p>
                        <p style="font-size:0.78rem;color:#5C6B78;margin:0;">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            &nbsp;·&nbsp; {{ $payment->created_at->format('d M Y') }}
                        </p>
                        @if ($payment->reject_reason)
                            <p style="font-size:0.72rem;color:#C0392B;margin:0.25rem 0 0;">
                                Ditolak: {{ $payment->reject_reason }}
                            </p>
                        @endif
                    </div>
                    <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                        @php
                            $pyColor = match($payment->status) {
                                'verified' => 'background:#F0FFF4;color:#276749;',
                                'rejected' => 'background:#FDECEA;color:#C0392B;',
                                default    => 'background:#FEF8EC;color:#B8860B;',
                            };
                        @endphp
                        <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;{{ $pyColor }}">
                            {{ match($payment->status) { 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak', default => 'Pending' } }}
                        </span>
                        @if ($payment->receipt_path)
                            <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank"
                               style="font-size:0.7rem;color:#2A7FC1;font-weight:700;text-decoration:none;">
                                Bukti
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:2rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                Belum ada riwayat pembayaran.
            </div>
            @endforelse
        </div>

    </div>

    {{-- Kolom Kanan --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Status Card --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#8A97A4;margin:0 0 1rem;">
                Status Keanggotaan
            </p>
            @php
                $steps = [
                    ['done' => $member->user?->email_verified,             'label' => 'Email Terverifikasi'],
                    ['done' => $member->biodata_status === 'verified',     'label' => 'Biodata Diverifikasi'],
                    ['done' => $member->registration_type === 'baru'
                                ? $member->hasPaidUangPangkal()
                                : $member->hasPaidIuranTahunan(),          'label' => 'Pembayaran Lunas'],
                    ['done' => (bool) $member->member_number,              'label' => 'KTA Digenerate'],
                ];
            @endphp
            @foreach ($steps as $step)
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.5rem 0;{{ !$loop->last ? 'border-bottom:1px solid #F8FAFC;' : '' }}">
                <div style="width:20px;height:20px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;
                    {{ $step['done'] ? 'background:#276749;' : 'background:#F0F2F4;border:1.5px solid #D6E8F7;' }}">
                    @if ($step['done'])
                        <svg style="width:10px;height:10px;" fill="none" stroke="#fff" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    @endif
                </div>
                <p style="font-size:0.8rem;color:{{ $step['done'] ? '#1A2A3A' : '#B0CCDF' }};margin:0;font-weight:{{ $step['done'] ? '600' : '400' }};">
                    {{ $step['label'] }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- Foto --}}
        @if ($member->photo)
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#8A97A4;margin:0 0 0.75rem;">
                Pas Foto
            </p>
            <img src="{{ Storage::url($member->photo) }}"
                 style="width:100%;border-radius:4px;border:1px solid #D6E8F7;"/>
        </div>
        @endif

        {{-- Aksi --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#8A97A4;margin:0 0 1rem;">
                Aksi
            </p>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
                <a href="{{ route('admin.members.index') }}"
                   style="display:block;text-align:center;padding:0.625rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">
                    ← Kembali ke Daftar
                </a>
                <form method="POST" action="{{ route('admin.members.destroy', $member) }}"
                      onsubmit="return confirm('Hapus anggota ini secara permanen?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="width:100%;padding:0.625rem;background:transparent;border:1.5px solid #C0392B;color:#C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">
                        Hapus Anggota
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Modal Reject Biodata --}}
<div id="reject-biodata-modal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:8px;padding:2rem;width:440px;max-width:90vw;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:1.25rem;color:#1A2A3A;margin:0 0 1rem;">
            Tolak Biodata
        </h3>
        <form method="POST" action="{{ route('admin.member.verify.reject', $member->id) }}">
            @csrf
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                Alasan Penolakan *
            </label>
            <textarea name="reason" rows="4" required
                      style="width:100%;padding:0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;resize:vertical;"
                      placeholder="Jelaskan apa yang perlu diperbaiki..."
                      onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"></textarea>
            <div style="display:flex;gap:0.75rem;margin-top:1.25rem;justify-content:flex-end;">
                <button type="button"
                        onclick="document.getElementById('reject-biodata-modal').style.display='none'"
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

@endsection