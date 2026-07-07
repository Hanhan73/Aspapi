@extends('layouts.member')
@php
$title = 'Biodata Saya';
$isImpersonating = session()->has('impersonator_id');

$isLocked = !$isImpersonating && in_array($member?->biodata_status, ['pending', 'verified']);
$isVerified = $member?->biodata_status === 'verified';
$isPending = $member?->biodata_status === 'pending';
$isRejected = $member?->biodata_status === 'rejected';
$isDraft = $member?->biodata_status === 'draft' || $member?->biodata_status === null;

$occupationOptions = ['Dosen', 'Guru', 'Praktisi', 'Lainnya'];
$currentOccupation = old('occupation', $member?->occupation);
$occupationInList = in_array($currentOccupation, $occupationOptions);
$selectedOccupation = $occupationInList ? $currentOccupation : ($currentOccupation ? 'Lainnya' : '');
$customOccupation = (!$occupationInList && $currentOccupation) ? $currentOccupation : '';

$b = fn($field) => $errors->has($field) ? '#C0392B' : '#D6E8F7';
@endphp

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.flatpickr-input.form-input { background: #fff !important; }
.flatpickr-day.selected { background: #2A7FC1 !important; border-color: #2A7FC1 !important; }
.flatpickr-day:hover { background: #EEF4FB !important; }
</style>
@endpush

@section('content')

{{-- ── Error Summary ── --}}
@if ($errors->any())
<div
    style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;">
    <p style="font-size:0.875rem;font-weight:700;color:#C0392B;margin-bottom:0.5rem;">✗ Terdapat kesalahan pada form:
    </p>
    <ul style="list-style:disc;padding-left:1.25rem;display:flex;flex-direction:column;gap:0.25rem;">
        @foreach ($errors->all() as $error)
        <li style="font-size:0.8rem;color:#922B21;">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ── Banner Status ── --}}
@if ($isVerified)
<div
    style="background:#F0FFF4;border-left:4px solid #276749;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
    <div>
        <p style="font-size:0.875rem;font-weight:700;color:#276749;">✓ Biodata Terverifikasi</p>
        <p style="font-size:0.8rem;color:#4A6580;margin-top:0.25rem;">Biodata Anda telah diverifikasi oleh Admin. Untuk
            mengubah, klik tombol Buka Kunci dan verifikasi ulang diperlukan.</p>
    </div>
</div>
@elseif ($isPending)
<div
    style="background:#FEF8EC;border-left:4px solid #E8B84B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
    <div>
        <p style="font-size:0.875rem;font-weight:700;color:#8B6914;">⏳ Menunggu Verifikasi Admin</p>
        <p style="font-size:0.8rem;color:#4A6580;margin-top:0.25rem;">Biodata Anda sedang ditinjau oleh Admin. Untuk
            mengubah, klik tombol Buka Kunci — Anda perlu verifikasi ulang setelahnya.</p>
    </div>
</div>
@elseif ($isRejected)
<div
    style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;">
    <p style="font-size:0.875rem;font-weight:700;color:#C0392B;">✗ Biodata Ditolak</p>
    <p style="font-size:0.8rem;color:#922B21;margin-top:0.25rem;"><strong>Alasan:</strong>
        {{ $member->biodata_reject_reason }}</p>
    <p style="font-size:0.8rem;color:#922B21;margin-top:0.25rem;">Silakan perbaiki dan simpan ulang untuk mengajukan
        verifikasi kembali.</p>
</div>
@endif

{{-- ── Modal Konfirmasi Unlock ── --}}
@if ($isLocked)
<div id="unlock-modal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div
        style="background:#fff;border-radius:8px;padding:2rem;max-width:440px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="text-align:center;margin-bottom:1.5rem;">
            <div
                style="width:56px;height:56px;background:#FEF8EC;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <svg style="width:28px;height:28px;color:#E8B84B;" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 style="font-size:1rem;font-weight:700;color:#1A2A3A;">Buka Kunci Biodata?</h3>
        </div>
        <div
            style="background:#FEF8EC;border-radius:6px;padding:1rem;margin-bottom:1.5rem;font-size:0.825rem;color:#8B6914;line-height:1.6;">
            <p style="font-weight:700;margin-bottom:0.5rem;">⚠ Perhatian sebelum melanjutkan:</p>
            <ul style="list-style:disc;padding-left:1.25rem;display:flex;flex-direction:column;gap:0.375rem;">
                <li>Status biodata akan kembali ke <strong>Draft</strong></li>
                <li>Setelah mengubah data, Anda <strong>wajib mengajukan verifikasi ulang</strong> ke Admin</li>
                @if ($isVerified)
                <li>Kartu anggota yang sudah terbit <strong>tidak akan berubah</strong> sampai verifikasi baru disetujui
                </li>
                @endif
                @if ($isPending)
                <li>Proses verifikasi yang sedang berjalan akan <strong>dibatalkan</strong></li>
                @endif
            </ul>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <button onclick="closeUnlockModal()"
                style="flex:1;padding:0.75rem;background:#F0F4F8;color:#4A6580;border:none;border-radius:4px;font-size:0.8rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;cursor:pointer;">
                Batal
            </button>
            <form method="POST" action="{{ route('member.biodata.unlock') }}" style="flex:1;">
                @csrf
                <button type="submit"
                    style="width:100%;padding:0.75rem;background:#C0392B;color:#fff;border:none;border-radius:4px;font-size:0.8rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;cursor:pointer;">
                    Ya, Buka Kunci
                </button>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ── Form ── --}}
<form method="POST" action="{{ route('member.biodata.update') }}" enctype="multipart/form-data" id="biodata-form">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 280px;gap:1.5rem;align-items:start;">

        {{-- ── Kolom Kiri ── --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            @if ($isLocked)
            {{-- Overlay kunci visual --}}
            <div
                style="background:#F8FAFC;border:2px dashed #D6E8F7;border-radius:8px;padding:2rem;text-align:center;color:#4A6580;">
                <svg style="width:36px;height:36px;margin:0 auto 0.75rem;color:#B0CCDF;" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <p style="font-size:0.875rem;font-weight:700;color:#1A2A3A;margin-bottom:0.375rem;">Biodata Terkunci</p>
                <p style="font-size:0.8rem;color:#4A6580;margin-bottom:1.25rem;">
                    @if ($isVerified) Biodata sudah terverifikasi.
                    @else Biodata sedang dalam proses verifikasi.
                    @endif
                    Klik "Buka Kunci" di sebelah kanan untuk mengubah.
                </p>
                <div
                    style="text-align:left;background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;display:grid;grid-template-columns:1fr 1fr;gap:0.75rem 1.5rem;">
                    @foreach ([
                    'Nama Lengkap' => $member?->full_name,
                    'Gelar Depan' => $member?->front_title ?: '—',
                    'Gelar Belakang' => $member?->back_title ?: '—',
                    'NIK' => $member?->nik,
                    'Tempat Lahir' => $member?->birth_place,
                    'Tanggal Lahir' => $member?->birth_date?->format('d M Y'),
                    'No. Telepon' => $member?->phone,
                    'Email' => $member?->email,
                    'Jenis Kelamin' => $member?->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                    'Pendidikan' => $member?->last_education_label,
                    'Provinsi' => $member?->provinceModel?->name,
                    'Kota' => $member?->cityModel?->name,
                    'ASPAPI Daerah' => $member?->registeredByRegion?->province
                                        ? 'ASPAPI ' . $member->registeredByRegion->province
                                        : '—',
                    'Pekerjaan' => $member?->occupation,
                    'Institusi' => $member?->institution,
                    ] as $label => $val)
                    <div>
                        <p
                            style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#B0CCDF;">
                            {{ $label }}</p>
                        <p style="font-size:0.8rem;color:#1A2A3A;margin-top:0.125rem;">{{ $val ?: '—' }}</p>
                    </div>
                    @endforeach
                    <div style="grid-column:1/-1;">
                        <p
                            style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#B0CCDF;">
                            Alamat</p>
                        <p style="font-size:0.8rem;color:#1A2A3A;margin-top:0.125rem;">{{ $member?->address ?: '—' }}
                        </p>
                    </div>
                </div>
            </div>

            @else
            {{-- ── Form editable ── --}}

            {{-- Data Pribadi --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;">
                <p
                    style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1.25rem;">
                    Data Pribadi</p>

                {{-- Nama Lengkap --}}
                <div style="margin-bottom:1rem;">
                    <label
                        style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Nama
                        Lengkap (tanpa gelar) *</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $member?->full_name) }}" required
                        style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('full_name') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#2A7FC1'"
                        onblur="this.style.borderColor='{{ $b('full_name') }}'" />
                    @error('full_name')
                    <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                    @enderror
                    @if (!$errors->has('full_name'))
                    <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.3rem;">Isi nama tanpa gelar. Gelar diisi di
                        field di bawah.</p>
                    @endif
                </div>

                {{-- Gelar --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Gelar
                            Depan</label>
                        <input type="text" name="front_title" value="{{ old('front_title', $member?->front_title) }}"
                            placeholder="Contoh: Dr., Prof., Ir."
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('front_title') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'"
                            onblur="this.style.borderColor='{{ $b('front_title') }}'" />
                        @error('front_title')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Gelar
                            Belakang</label>
                        <input type="text" name="back_title" value="{{ old('back_title', $member?->back_title) }}"
                            placeholder="Contoh: M.Pd., S.E., Ph.D."
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('back_title') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'"
                            onblur="this.style.borderColor='{{ $b('back_title') }}'" />
                        @error('back_title')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- NIK & Gender --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">NIK
                            (16 digit) *</label>
                        <input type="text" name="nik" value="{{ old('nik', $member?->nik) }}" maxlength="16" required
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('nik') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'"
                            onblur="this.style.borderColor='{{ $b('nik') }}'" />
                        @error('nik')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Jenis
                            Kelamin *</label>
                        <select name="gender" required
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('gender') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                            <option value="">Pilih</option>
                            <option value="L" {{ old('gender', $member?->gender) === 'L' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="P" {{ old('gender', $member?->gender) === 'P' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('gender')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tempat & Tanggal Lahir --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Tempat
                            Lahir *</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $member?->birth_place) }}"
                            required placeholder="Kota tempat lahir..."
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('birth_place') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'"
                            onblur="this.style.borderColor='{{ $b('birth_place') }}'" />
                        @error('birth_place')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Tanggal
                            Lahir *</label>
                        <input type="text" name="birth_date"
                            value="{{ old('birth_date', $member?->birth_date?->format('Y-m-d')) }}"
                            id="birth_date_picker" required
                            placeholder="DD/MM/YYYY"
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('birth_date') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;" />
                        @error('birth_date')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Telepon & Pendidikan --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">No.
                            Telepon *</label>
                        <input type="text" name="phone" value="{{ old('phone', $member?->phone) }}" required
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('phone') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'"
                            onblur="this.style.borderColor='{{ $b('phone') }}'" />
                        @error('phone')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Pendidikan
                            Terakhir *</label>
                        <select name="last_education" required
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('last_education') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                            <option value="">Pilih</option>
                            @foreach(['sd'=>'SD','smp'=>'SMP','sma'=>'SMA/SMK','d3'=>'D3','s1'=>'S1','s2'=>'S2','s3'=>'S3','profesi'=>'Profesi','lainnya'=>'Lainnya']
                            as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('last_education', $member?->last_education) === $val ? 'selected' : '' }}>
                                {{ $label }}</option>
                            @endforeach
                        </select>
                        @error('last_education')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label
                        style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Email
                        *</label>
                    <input type="email" name="email" id="email-input"
                        data-current-email="{{ $member?->email ?? $member?->user?->email }}"
                        value="{{ old('email', $member?->email ?? $member?->user?->email) }}" required
                        autocomplete="off"
                        style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('email') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#2A7FC1'"
                        onblur="this.style.borderColor='{{ $b('email') }}'" />
                    @error('email')
                    <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                    @enderror
                    <p id="email-checking" style="display:none;font-size:0.7rem;color:#B0CCDF;margin-top:0.3rem;">
                        Mengecek ketersediaan email...</p>
                    <p id="email-warning" style="display:none;font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">
                        ✗ Email ini sudah terdaftar untuk anggota lain. Gunakan email yang berbeda.</p>
                    <p id="email-ok" style="display:none;font-size:0.7rem;color:#276749;margin-top:0.3rem;">
                        ✓ Email tersedia.</p>
                </div>
            </div>

            {{-- Domisili --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;">
                <p
                    style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1.25rem;">
                    Domisili</p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Provinsi
                            *</label>
                        <select name="province_id" id="province-select" required
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('province_id') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onchange="loadCities(this.value)">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinces as $prov)
                            <option value="{{ $prov->id }}"
                                {{ old('province_id', $member?->province_id) == $prov->id ? 'selected' : '' }}>
                                {{ $prov->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('province_id')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Kota/Kabupaten
                            *</label>
                        <select name="city_id" id="city-select" required
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('city_id') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                            <option value="">Pilih Kota</option>
                            @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('city_id', $member?->city_id) == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('city_id')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label
                        style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Alamat
                        Lengkap *</label>
                    <textarea name="address" rows="3" required
                        style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('address') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;resize:vertical;"
                        onfocus="this.style.borderColor='#2A7FC1'"
                        onblur="this.style.borderColor='{{ $b('address') }}'">{{ old('address', $member?->address) }}</textarea>
                    @error('address')
                    <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pilihan ASPAPI Daerah --}}
                <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;margin-top:1.25rem;">
                    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1.25rem;">Keanggotaan Daerah</p>
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">ASPAPI Daerah</label>
                        <select name="registered_by_region_id"
                                style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('registered_by_region_id') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                            <option value="">— Belum pilih / Independen —</option>
                            @foreach ($regions as $region)
                            <option value="{{ $region->id }}"
                                {{ old('registered_by_region_id', $member?->registered_by_region_id) == $region->id ? 'selected' : '' }}>
                                ASPAPI {{ $region->province }}
                            </option>
                            @endforeach
                        </select>
                        @error('registered_by_region_id')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                        <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.3rem;">Pilih sesuai wilayah domisili Anda. Admin daerah akan ikut memverifikasi biodata Anda.</p>
                    </div>
                </div>
            </div>

            {{-- Profesi --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;">
                <p
                    style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1.25rem;">
                    Data Profesi / Akademik</p>

                <div style="margin-bottom:1rem;">
                    <label
                        style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Pekerjaan
                        / Profesi</label>
                    <select name="occupation_select" id="occupation-select" onchange="toggleOccupationOther(this.value)"
                        style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('occupation') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                        <option value="">Pilih</option>
                        <option value="Dosen" {{ $selectedOccupation === 'Dosen'    ? 'selected' : '' }}>Dosen</option>
                        <option value="Guru" {{ $selectedOccupation === 'Guru'     ? 'selected' : '' }}>Guru</option>
                        <option value="Praktisi" {{ $selectedOccupation === 'Praktisi' ? 'selected' : '' }}>Praktisi</option>
                        <option value="Lainnya" {{ $selectedOccupation === 'Lainnya'  ? 'selected' : '' }}>Lainnya</option>
                    </select>

                    <div id="occupation-other-wrap"
                        style="margin-top:0.625rem;{{ $selectedOccupation === 'Lainnya' ? '' : 'display:none;' }}">
                        <input type="text" id="occupation-other" placeholder="Tuliskan pekerjaan Anda..."
                            value="{{ $customOccupation }}"
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
                    </div>

                    <input type="hidden" name="occupation" id="occupation-hidden" value="{{ $currentOccupation }}">

                    @error('occupation')
                    <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Institusi
                            / Universitas</label>
                        <input type="text" name="institution" value="{{ old('institution', $member?->institution) }}"
                            placeholder="Nama universitas / instansi..."
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('institution') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'"
                            onblur="this.style.borderColor='{{ $b('institution') }}'" />
                        @error('institution')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Program
                            Studi / Unit Kerja</label>
                        <input type="text" name="position" value="{{ old('position', $member?->position) }}"
                            placeholder="Prodi / Unit Kerja di instansi..."
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid {{ $b('position') }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'"
                            onblur="this.style.borderColor='{{ $b('position') }}'" />
                        @error('position')
                        <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            @endif {{-- end else (editable) --}}

        </div>

        {{-- ── Kolom Kanan ── --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Status & Aksi --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
                <p
                    style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">
                    Status Biodata</p>

                @php
                $badgeStyle = match($member?->biodata_status) {
                'verified' => 'background:#F0FFF4;color:#276749;',
                'rejected' => 'background:#FDECEA;color:#C0392B;',
                'pending'  => 'background:#FEF8EC;color:#B8860B;',
                default    => 'background:#EEF4FB;color:#4A6580;',
                };
                $badgeLabel = match($member?->biodata_status) {
                'verified' => '✓ Terverifikasi',
                'rejected' => '✗ Ditolak',
                'pending'  => '⏳ Menunggu Verifikasi',
                default    => '✎ Draft — Belum Diajukan',
                };
                @endphp
                <span
                    style="display:inline-block;font-size:0.72rem;font-weight:700;padding:0.3rem 0.75rem;border-radius:3px;margin-bottom:1rem;{{ $badgeStyle }}">
                    {{ $badgeLabel }}
                </span>

                @if ($isLocked)
                <button type="button" onclick="openUnlockModal()"
                    style="width:100%;padding:0.75rem;background:#fff;color:#C0392B;border:1.5px solid #C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                    Buka Kunci & Edit
                </button>
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.5rem;text-align:center;">Perlu verifikasi ulang
                    setelah diedit.</p>
                @else
                <button type="submit" id="biodata-submit-btn"
                    style="width:100%;padding:0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                    {{ $isRejected ? 'Simpan & Ajukan Ulang' : 'Simpan & Ajukan Verifikasi' }}
                </button>
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.5rem;text-align:center;">
                    Admin akan memverifikasi biodata Anda.
                </p>
                @endif
            </div>

            {{-- Pas Foto --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
                <label
                    style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">Pas
                    Foto</label>
                <div id="photo-preview"
                    style="width:100%;aspect-ratio:3/4;background:#EEF4FB;border-radius:4px;margin-bottom:0.75rem;overflow:hidden;display:flex;align-items:center;justify-content:center;border:1.5px {{ $isLocked ? 'solid' : 'dashed' }} {{ $b('photo') }};">
                    @if ($member?->photo)
                    <img src="{{ Storage::url($member->photo) }}" style="width:100%;height:100%;object-fit:cover;" />
                    @else
                    <div style="text-align:center;">
                        <svg style="width:40px;height:40px;color:#B0CCDF;margin:0 auto;" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.5rem;">Belum ada foto</p>
                    </div>
                    @endif
                </div>
                @if (!$isLocked)
                <input type="file" name="photo" accept="image/*" style="width:100%;font-size:0.8rem;color:#4A6580;"
                    onchange="previewPhoto(this)" />
                @error('photo')
                <p style="font-size:0.7rem;color:#C0392B;margin-top:0.3rem;">{{ $message }}</p>
                @else
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.375rem;">Format 3×4. JPG/PNG, latar putih, maks
                    2MB.</p>
                @enderror
                @else
                <p style="font-size:0.72rem;color:#B0CCDF;text-align:center;">Buka kunci untuk mengubah foto.</p>
                @endif
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
flatpickr("#birth_date_picker", {
    locale: "id",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d/m/Y",
    allowInput: true,
    maxDate: new Date(new Date().setFullYear(new Date().getFullYear() - 17)),
    minDate: "1940-01-01",
});

function openUnlockModal() {
    document.getElementById('unlock-modal').style.display = 'flex';
}
function closeUnlockModal() {
    document.getElementById('unlock-modal').style.display = 'none';
}
document.getElementById('unlock-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeUnlockModal();
});

function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photo-preview').innerHTML =
                '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;"/>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function loadCities(provinceId) {
    const select = document.getElementById('city-select');
    select.innerHTML = '<option value="">Memuat...</option>';
    fetch('/api/cities/' + provinceId)
        .then(r => r.json())
        .then(cities => {
            select.innerHTML = '<option value="">Pilih Kota</option>';
            cities.forEach(c => {
                select.innerHTML += '<option value="' + c.id + '">' + c.name + '</option>';
            });
        });
}

function toggleOccupationOther(val) {
    const wrap   = document.getElementById('occupation-other-wrap');
    const other  = document.getElementById('occupation-other');
    const hidden = document.getElementById('occupation-hidden');
    if (val === 'Lainnya') {
        wrap.style.display = 'block';
        other.focus();
        hidden.value = other.value;
    } else {
        wrap.style.display = 'none';
        hidden.value = val;
    }
}

document.getElementById('occupation-other')?.addEventListener('input', function() {
    document.getElementById('occupation-hidden').value = this.value;
});

document.getElementById('biodata-form')?.addEventListener('submit', function() {
    const select = document.getElementById('occupation-select');
    const other  = document.getElementById('occupation-other');
    const hidden = document.getElementById('occupation-hidden');
    if (select && select.value === 'Lainnya') {
        hidden.value = other?.value || '';
    } else if (select) {
        hidden.value = select.value;
    }
});

// ── Realtime cek ketersediaan email ──
(function() {
    const emailInput   = document.getElementById('email-input');
    if (!emailInput) return;

    const checkingEl = document.getElementById('email-checking');
    const warningEl  = document.getElementById('email-warning');
    const okEl       = document.getElementById('email-ok');
    const submitBtn  = document.getElementById('biodata-submit-btn');

    let debounceTimer = null;
    let emailTaken    = false;

    function hideAllStatus() {
        checkingEl.style.display = 'none';
        warningEl.style.display  = 'none';
        okEl.style.display       = 'none';
    }

    function setEmailBorder(color) {
        emailInput.style.borderColor = color;
    }

    function checkEmail(value) {
        const currentEmail = emailInput.dataset.currentEmail || '';

        // Kalau email sama persis dengan yang sudah tersimpan (belum diubah), skip cek.
        if (value.trim().toLowerCase() === currentEmail.trim().toLowerCase()) {
            hideAllStatus();
            emailTaken = false;
            setEmailBorder('#D6E8F7');
            return;
        }

        // Validasi format dasar dulu sebelum hit server
        const isValidFormat = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        if (!isValidFormat) {
            hideAllStatus();
            emailTaken = false;
            return;
        }

        hideAllStatus();
        checkingEl.style.display = 'block';

        fetch('{{ route('member.biodata.check-email') }}?email=' + encodeURIComponent(value))
            .then(r => r.json())
            .then(data => {
                hideAllStatus();
                if (data.available) {
                    emailTaken = false;
                    okEl.style.display = 'block';
                    setEmailBorder('#2A7FC1');
                } else {
                    emailTaken = true;
                    warningEl.style.display = 'block';
                    setEmailBorder('#C0392B');
                }
            })
            .catch(() => {
                hideAllStatus();
            });
    }

    emailInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const value = this.value;
        debounceTimer = setTimeout(() => checkEmail(value), 500);
    });

    document.getElementById('biodata-form')?.addEventListener('submit', function(e) {
        if (emailTaken) {
            e.preventDefault();
            warningEl.style.display = 'block';
            setEmailBorder('#C0392B');
            emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            emailInput.focus();
        }
    });
})();
</script>
@endpush

@endsection