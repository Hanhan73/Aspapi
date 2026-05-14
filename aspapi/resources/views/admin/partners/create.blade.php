@extends('layouts.admin')
@section('title', 'Tambah Mitra')

@php
    $breadcrumbs = [
        ['label' => 'Mitra', 'url' => route('admin.partners.index')],
        ['label' => 'Tambah Mitra', 'url' => '#'],
    ];
@endphp

@section('content')

<div style="margin-bottom:1.5rem;">
    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#C0392B;">Kelola Kemitraan</p>
    <h2 style="font-family:'DM Serif Display',serif;font-size:1.5rem;color:#1A2A3A;margin-top:0.125rem;">Tambah Mitra Baru</h2>
</div>

<form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

        {{-- Kiri --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Nama --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Nama Mitra <span style="color:#C0392B;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="Contoh: Universitas Indonesia" required
                       style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                @error('name') <p style="font-size:0.75rem;color:#C0392B;margin-top:0.375rem;">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Kategori <span style="color:#C0392B;">*</span>
                </label>
                <select name="category" required
                        style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;background:#fff;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'">
                    <option value="">— Pilih Kategori —</option>
                    @foreach ($categories as $value => $label)
                        <option value="{{ $value }}" {{ old('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category') <p style="font-size:0.75rem;color:#C0392B;margin-top:0.375rem;">{{ $message }}</p> @enderror
            </div>

            {{-- Profil Singkat --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Profil Singkat
                </label>
                <textarea name="profile" rows="5"
                          placeholder="Deskripsi singkat tentang mitra..."
                          style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;resize:vertical;box-sizing:border-box;"
                          onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'">{{ old('profile') }}</textarea>
                @error('profile') <p style="font-size:0.75rem;color:#C0392B;margin-top:0.375rem;">{{ $message }}</p> @enderror
            </div>

            {{-- Website --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    URL Website
                </label>
                <input type="url" name="website_url" value="{{ old('website_url') }}"
                       placeholder="https://example.ac.id"
                       style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                @error('website_url') <p style="font-size:0.75rem;color:#C0392B;margin-top:0.375rem;">{{ $message }}</p> @enderror
            </div>

        </div>

        {{-- Kanan --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Pengaturan + Simpan --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Pengaturan</p>

                <div style="margin-bottom:1rem;">
                    <label style="display:flex;align-items:center;gap:0.625rem;cursor:pointer;">
                        <input type="hidden" name="is_active" value="0"/>
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', 1) ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:#2A7FC1;"/>
                        <span style="font-size:0.825rem;color:#4A6580;">Tampilkan di website (Aktif)</span>
                    </label>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                        Urutan Tampil
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                           onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                    <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.25rem;">Angka kecil = tampil lebih awal</p>
                </div>

                <button type="submit"
                        style="width:100%;padding:0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                    Simpan Mitra
                </button>

                <a href="{{ route('admin.partners.index') }}"
                   style="display:block;text-align:center;margin-top:0.75rem;font-size:0.75rem;color:#B0CCDF;text-decoration:none;"
                   onmouseover="this.style.color='#4A6580'" onmouseout="this.style.color='#B0CCDF'">
                    Batal
                </a>
            </div>

            {{-- Logo --}}
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">
                    Logo <span style="font-weight:400;color:#B0CCDF;">(opsional)</span>
                </label>

                <div id="logo-preview"
                     style="width:100%;height:120px;background:#EEF4FB;border-radius:4px;margin-bottom:0.75rem;overflow:hidden;display:flex;align-items:center;justify-content:center;border:1.5px dashed #D6E8F7;">
                    <div style="text-align:center;">
                        <svg style="width:28px;height:28px;color:#B0CCDF;margin:0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.375rem;">Belum ada logo</p>
                    </div>
                </div>

                <input type="file" name="logo" accept="image/*"
                       style="width:100%;font-size:0.8rem;color:#4A6580;"
                       onchange="previewLogo(this)"/>
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.375rem;">JPG, PNG, SVG. Maks 2MB.</p>
                @error('logo') <p style="font-size:0.75rem;color:#C0392B;margin-top:0.375rem;">{{ $message }}</p> @enderror
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('logo-preview').innerHTML =
                '<img src="' + e.target.result + '" style="max-width:100%;max-height:100%;object-fit:contain;padding:8px;"/>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush