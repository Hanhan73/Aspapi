@extends('layouts.admin')
@section('title', isset($agenda) ? 'Edit Agenda' : 'Tambah Agenda')

@section('content')
<div class="p-6 max-w-2xl">

    <div class="mb-6">
        <a href="{{ route('admin.agenda.index') }}" class="text-xs text-primary hover:text-primary-600 transition-colors">← Kembali ke Daftar Agenda</a>
        <h1 class="text-xl font-extrabold text-navy mt-2">{{ isset($agenda) ? 'Edit Agenda' : 'Tambah Agenda' }}</h1>
        @if (!isset($agenda))
        <p class="text-xs text-neutral-500 mt-1">Agenda yang dibuat admin langsung ditampilkan di halaman publik tanpa perlu persetujuan.</p>
        @endif
    </div>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        @foreach ($errors->all() as $e)
        <p class="text-xs text-accent-red font-medium">• {{ $e }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST"
          action="{{ isset($agenda) ? route('admin.agenda.update', $agenda) : route('admin.agenda.store') }}"
          enctype="multipart/form-data"
          class="card p-6 flex flex-col gap-5">
        @csrf
        @if (isset($agenda)) @method('PUT') @endif

        {{-- ASPAPI Daerah --}}
        <div>
            <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                ASPAPI Daerah <span class="text-neutral-400 font-normal">(opsional — kosongkan jika dari pusat)</span>
            </label>
            <select name="region_id" class="form-input w-full text-sm">
                <option value="">— ASPAPI Pusat —</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}"
                            {{ old('region_id', $agenda->region_id ?? '') == $region->id ? 'selected' : '' }}>
                        {{ $region->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nama Kegiatan --}}
        <div>
            <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                Nama Kegiatan <span class="text-accent-red">*</span>
            </label>
            <input type="text" name="title"
                   value="{{ old('title', $agenda->title ?? '') }}"
                   placeholder="contoh: Kongres ASPAPI ke-V..."
                   class="form-input w-full text-sm" required>
        </div>

        {{-- Tanggal Kegiatan --}}
        <div>
            <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                Tanggal Kegiatan <span class="text-accent-red">*</span>
            </label>
            <input type="date" name="event_date"
                   value="{{ old('event_date', isset($agenda) ? $agenda->event_date->format('Y-m-d') : '') }}"
                   class="form-input w-full text-sm" required>
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">Deskripsi</label>
            @include('components.quill-editor', [
                'name'        => 'description',
                'value'       => old('description', $agenda->description ?? ''),
                'placeholder' => 'Deskripsikan kegiatan ini...',
            ])
        </div>

        {{-- Foto --}}
        <div>
            <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                Foto Kegiatan <span class="text-neutral-400 font-normal">(opsional, rasio 1:1)</span>
            </label>

            <div id="photo-preview"
                 style="width:160px;height:160px;background:#EEF4FB;border-radius:6px;margin-bottom:0.75rem;overflow:hidden;display:flex;align-items:center;justify-content:center;border:1.5px dashed #D6E8F7;cursor:pointer;"
                 onclick="document.getElementById('photo-input').click()">
                @if (isset($agenda) && $agenda->photo)
                    <img src="{{ Storage::url($agenda->photo) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <div id="photo-placeholder" style="text-align:center;">
                        <svg style="width:32px;height:32px;color:#B0CCDF;margin:0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.25rem;">Klik untuk pilih foto</p>
                    </div>
                @endif
            </div>

            <input type="file" name="photo" id="photo-input" accept="image/*"
                   class="hidden" onchange="previewPhoto(this)">
            <p class="text-2xs text-neutral-400 mt-1">JPG, PNG, WebP. Maks. 2MB. Sebaiknya gunakan foto persegi (1:1).</p>

            @if (isset($agenda) && $agenda->photo)
            <label class="inline-flex items-center gap-1.5 mt-2 cursor-pointer">
                <input type="checkbox" name="remove_photo" value="1" onchange="toggleRemovePhoto(this)">
                <span class="text-2xs text-accent-red font-medium">Hapus foto saat ini</span>
            </label>
            @endif
        </div>

        <div class="pt-2 flex gap-3">
            <button type="submit" class="btn btn-primary">
                {{ isset($agenda) ? 'Simpan Perubahan' : 'Tambah Agenda' }}
            </button>
            <a href="{{ route('admin.agenda.index') }}" class="btn border border-neutral-200 text-neutral-500 hover:bg-neutral-50">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('photo-preview');
        preview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
    };
    reader.readAsDataURL(input.files[0]);
}

function toggleRemovePhoto(cb) {
    const preview = document.getElementById('photo-preview');
    if (cb.checked) {
        preview.innerHTML = '<div id="photo-placeholder" style="text-align:center;"><svg style="width:32px;height:32px;color:#B0CCDF;margin:0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.25rem;">Foto akan dihapus</p></div>';
    }
}
</script>
@endpush
@endsection