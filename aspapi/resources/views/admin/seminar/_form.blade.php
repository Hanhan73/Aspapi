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
            @include('components.quill-editor', [
                'name'        => 'description',
                'value'       => old('description', $seminar?->description ?? ''),
                'placeholder' => 'Deskripsikan kegiatan ini...',
            ])
        </div>

        {{-- ── Materi (repeater) ── --}}
        <div class="bg-white border border-neutral-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider">
                        Materi Seminar <span class="text-red-500">*</span>
                    </label>
                    <p class="text-2xs text-neutral-400 mt-0.5">
                        Tambahkan satu atau lebih materi. Setiap materi butuh judul dan link Google Drive.
                    </p>
                </div>
                <button type="button" onclick="addMaterial()"
                        class="flex-shrink-0 text-xs font-bold px-3 py-1.5 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition">
                    + Tambah Materi
                </button>
            </div>

            <div id="materials-list" class="space-y-3">
                {{-- Diisi JS saat load, atau dari old()/existing data --}}
            </div>

            <p class="text-2xs text-neutral-400 mt-3">
                Format link: <code class="bg-neutral-100 px-1 rounded">drive.google.com/file/d/ID/view</code> —
                pastikan file dibagikan ke "Siapa saja yang memiliki link".
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

@push('scripts')
<script>
// ── Data awal dari server (untuk edit) ──────────────────────────────────────
@php
    // old() dulu, fallback ke data seminar yang ada
    $initialMaterials = old('materials');
    if (! $initialMaterials && $seminar?->relationLoaded('materials')) {
        $initialMaterials = $seminar->materials->map(fn($m) => [
            'label' => $m->label,
            'url'   => $m->url,
        ])->toArray();
    }
    if (! $initialMaterials) {
        $initialMaterials = [['label' => '', 'url' => '']]; // minimal 1 baris kosong untuk create
    }
@endphp

const INITIAL_MATERIALS = @json($initialMaterials);

// ── Render saat halaman load ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    INITIAL_MATERIALS.forEach(function (mat) {
        addMaterial(mat.label, mat.url);
    });
});

// ── Tambah baris materi baru ────────────────────────────────────────────────
function addMaterial(label, url) {
    label = label || '';
    url   = url   || '';

    const list  = document.getElementById('materials-list');
    const index = list.children.length;

    const row = document.createElement('div');
    row.className = 'flex gap-2 items-start';
    row.innerHTML = `
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-2">
            <input type="text"
                   name="materials[${index}][label]"
                   value="${escHtml(label)}"
                   placeholder="Judul materi, misal: Modul 1 — Pengantar"
                   required
                   class="w-full border border-neutral-200 rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-primary/30">
            <input type="url"
                   name="materials[${index}][url]"
                   value="${escHtml(url)}"
                   placeholder="https://drive.google.com/file/d/..."
                   required
                   class="w-full border border-neutral-200 rounded-lg px-3 py-2.5 text-sm text-navy focus:outline-none focus:ring-2 focus:ring-primary/30">
        </div>
        <button type="button"
                onclick="removeMaterial(this)"
                class="flex-shrink-0 w-9 h-9 mt-0.5 flex items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-500 hover:bg-red-100 transition"
                title="Hapus materi">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;

    list.appendChild(row);
    reindexMaterials();
    updateRemoveButtons();
}

// ── Hapus baris ─────────────────────────────────────────────────────────────
function removeMaterial(btn) {
    const list = document.getElementById('materials-list');
    if (list.children.length <= 1) return; // minimal 1 baris
    btn.closest('.flex.gap-2').remove();
    reindexMaterials();
    updateRemoveButtons();
}

// ── Re-index name attribute setelah add/remove ──────────────────────────────
function reindexMaterials() {
    const list = document.getElementById('materials-list');
    Array.from(list.children).forEach(function (row, i) {
        row.querySelectorAll('input[name]').forEach(function (input) {
            input.name = input.name.replace(/materials\[\d+\]/, `materials[${i}]`);
        });
    });
}

// ── Nonaktifkan tombol hapus jika hanya 1 baris ──────────────────────────────
function updateRemoveButtons() {
    const list    = document.getElementById('materials-list');
    const btns    = list.querySelectorAll('button[onclick="removeMaterial(this)"]');
    const disable = list.children.length <= 1;
    btns.forEach(function (btn) {
        btn.disabled = disable;
        btn.style.opacity = disable ? '0.3' : '1';
        btn.style.cursor  = disable ? 'not-allowed' : 'pointer';
    });
}

// ── Thumbnail preview ────────────────────────────────────────────────────────
function previewSeminarThumb(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('thumbnail-preview');
        preview.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
    };
    reader.readAsDataURL(input.files[0]);
}

// ── Escape HTML helper ───────────────────────────────────────────────────────
function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}
</script>
@endpush