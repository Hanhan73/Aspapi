@extends('layouts.member')
@php $title = 'Biodata Saya'; @endphp

@section('content')

@if ($member?->biodata_status === 'rejected')
<div style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.875rem;color:#922B21;">
    <strong>Biodata ditolak:</strong> {{ $member->biodata_reject_reason }}. Silakan perbaiki dan simpan ulang.
</div>
@endif

<form method="POST" action="{{ route('member.biodata.update') }}" enctype="multipart/form-data">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 280px;gap:1.5rem;align-items:start;">

        {{-- Kiri --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

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
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Jenis Anggota *</label>
                        <select name="member_type" required
                                style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;">
                            <option value="biasa"      {{ old('member_type', $member?->member_type) === 'biasa'      ? 'selected' : '' }}>Biasa</option>
                            <option value="luar_biasa" {{ old('member_type', $member?->member_type) === 'luar_biasa' ? 'selected' : '' }}>Luar Biasa</option>
                            <option value="kehormatan" {{ old('member_type', $member?->member_type) === 'kehormatan' ? 'selected' : '' }}>Kehormatan</option>
                        </select>
                    </div>
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

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
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

        </div>

        {{-- Kanan --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Simpan --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Status Biodata</p>
                <span style="display:inline-block;font-size:0.72rem;font-weight:700;padding:0.3rem 0.75rem;border-radius:3px;margin-bottom:1rem;
                    {{ $member?->biodata_status === 'verified' ? 'background:#F0FFF4;color:#276749;' : ($member?->biodata_status === 'rejected' ? 'background:#FDECEA;color:#C0392B;' : 'background:#FEF8EC;color:#B8860B;') }}">
                    {{ $member?->biodata_status === 'verified' ? 'Terverifikasi' : ($member?->biodata_status === 'rejected' ? 'Ditolak' : 'Menunggu Verifikasi') }}
                </span>
                <button type="submit"
                        style="width:100%;padding:0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                    Simpan Biodata
                </button>
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.5rem;text-align:center;">Setelah disimpan, Admin akan memverifikasi biodata Anda.</p>
            </div>

            {{-- Pas Foto --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">Pas Foto</label>
                <div id="photo-preview"
                     style="width:100%;height:200px;background:#EEF4FB;border-radius:4px;margin-bottom:0.75rem;overflow:hidden;display:flex;align-items:center;justify-content:center;border:1.5px dashed #D6E8F7;">
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
                <input type="file" name="photo" accept="image/*"
                       style="width:100%;font-size:0.8rem;color:#4A6580;"
                       onchange="previewPhoto(this)"/>
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.375rem;">JPG, PNG. Latar belakang putih. Maks 2MB.</p>
            </div>

        </div>
    </div>
</form>

@push('scripts')
<script>
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