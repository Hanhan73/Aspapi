@extends('layouts.admin')

@php
    $title = 'Atur Urutan Dokumen';
    $breadcrumbs = [
        ['label' => 'Download Dokumen', 'url' => route('admin.documents.index')],
        ['label' => 'Atur Urutan',      'url' => '#'],
    ];
@endphp

@push('styles')
<style>
    .drag-handle { cursor: grab; }
    .drag-handle:active { cursor: grabbing; }
    .sortable-ghost {
        opacity: 0.4;
        background: #EEF4FB !important;
        border: 2px dashed #2A7FC1 !important;
    }
    .sortable-chosen { box-shadow: 0 8px 24px rgba(42,127,193,0.18); }
    .category-ghost {
        opacity: 0.4;
        background: #F8FAFC !important;
        border: 2px dashed #7A9CB8 !important;
        border-radius: 12px;
    }
    .save-indicator {
        transition: all 0.3s ease;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-navy">Atur Urutan Dokumen</h2>
        <p class="text-xs text-neutral-400 mt-0.5">
            Drag & drop untuk mengubah urutan — perubahan tersimpan otomatis
        </p>
    </div>
    <div class="flex items-center gap-3">
        <span id="save-status"
              class="save-indicator text-xs text-neutral-400 flex items-center gap-1.5 opacity-0">
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
            Tersimpan
        </span>
        <a href="{{ route('admin.documents.index') }}"
           class="px-4 py-2 text-sm font-semibold text-neutral-500 border border-neutral-200 rounded hover:bg-neutral-100 transition-colors">
            Kembali
        </a>
    </div>
</div>

{{-- Petunjuk --}}
<div class="bg-primary-50 border border-primary-200 rounded-lg px-5 py-3.5 mb-6 flex items-start gap-3">
    <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div class="text-xs text-primary-700 leading-relaxed">
        <strong>Cara pakai:</strong>
        Gunakan <strong>handle ⠿</strong> di kiri untuk drag dokumen dalam satu kategori.
        Untuk mengubah urutan <strong>kategori</strong>, drag seluruh blok kategori dari header-nya.
        Urutan tersimpan otomatis setiap kali kamu melepas drag.
    </div>
</div>

{{-- Container kategori — ini yang bisa di-sort --}}
<div id="category-container" class="flex flex-col gap-6">

    @foreach($documents as $kategori => $docs)

    <div class="category-block bg-white rounded-xl border border-neutral-200 overflow-hidden"
         data-category="{{ $kategori }}">

        {{-- Header kategori — drag handle untuk urutan kategori --}}
        <div class="category-drag-handle flex items-center gap-3 px-5 py-3.5 bg-neutral-50 border-b border-neutral-200 cursor-grab active:cursor-grabbing select-none">
            <svg class="w-5 h-5 text-neutral-300 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5a1 1 0 100 2 1 1 0 000-2zm0 6a1 1 0 100 2 1 1 0 000-2zm0 6a1 1 0 100 2 1 1 0 000-2zm8-12a1 1 0 100 2 1 1 0 000-2zm0 6a1 1 0 100 2 1 1 0 000-2zm0 6a1 1 0 100 2 1 1 0 000-2z"/>
            </svg>
            <div class="flex items-center gap-2.5 flex-1">
                <div class="w-7 h-7 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-navy">{{ $kategori }}</p>
                    <p class="text-2xs text-neutral-400">{{ $docs->count() }} dokumen</p>
                </div>
            </div>
            <span class="text-2xs text-neutral-300 font-semibold tracking-widest uppercase">Drag untuk pindah kategori</span>
        </div>

        {{-- List dokumen dalam kategori ini --}}
        <ul class="doc-sortable divide-y divide-neutral-100 px-0"
            data-category="{{ $kategori }}">

            @foreach($docs as $doc)
            <li class="doc-item flex items-center gap-4 px-5 py-3.5 hover:bg-neutral-50 transition-colors"
                data-id="{{ $doc->id }}">

                {{-- Handle dokumen --}}
                <div class="drag-handle flex-shrink-0 text-neutral-300 hover:text-neutral-500 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5a1 1 0 100 2 1 1 0 000-2zm0 6a1 1 0 100 2 1 1 0 000-2zm0 6a1 1 0 100 2 1 1 0 000-2zm8-12a1 1 0 100 2 1 1 0 000-2zm0 6a1 1 0 100 2 1 1 0 000-2zm0 6a1 1 0 100 2 1 1 0 000-2z"/>
                    </svg>
                </div>

                {{-- Tipe badge --}}
                <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center
                    @if($doc->file_type === 'PDF') bg-red-50
                    @elseif(in_array($doc->file_type, ['DOC','DOCX'])) bg-primary-50
                    @elseif(in_array($doc->file_type, ['XLS','XLSX'])) bg-green-50
                    @else bg-neutral-100
                    @endif">
                    <span class="text-2xs font-black tracking-tight
                        @if($doc->file_type === 'PDF') text-accent-red
                        @elseif(in_array($doc->file_type, ['DOC','DOCX'])) text-primary
                        @elseif(in_array($doc->file_type, ['XLS','XLSX'])) text-green-700
                        @else text-neutral-500
                        @endif">
                        {{ $doc->file_type }}
                    </span>
                </div>

                {{-- Judul --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-navy truncate">{{ $doc->title }}</p>
                    @if($doc->description)
                        <p class="text-2xs text-neutral-400 truncate">{{ $doc->description }}</p>
                    @endif
                </div>

                {{-- Ukuran --}}
                <span class="text-xs text-neutral-400 tabular-nums flex-shrink-0">
                    {{ $doc->file_size_formatted }}
                </span>

            </li>
            @endforeach

        </ul>
    </div>

    @endforeach

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script>
const csrfToken  = document.querySelector('meta[name="csrf-token"]').content;
const statusEl   = document.getElementById('save-status');

// Tampilkan "Tersimpan" sebentar
function flashSaved() {
    statusEl.style.opacity = '1';
    clearTimeout(window._saveTimer);
    window._saveTimer = setTimeout(() => {
        statusEl.style.opacity = '0';
    }, 2500);
}

// Kirim urutan dokumen ke server
function saveDocumentOrder(categoryEl) {
    const ids = [...categoryEl.querySelectorAll('.doc-item')]
                    .map(el => parseInt(el.dataset.id));

    fetch('{{ route('admin.documents.sort.documents') }}', {
        method:  'POST',
        headers: {
            'Content-Type':     'application/json',
            'X-CSRF-TOKEN':     csrfToken,
            'Accept':           'application/json',
        },
        body: JSON.stringify({ order: ids }),
    })
    .then(r => r.json())
    .then(() => flashSaved())
    .catch(err => console.error('Sort error:', err));
}

// Kirim urutan kategori ke server
function saveCategoryOrder() {
    const cats = [...document.querySelectorAll('.category-block')]
                     .map(el => el.dataset.category);

    fetch('{{ route('admin.documents.sort.categories') }}', {
        method:  'POST',
        headers: {
            'Content-Type':     'application/json',
            'X-CSRF-TOKEN':     csrfToken,
            'Accept':           'application/json',
        },
        body: JSON.stringify({ order: cats }),
    })
    .then(r => r.json())
    .then(() => flashSaved())
    .catch(err => console.error('Category sort error:', err));
}

// ── Sort dokumen dalam tiap kategori ──
document.querySelectorAll('.doc-sortable').forEach(list => {
    Sortable.create(list, {
        handle:        '.drag-handle',
        animation:     200,
        ghostClass:    'sortable-ghost',
        chosenClass:   'sortable-chosen',
        onEnd() {
            saveDocumentOrder(list.closest('.category-block'));
        },
    });
});

// ── Sort kategori ──
Sortable.create(document.getElementById('category-container'), {
    handle:      '.category-drag-handle',
    animation:   250,
    ghostClass:  'category-ghost',
    chosenClass: 'sortable-chosen',
    onEnd() {
        saveCategoryOrder();
    },
});
</script>
@endpush