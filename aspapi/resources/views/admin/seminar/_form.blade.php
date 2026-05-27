{{-- resources/views/admin/seminar/_form.blade.php --}}

@if ($errors->any())
<div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
    <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    {{-- ── Kolom Kiri (konten utama) ── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Judul --}}
        <div class="bg-white border border-neutral-200 rounded-xl p-5">
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">
                Judul Seminar <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title"
                   value="{{ old('title', $seminar?->title) }}"
                   required
                   class="w-full border border-neutral-200 rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-primary/30"
                   placeholder="Judul seminar...">
        </div>

        {{-- Kategori --}}
        <div class="bg-white border border-neutral-200 rounded-xl p-5">
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">
                Kategori
            </label>
            <input type="text" name="category"
                   value="{{ old('category', $seminar?->category) }}"
                   list="category-suggestions"
                   class="w-full border border-neutral-200 rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-primary/30"
                   placeholder="contoh: Manajemen, Administrasi, Teknologi...">
            <datalist id="category-suggestions">
                @php
                    $categories = \App\Models\Seminar::whereNotNull('category')
                        ->distinct()->pluck('category');
                @endphp
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">
                @endforeach
            </datalist>
            <p class="text-2xs text-neutral-400 mt-1.5">
                Opsional. Digunakan untuk mengelompokkan seminar.
            </p>
        </div>

        {{-- Deskripsi --}}
        <div class="bg-white border border-neutral-200 rounded-xl p-5">
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">
                Deskripsi
            </label>
            <textarea name="description" rows="5"
                      class="w-full border border-neutral-200 rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none"
                      placeholder="Deskripsi singkat tentang seminar ini...">{{ old('description', $seminar?->description) }}</textarea>
        </div>

        {{-- Link Materi Google Drive --}}
        <div class="bg-white border border-neutral-200 rounded-xl p-5">
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">
                Link Materi Google Drive <span class="text-red-500">*</span>
            </label>
            <input type="url" name="material_url"
                   value="{{ old('material_url', $seminar?->material_url) }}"
                   required
                   class="w-full border border-neutral-200 rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-primary/30"
                   placeholder="https://drive.google.com/file/d/...">
            <p class="text-2xs text-neutral-400 mt-2">
                Gunakan link share Google Drive (format: <code class="bg-neutral-100 px-1 rounded">drive.google.com/file/d/ID/view</code>).
                Pastikan file bisa diakses oleh siapapun yang memiliki link.
            </p>
        </div>

    </div>

    {{-- ── Kolom Kanan (sidebar) ── --}}
    <div class="space-y-5">

        {{-- Publish / Pengaturan --}}
        <div class="bg-white border border-neutral-200 rounded-xl p-5">
            <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider mb-4">Pengaturan</p>

            {{-- Passing Grade --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">
                    Passing Grade (%) <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-2">
                    <input type="number" name="passing_grade" min="1" max="100"
                           value="{{ old('passing_grade', $seminar?->passing_grade ?? 70) }}"
                           required
                           class="w-full border border-neutral-200 rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-primary/30">
                    <span class="text-sm text-neutral-400 flex-shrink-0">/ 100</span>
                </div>
                <p class="text-2xs text-neutral-400 mt-1.5">
                    Nilai minimum untuk mendapatkan sertifikat.
                </p>
            </div>

            {{-- Status Aktif --}}
            <div class="pt-4 border-t border-neutral-100">
                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">
                    Status
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           {{ old('is_active', $seminar?->is_active ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 accent-primary">
                    <div>
                        <p class="text-sm text-navy font-medium group-hover:text-primary transition-colors">
                            Aktif
                        </p>
                        <p class="text-2xs text-neutral-400">Seminar tampil ke anggota aktif</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- Thumbnail --}}
        <div class="bg-white border border-neutral-200 rounded-xl p-5">
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-3">
                Thumbnail
            </label>

            {{-- Preview --}}
            <div id="thumbnail-preview"
                 class="w-full h-40 rounded-lg overflow-hidden bg-neutral-100 border border-neutral-200 mb-3 flex items-center justify-center">
                @if ($seminar?->thumbnail)
                    <img src="{{ $seminar->thumbnail_url }}"
                         id="thumb-img"
                         class="w-full h-full object-cover">
                @else
                    <div id="thumb-placeholder" class="text-center">
                        <svg class="w-8 h-8 text-neutral-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-2xs text-neutral-400">Belum ada gambar</p>
                    </div>
                @endif
            </div>

            <input type="file" name="thumbnail" id="thumbnail-input" accept="image/*"
                   onchange="previewSeminarThumb(this)"
                   class="block w-full text-xs text-neutral-500
                          file:mr-2 file:text-xs file:font-bold
                          file:border-0 file:bg-primary/10 file:text-primary
                          file:rounded-lg file:px-3 file:py-1.5 file:cursor-pointer">
            <p class="text-2xs text-neutral-400 mt-1.5">
                Format: JPG, PNG. Maks. 2MB.
                @if ($seminar?->thumbnail)
                    <span class="block mt-0.5">Upload baru untuk mengganti.</span>
                @endif
            </p>
        </div>

    </div>
</div>

<script>
function previewSeminarThumb(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('thumbnail-preview');
        preview.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>