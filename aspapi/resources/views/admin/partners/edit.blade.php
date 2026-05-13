@extends('layouts.admin')
@section('title', 'Edit Mitra')

@php
    use Illuminate\Support\Facades\Storage;
    $breadcrumbs = [
        ['label' => 'Mitra', 'url' => route('admin.partners.index')],
        ['label' => 'Edit: ' . $partner->name, 'url' => '#'],
    ];
@endphp

@section('content')

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="card p-6 flex flex-col gap-5">

            {{-- Nama --}}
            <div>
                <label class="form-label">Nama Mitra <span class="text-accent-red">*</span></label>
                <input type="text" name="name" value="{{ old('name', $partner->name) }}"
                       class="input @error('name') border-accent-red @enderror" required />
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label class="form-label">Kategori <span class="text-accent-red">*</span></label>
                <select name="category" class="input @error('category') border-accent-red @enderror" required>
                    <option value="">— Pilih Kategori —</option>
                    @foreach ($categories as $value => $label)
                        <option value="{{ $value }}"
                            {{ old('category', $partner->category) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('category') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Logo --}}
            <div>
                <label class="form-label">Logo</label>

                {{-- Preview logo saat ini --}}
                @if ($partner->logo)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ Storage::url($partner->logo) }}"
                         alt="Logo {{ $partner->name }}"
                         class="h-14 w-24 object-contain rounded border border-neutral-200 bg-white p-1.5">
                    <span class="text-2xs text-neutral-400">Logo saat ini</span>
                </div>
                @endif

                <input type="file" name="logo" accept="image/*"
                       class="input @error('logo') border-accent-red @enderror" />
                <p class="text-2xs text-neutral-400 mt-1">Kosongkan jika tidak ingin mengganti logo. Maks 2MB.</p>
                @error('logo') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Profil Singkat --}}
            <div>
                <label class="form-label">Profil Singkat</label>
                <textarea name="profile" rows="4"
                          class="input @error('profile') border-accent-red @enderror"
                          placeholder="Deskripsi singkat tentang mitra...">{{ old('profile', $partner->profile) }}</textarea>
                @error('profile') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Website --}}
            <div>
                <label class="form-label">URL Website</label>
                <input type="url" name="website_url" value="{{ old('website_url', $partner->website_url) }}"
                       class="input @error('website_url') border-accent-red @enderror"
                       placeholder="https://example.ac.id" />
                @error('website_url') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Sort & Status --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $partner->sort_order) }}"
                           min="0" class="input" />
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $partner->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 accent-primary" />
                        <span class="text-sm text-neutral-700">Aktif / Tampilkan di website</span>
                    </label>
                </div>
            </div>

        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.partners.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>

@endsection