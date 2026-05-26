@extends('layouts.member')
@php
    $title = 'Biodata Saya';
    $isImpersonating = session()->has('impersonator_id');

    // Kalau diimpersonate, anggap selalu editable
    $isLocked   = !$isImpersonating && in_array($member?->biodata_status, ['pending', 'verified']);
    $isVerified = $member?->biodata_status === 'verified';
    $isPending  = $member?->biodata_status === 'pending';
    $isRejected = $member?->biodata_status === 'rejected';
    $isDraft    = $member?->biodata_status === 'draft' || $member?->biodata_status === null;
@endphp

@section('content')

{{-- ── Banner Status ── --}}
@if ($isVerified)
<div style="background:#F0FFF4;border-left:4px solid #276749;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
    <div>
        <p style="font-size:0.875rem;font-weight:700;color:#276749;">✓ Biodata Terverifikasi</p>
        <p style="font-size:0.8rem;color:#4A6580;margin-top:0.25rem;">Biodata Anda telah diverifikasi oleh Admin. Untuk mengubah, klik tombol Buka Kunci dan verifikasi ulang diperlukan.</p>
    </div>
</div>
@elseif ($isPending)
<div style="background:#FEF8EC;border-left:4px solid #E8B84B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
    <div>
        <p style="font-size:0.875rem;font-weight:700;color:#8B6914;">⏳ Menunggu Verifikasi Admin</p>
        <p style="font-size:0.8rem;color:#4A6580;margin-top:0.25rem;">Biodata Anda sedang ditinjau oleh Admin. Untuk mengubah, klik tombol Buka Kunci — Anda perlu verifikasi ulang setelahnya.</p>
    </div>
</div>
@elseif ($isRejected)
<div style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;">
    <p style="font-size:0.875rem;font-weight:700;color:#C0392B;">✗ Biodata Ditolak</p>
    <p style="font-size:0.8rem;color:#922B21;margin-top:0.25rem;"><strong>Alasan:</strong> {{ $member->biodata_reject_reason }}</p>
    <p style="font-size:0.8rem;color:#922B21;margin-top:0.25rem;">Silakan perbaiki dan simpan ulang untuk mengajukan verifikasi kembali.</p>
</div>
@endif

{{-- ── Modal Konfirmasi Unlock ── --}}
@if ($isLocked)
<div id="unlock-modal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:8px;padding:2rem;max-width:440px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="text-align:center;margin-bottom:1.5rem;">
            <div style="width:56px;height:56px;background:#FEF8EC;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <svg style="width:28px;height:28px;color:#E8B84B;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 style="font-size:1rem;font-weight:700;color:#1A2A3A;">Buka Kunci Biodata?</h3>
        </div>
        <div style="background:#FEF8EC;border-radius:6px;padding:1rem;margin-bottom:1.5rem;font-size:0.825rem;color:#8B6914;line-height:1.6;">
            <p style="font-weight:700;margin-bottom:0.5rem;">⚠ Perhatian sebelum melanjutkan:</p>
            <ul style="list-style:disc;padding-left:1.25rem;display:flex;flex-direction:column;gap:0.375rem;">
                <li>Status biodata akan kembali ke <strong>Draft</strong></li>
                <li>Setelah mengubah data, Anda <strong>wajib mengajukan verifikasi ulang</strong> ke Admin</li>
                @if ($isVerified)
                <li>Kartu anggota yang sudah terbit <strong>tidak akan berubah</strong> sampai verifikasi baru disetujui</li>
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

            {{-- Overlay kunci visual kalau locked --}}
            @if ($isLocked)
            <div style="background:#F8FAFC;border:2px dashed #D6E8F7;border-radius:8px;padding:2rem;text-align:center;color:#4A6580;">
                <svg style="width:36px;height:36px;margin:0 auto 0.75rem;color:#B0CCDF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <p style="font-size:0.875rem;font-weight:700;color:#1A2A3A;margin-bottom:0.375rem;">Biodata Terkunci</p>
                <p style="font-size:0.8rem;color:#4A6580;margin-bottom:1.25rem;">
                    @if ($isVerified) Biodata sudah terverifikasi.
                    @else Biodata sedang dalam proses verifikasi.
                    @endif
                    Klik "Buka Kunci" di sebelah kanan untuk mengubah.
                </p>

                {{-- Preview data (read-only) --}}
                <div style="text-align:left;background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;display:grid;grid-template-columns:1fr 1fr;gap:0.75rem 1.5rem;">
                    @foreach ([
                        'Nama Lengkap'  => $member?->full_name,
                        'NIK'           => $member?->nik,
                        'Tempat Lahir'  => $member?->birth_place,
                        'Tanggal Lahir' => $member?->birth_date?->format('d M Y'),
                        'No. Telepon'   => $member?->phone,
                        'Email'         => $member?->email,
                        'Jenis Kelamin' => $member?->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                        'Pendidikan'    => $member?->last_education_label,
                        'Provinsi'      => $member?->provinceModel?->name,
                        'Kota'          => $member?->cityModel?->name,
                        'Pekerjaan'     => $member?->occupation,
                        'Institusi'     => $member?->institution,
                    ] as $label => $val)
                    <div>
                        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#B0CCDF;">{{ $label }}</p>
                        <p style="font-size:0.8rem;color:#1A2A3A;margin-top:0.125rem;">{{ $val ?: '—' }}</p>
                    </div>
                    @endforeach
                    <div style="grid-column:1/-1;">
                        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#B0CCDF;">Alamat</p>
                        <p style="font-size:0.8rem;color:#1A2A3A;margin-top:0.125rem;">{{ $member?->address ?: '—' }}</p>
                    </div>
                </div>
            </div>

            @else
            {{-- ── Form editable (draft / rejected) ── --}}

            {{-- Data Pribadi --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1.25rem;">Data Pribadi</p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Nama Lengkap *</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $member?->full_name) }}" required
                               style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                               onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">NIK (16 digit) *</label>
                        <input type="text" name="nik" value="{{ old('nik', $member?->nik) }}" maxlength="16" required
                               style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                               onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Tempat Lahir *</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $member?->birth_place) }}" required
                               placeholder="Kota tempat lahir..."
                               style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                               onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Tanggal Lahir *</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $member?->birth_date?->format('Y-m-d')) }}" required
                               style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                               onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">No. Telepon *</label>
                        <input type="text" name="phone" value="{{ old('phone', $member?->phone) }}" required
                               style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                               onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Jenis Kelamin *</label>
                        <select name="gender" required
                                style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                            <option value="">Pilih</option>
                            <option value="L" {{ old('gender', $member?->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $member?->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Pendidikan Terakhir *</label>
                        <select name="last_education" required
                                style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                            <option value="">Pilih</option>
                            @foreach(['sd'=>'SD','smp'=>'SMP','sma'=>'SMA/SMK','d3'=>'D3','s1'=>'S1','s2'=>'S2','s3'=>'S3','profesi'=>'Profesi','lainnya'=>'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" {{ old('last_education', $member?->last_education) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $member?->email ?? $member?->user?->email) }}" required
                           style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                           onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                </div>
            </div>

            {{-- Domisili --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1.25rem;">Domisili</p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Provinsi *</label>
                        <select name="province_id" id="province-select" required
                                style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                                onchange="loadCities(this.value)">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinces as $prov)
                            <option value="{{ $prov->id }}" {{ old('province_id', $member?->province_id) == $prov->id ? 'selected' : '' }}>
                                {{ $prov->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Kota/Kabupaten *</label>
                        <select name="city_id" id="city-select" required
                                style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                            <option value="">Pilih Kota</option>
                            @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', $member?->city_id) == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Alamat Lengkap *</label>
                    <textarea name="address" rows="3" required
                              style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;resize:vertical;"
                              onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'">{{ old('address', $member?->address) }}</textarea>
                </div>
            </div>

            {{-- Profesi --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.5rem;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:1.25rem;">Data Profesi / Akademik</p>

                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Pekerjaan / Profesi</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $member?->occupation) }}"
                           placeholder="Contoh: Dosen, PNS, Dokter, Wiraswasta..."
                           style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                           onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Institusi / Universitas</label>
                        <input type="text" name="institution" value="{{ old('institution', $member?->institution) }}"
                               placeholder="Nama universitas / instansi..."
                               style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                               onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Program Studi / Jabatan</label>
                        <input type="text" name="position" value="{{ old('position', $member?->position) }}"
                               placeholder="Prodi / Jabatan di instansi..."
                               style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                               onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                    </div>
                </div>
            </div>
            @endif {{-- end else (editable) --}}

        </div>

        {{-- ── Kolom Kanan ── --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Status & Aksi --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">Status Biodata</p>

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
                <span style="display:inline-block;font-size:0.72rem;font-weight:700;padding:0.3rem 0.75rem;border-radius:3px;margin-bottom:1rem;{{ $badgeStyle }}">
                    {{ $badgeLabel }}
                </span>

                @if ($isLocked)
                    {{-- Tombol buka kunci --}}
                    <button type="button" onclick="openUnlockModal()"
                            style="width:100%;padding:0.75rem;background:#fff;color:#C0392B;border:1.5px solid #C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                        Buka Kunci & Edit
                    </button>
                    <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.5rem;text-align:center;">Perlu verifikasi ulang setelah diedit.</p>
                @else
                    {{-- Tombol simpan & ajukan verifikasi --}}
                    <button type="submit"
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
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">Pas Foto</label>
                <div id="photo-preview"
                     style="width:100%;height:200px;background:#EEF4FB;border-radius:4px;margin-bottom:0.75rem;overflow:hidden;display:flex;align-items:center;justify-content:center;border:1.5px {{ $isLocked ? 'solid #D6E8F7' : 'dashed #D6E8F7' }};">
                    @if ($member?->photo)
                        <img src="{{ Storage::url($member->photo) }}" style="width:100%;height:100%;object-fit:cover;"/>
                    @else
                        <div style="text-align:center;">
                            <svg style="width:40px;height:40px;color:#B0CCDF;margin:0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.5rem;">Belum ada foto</p>
                        </div>
                    @endif
                </div>
                @if (!$isLocked)
                <input type="file" name="photo" accept="image/*"
                       style="width:100%;font-size:0.8rem;color:#4A6580;"
                       onchange="previewPhoto(this)"/>
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.375rem;">JPG, PNG. Latar belakang putih. Maks 2MB.</p>
                @else
                <p style="font-size:0.72rem;color:#B0CCDF;text-align:center;">Buka kunci untuk mengubah foto.</p>
                @endif
            </div>

        </div>
    </div>
</form>

@push('scripts')
<script>
function openUnlockModal() {
    const modal = document.getElementById('unlock-modal');
    modal.style.display = 'flex';
}
function closeUnlockModal() {
    const modal = document.getElementById('unlock-modal');
    modal.style.display = 'none';
}
// Tutup modal kalau klik di luar
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
</script>
@endpush

@endsection