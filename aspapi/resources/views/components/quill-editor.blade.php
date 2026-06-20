{{--
    Quill Rich Text Editor Component
    Usage:
        @include('components.quill-editor', [
            'name'        => 'description',       // nama field form
            'value'       => old('description', $model->description ?? ''),
            'placeholder' => 'Tulis deskripsi...',
            'height'      => '200px',             // opsional, default 180px
        ])

    Pastikan @stack('quill-scripts') ada di layouts sebelum </body>
    atau letakkan @push('scripts') di bawah component ini.
--}}

@php
    $editorId    = 'quill-' . $name . '-' . uniqid();
    $editorHeight = $height ?? '180px';
@endphp

<div>
    {{-- Toolbar + editor container --}}
    <div id="{{ $editorId }}-container"
         style="border:1.5px solid #D6E8F7;border-radius:6px;overflow:hidden;background:#fff;">

        {{-- Toolbar --}}
        <div id="{{ $editorId }}-toolbar" style="border:none;border-bottom:1.5px solid #EEF4FB;padding:6px 8px;">
            <span class="ql-formats">
                <button class="ql-bold" title="Tebal"></button>
                <button class="ql-italic" title="Miring"></button>
                <button class="ql-underline" title="Garis bawah"></button>
                <button class="ql-strike" title="Coret"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-list" value="ordered" title="Daftar angka"></button>
                <button class="ql-list" value="bullet" title="Daftar poin"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-link" title="Link"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-clean" title="Hapus format"></button>
            </span>
        </div>

        {{-- Editor area --}}
        <div id="{{ $editorId }}" style="height:{{ $editorHeight }};font-size:0.875rem;color:#1A2A3A;"></div>
    </div>

    {{-- Hidden textarea untuk submit form --}}
    <textarea name="{{ $name }}" id="{{ $editorId }}-hidden" class="hidden">{{ $value ?? '' }}</textarea>
</div>

@once
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
/* Override Quill styles agar match design system */
.ql-toolbar.ql-snow {
    border: none !important;
    border-bottom: 1.5px solid #EEF4FB !important;
    padding: 6px 8px !important;
    background: #F8FBFE;
}
.ql-container.ql-snow {
    border: none !important;
    font-family: 'DM Sans', sans-serif !important;
}
.ql-editor {
    font-size: 0.875rem !important;
    color: #1A2A3A !important;
    line-height: 1.65 !important;
}
.ql-editor.ql-blank::before {
    color: #B0CCDF !important;
    font-style: normal !important;
    font-size: 0.875rem !important;
}
.ql-snow .ql-stroke { stroke: #4A6580 !important; }
.ql-snow .ql-fill  { fill:   #4A6580 !important; }
.ql-snow.ql-toolbar button:hover .ql-stroke,
.ql-snow.ql-toolbar button.ql-active .ql-stroke { stroke: #2A7FC1 !important; }
.ql-snow.ql-toolbar button:hover .ql-fill,
.ql-snow.ql-toolbar button.ql-active .ql-fill   { fill:   #2A7FC1 !important; }
</style>
@endpush
@endonce

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
(function() {
    const editorEl  = document.getElementById('{{ $editorId }}');
    const hiddenEl  = document.getElementById('{{ $editorId }}-hidden');
    if (!editorEl || !hiddenEl) return;

    const quill = new Quill(editorEl, {
        theme: 'snow',
        modules: {
            toolbar: '#{{ $editorId }}-toolbar',
        },
        placeholder: '{{ $placeholder ?? 'Tulis deskripsi...' }}',
    });

    // Set nilai awal dari textarea hidden
    const initialValue = hiddenEl.value.trim();
    if (initialValue) {
        // Kalau sudah HTML (dari edit), pakai pasteHTML; kalau plain text, set teks
        if (initialValue.startsWith('<')) {
            quill.clipboard.dangerouslyPasteHTML(initialValue);
        } else {
            quill.setText(initialValue);
        }
    }

    // Sync ke hidden textarea saat konten berubah
    quill.on('text-change', function() {
        const html = quill.getSemanticHTML();
        // Kalau hanya paragraph kosong, kosongkan
        hiddenEl.value = html === '<p><br></p>' || html === '<p></p>' ? '' : html;
    });

    // Sync sebelum form submit (jaga-jaga)
    const form = hiddenEl.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            const html = quill.getSemanticHTML();
            hiddenEl.value = html === '<p><br></p>' || html === '<p></p>' ? '' : html;
        });
    }
})();
</script>
@endpush