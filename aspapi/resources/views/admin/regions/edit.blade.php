@extends('layouts.admin')
@section('title', 'Edit ASPAPI '.$region->province)

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.regions.index') }}" class="text-neutral-400 hover:text-navy text-sm">← Kembali</a>
        <h1 class="text-xl font-bold text-navy">Edit ASPAPI {{ $region->province }}</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.regions.update', $region) }}" method="POST"
          enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="card p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-widest text-neutral-400 border-b pb-3">Informasi Daerah</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="form-label">Provinsi <span class="text-accent-red">*</span></label>
                    <input type="text" name="province" class="form-input"
                           value="{{ old('province', $region->province) }}" required>
                    @error('province') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Nama Ketua</label>
                    <input type="text" name="chairman_name" class="form-input"
                           value="{{ old('chairman_name', $region->chairman_name) }}">
                </div>

                <div>
                    <label class="form-label">Institusi / Jabatan Ketua</label>
                    <input type="text" name="chairman_title" class="form-input"
                           value="{{ old('chairman_title', $region->chairman_title) }}">
                </div>

                <div>
                    <label class="form-label">Periode Mulai</label>
                    <input type="number" name="period_start" class="form-input"
                           value="{{ old('period_start', $region->period_start) }}" min="2000" max="2099">
                </div>

                <div>
                    <label class="form-label">Periode Selesai</label>
                    <input type="number" name="period_end" class="form-input"
                           value="{{ old('period_end', $region->period_end) }}" min="2000" max="2099">
                </div>

                <div>
                    <label class="form-label">Website Resmi</label>
                    <input type="url" name="website_url" class="form-input"
                           value="{{ old('website_url', $region->website_url) }}">
                </div>

                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input"
                           value="{{ old('email', $region->email) }}">
                </div>

                <div>
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" class="form-input"
                           value="{{ old('phone', $region->phone) }}">
                </div>

                <div>
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-input"
                           value="{{ old('sort_order', $region->sort_order) }}" min="0">
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-input h-24">{{ old('description', $region->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Foto / Logo</label>
                    <input type="file" name="photo" class="form-input" accept="image/*">
                    @if ($region->photo)
                        <img src="{{ Storage::url($region->photo) }}"
                             class="mt-2 h-14 w-14 object-cover rounded border border-neutral-200">
                    @endif
                </div>

                <div>
                    <label class="form-label">Gambar Cover</label>
                    <input type="file" name="cover_image" class="form-input" accept="image/*">
                    @if ($region->cover_image)
                        <img src="{{ Storage::url($region->cover_image) }}"
                             class="mt-2 h-14 object-cover rounded border border-neutral-200">
                    @endif
                </div>

                <div class="sm:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ $region->is_active ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm text-navy">Tampilkan di website (Aktif)</label>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.regions.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection