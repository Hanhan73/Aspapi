@extends('layouts.admin')
@section('title', 'Tambah ASPAPI Daerah')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.regions.index') }}" class="text-neutral-400 hover:text-navy text-sm">← Kembali</a>
        <h1 class="text-xl font-bold text-navy">Tambah ASPAPI Daerah</h1>
    </div>

    <form action="{{ route('admin.regions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Info Daerah --}}
        <div class="card p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-widest text-neutral-400 border-b pb-3">Informasi Daerah</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="form-label">Provinsi <span class="text-accent-red">*</span></label>
                    <input type="text" name="province" class="form-input @error('province') border-accent-red @enderror"
                           value="{{ old('province') }}" placeholder="Contoh: Jawa Barat" required>
                    @error('province') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Nama Ketua</label>
                    <input type="text" name="chairman_name" class="form-input"
                           value="{{ old('chairman_name') }}" placeholder="Nama lengkap dengan gelar">
                </div>

                <div>
                    <label class="form-label">Institusi / Jabatan Ketua</label>
                    <input type="text" name="chairman_title" class="form-input"
                           value="{{ old('chairman_title') }}" placeholder="Universitas / Sekolah">
                </div>

                <div>
                    <label class="form-label">Periode Mulai</label>
                    <input type="number" name="period_start" class="form-input"
                           value="{{ old('period_start') }}" placeholder="2023" min="2000" max="2099">
                </div>

                <div>
                    <label class="form-label">Periode Selesai</label>
                    <input type="number" name="period_end" class="form-input"
                           value="{{ old('period_end') }}" placeholder="2027" min="2000" max="2099">
                </div>

                <div>
                    <label class="form-label">Website Resmi</label>
                    <input type="url" name="website_url" class="form-input"
                           value="{{ old('website_url') }}" placeholder="https://...">
                </div>

                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}">
                </div>

                <div>
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                </div>

                <div>
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-input"
                           value="{{ old('sort_order', 0) }}" min="0">
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-input h-24"
                              placeholder="Deskripsi singkat ASPAPI Daerah...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Foto / Logo</label>
                    <input type="file" name="photo" class="form-input" accept="image/*">
                </div>

                <div>
                    <label class="form-label">Gambar Cover</label>
                    <input type="file" name="cover_image" class="form-input" accept="image/*">
                </div>

                <div class="sm:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label for="is_active" class="text-sm text-navy">Tampilkan di website (Aktif)</label>
                </div>
            </div>
        </div>

        {{-- Akun Login --}}
        <div class="card p-6 space-y-4" x-data="{ show: false }">
            <h2 class="text-xs font-bold uppercase tracking-widest text-neutral-400 border-b pb-3">Akun Login Daerah</h2>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="create_account" id="create_account" value="1"
                       x-model="show">
                <label for="create_account" class="text-sm text-navy">Buat akun login sekarang (opsional)</label>
            </div>
            <div x-show="show" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Email Akun</label>
                    <input type="email" name="account_email" class="form-input @error('account_email') border-accent-red @enderror"
                           value="{{ old('account_email') }}" placeholder="daerah.jabar@aspapi.or.id">
                    @error('account_email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="account_password" class="form-input" placeholder="Min. 8 karakter">
                    @error('account_password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">Tambah Daerah</button>
            <a href="{{ route('admin.regions.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection