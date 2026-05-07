@extends('layouts.admin')

@php
    $title = 'Edit Dokumen';
    $breadcrumbs = [
        ['label' => 'Download Dokumen', 'url' => route('admin.documents.index')],
        ['label' => 'Edit Dokumen',     'url' => '#'],
    ];
@endphp

@section('content')

<div class="max-w-2xl">
    <form action="{{ route('admin.documents.update', $document) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">

            <div class="px-6 py-4 border-b border-neutral-200 bg-neutral-50">
                <h3 class="text-sm font-bold text-navy">Edit Dokumen</h3>
                <p class="text-2xs text-neutral-400 mt-0.5">Perbarui detail atau ganti file dokumen</p>
            </div>

            <div class="px-6 py-5 space-y-5">

                {{-- Judul --}}
                <div>
                    <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                        Judul Dokumen <span class="text-accent-red">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $document->title) }}"
                           class="w-full px-4 py-2.5 text-sm text-navy border border-neutral-200 rounded bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('title') border-accent-red @enderror"
                           required>
                    @error('title')
                        <p class="mt-1.5 text-2xs text-accent-red">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 text-sm text-navy border border-neutral-200 rounded bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none">{{ old('description', $document->description) }}</textarea>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">Kategori</label>
                    <input type="text"
                           name="category"
                           value="{{ old('category', $document->category) }}"
                           class="w-full px-4 py-2.5 text-sm text-navy border border-neutral-200 rounded bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>

                {{-- File -- Ganti (opsional) --}}
                <div>
                    <label class="block text-2xs font-bold text-navy uppercase tracking-widest mb-1.5">
                        Ganti File <span class="text-neutral-300 font-normal normal-case">(opsional)</span>
                    </label>

                    {{-- File Saat Ini --}}
                    <div class="flex items-center gap-3 px-4 py-3 mb-3 bg-primary-50 border border-primary/20 rounded-lg">
                        <svg class="w-8 h-8 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy truncate">{{ $document->file_name }}</p>
                            <p class="text-2xs text-neutral-400 mt-0.5">{{ $document->file_size }} &middot; {{ $document->file_type }}</p>
                        </div>
                        <span class="text-2xs text-primary font-semibold bg-primary-100 px-2 py-0.5 rounded">Aktif</span>
                    </div>

                    <label for="file-upload"
                           class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-neutral-200 rounded-lg cursor-pointer bg-neutral-50 hover:bg-primary-50 hover:border-primary/40 transition-all group">
                        <div class="flex flex-col items-center gap-1.5 pointer-events-none">
                            <svg class="w-6 h-6 text-neutral-300 group-hover:text-primary transition-colors"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-xs text-neutral-400 group-hover:text-primary font-medium transition-colors">
                                Klik untuk mengganti file
                            </p>
                            <p class="text-2xs text-neutral-300">PDF, Word, Excel, PowerPoint, ZIP &mdash; Maks. 20 MB</p>
                        </div>
                        <input id="file-upload" type="file" name="file"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                               class="hidden"
                               onchange="showFileName(this)">
                    </label>

                    <p id="file-name-display"
                       class="hidden mt-2 text-xs text-primary font-semibold flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <input type="checkbox"
                           name="is_public"
                           id="is_public"
                           value="1"
                           {{ old('is_public', $document->is_public) ? 'checked' : '' }}
                           class="w-4 h-4 mt-0.5 rounded border-neutral-300 text-primary focus:ring-primary/30 cursor-pointer flex-shrink-0">
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

            <div class="px-6 py-4 border-t border-neutral-200 bg-neutral-50 flex items-center gap-3">
                <button type="submit"
                        class="px-5 py-2 bg-primary text-white text-sm font-bold rounded hover:bg-primary-600 transition-colors">
                    Perbarui Dokumen
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