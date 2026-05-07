@extends('layouts.admin')

@php
    $title = 'Tambah Dokumen';
    $breadcrumbs = [
        ['label' => 'Download Dokumen', 'url' => route('admin.documents.index')],
        ['label' => 'Tambah Dokumen',   'url' => '#'],
    ];
@endphp

@section('content')

<div class="max-w-2xl">
    <form action="{{ route('admin.documents.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">

            {{-- Form Header --}}
            <div class="px-6 py-4 border-b border-neutral-200 bg-neutral-50">
                <h3 class="text-sm font-bold text-navy">Informasi Dokumen</h3>
                <p class="text-2xs text-neutral-400 mt-0.5">Isi detail dokumen yang akan diunggah</p>
            </div>

            <div class="px-6 py-5 space-y-5">

                {{-- Judul --}}
                <div>
                    <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                        Judul Dokumen <span class="text-accent-red">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Contoh: AD/ART ASPAPI 2024"
                           class="w-full px-4 py-2.5 text-sm text-navy border border-neutral-200 rounded bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-neutral-300 @error('title') border-accent-red ring-2 ring-accent-red/20 @enderror">
                    @error('title')
                        <p class="mt-1.5 text-2xs text-accent-red">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                        Deskripsi
                    </label>
                    <textarea name="description"
                              rows="3"
                              placeholder="Deskripsi singkat isi dokumen..."
                              class="w-full px-4 py-2.5 text-sm text-navy border border-neutral-200 rounded bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-neutral-300 resize-none">{{ old('description') }}</textarea>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                        Kategori
                    </label>
                    <input type="text"
                           name="category"
                           value="{{ old('category') }}"
                           placeholder="Contoh: AD/ART, Panduan, Formulir, Materi"
                           class="w-full px-4 py-2.5 text-sm text-navy border border-neutral-200 rounded bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-neutral-300">
                    <p class="mt-1.5 text-2xs text-neutral-400">Kategori digunakan untuk memfilter dokumen di halaman publik.</p>
                </div>

                {{-- Upload File --}}
                <div>
                    <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                        File Dokumen <span class="text-accent-red">*</span>
                    </label>

                    <label for="file-upload"
                           class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-neutral-200 rounded-lg cursor-pointer bg-neutral-50 hover:bg-primary-50 hover:border-primary/40 transition-all group @error('file') border-accent-red @enderror">
                        <div class="flex flex-col items-center gap-2 pointer-events-none">
                            <svg class="w-8 h-8 text-neutral-300 group-hover:text-primary transition-colors"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-xs text-neutral-500 group-hover:text-primary font-medium transition-colors">
                                Klik untuk pilih file
                            </p>
                            <p class="text-2xs text-neutral-300">
                                PDF, Word, Excel, PowerPoint, ZIP &mdash; Maks. 20 MB
                            </p>
                        </div>
                        <input id="file-upload" type="file" name="file"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                               class="hidden" required
                               onchange="showFileName(this)">
                    </label>

                    <p id="file-name-display"
                       class="hidden mt-2 text-xs text-primary font-semibold flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="file-name-text"></span>
                    </p>

                    @error('file')
                        <p class="mt-1.5 text-2xs text-accent-red">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tampilkan Publik --}}
                <div class="flex items-start gap-3 pt-1">
                    <div class="relative mt-0.5 flex-shrink-0">
                        <input type="checkbox"
                               name="is_public"
                               id="is_public"
                               value="1"
                               {{ old('is_public', true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-neutral-300 text-primary focus:ring-primary/30 cursor-pointer">
                    </div>
                    <div>
                        <label for="is_public" class="text-sm font-semibold text-navy cursor-pointer">
                            Tampilkan di halaman publik
                        </label>
                        <p class="text-2xs text-neutral-400 mt-0.5">
                            Jika dicentang, dokumen ini akan muncul dan dapat diunduh oleh pengunjung website.
                        </p>
                    </div>
                </div>

            </div>

            {{-- Form Footer --}}
            <div class="px-6 py-4 border-t border-neutral-200 bg-neutral-50 flex items-center gap-3">
                <button type="submit"
                        class="px-5 py-2 bg-primary text-white text-sm font-bold rounded hover:bg-primary-600 transition-colors">
                    Simpan Dokumen
                </button>
                <a href="{{ route('admin.documents.index') }}"
                   class="px-5 py-2 text-sm font-semibold text-neutral-500 border border-neutral-200 rounded hover:bg-neutral-100 transition-colors">
                    Batal
                </a>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
function showFileName(input) {
    const display = document.getElementById('file-name-display');
    const text    = document.getElementById('file-name-text');
    if (input.files && input.files[0]) {
        text.textContent = input.files[0].name;
        display.classList.remove('hidden');
        display.classList.add('flex');
    }
}
</script>
@endpush

@endsection