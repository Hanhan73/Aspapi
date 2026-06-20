{{--
    Quill Rich Text Editor Component
    Usage:
        @include('components.quill-editor', [
            'name'        => 'description',
            'value'       => old('description', $model->description ?? ''),
            'placeholder' => 'Tulis deskripsi...',
            'height'      => '200px',
        ])
--}}

@php
    $editorId     = 'quill-' . $name . '-' . uniqid();
    $editorHeight = $height ?? '180px';
@endphp

<div>
    <div id="{{ $editorId }}-container"
         style="border:1.5px solid #D6E8F7;border-radius:6px;overflow:hidden;background:#fff;">

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

        <div id="{{ $editorId }}" style="height:{{ $editorHeight }};font-size:0.875rem;color:#1A2A3A;"></div>
    </div>

    <textarea name="{{ $name }}" id="{{ $editorId }}-hidden" class="hidden">{{ $value ?? '' }}</textarea>
</div>

@once
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
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

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
@endpush
@endonce

@push('scripts')
<script>
(function() {
    const editorEl  = document.getElementById('{{ $editorId }}');
    const hiddenEl  = document.getElementById('{{ $editorId }}-hidden');
    if (!editorEl || !hiddenEl) return;

    const quill = new Quill(editorEl, {
        theme: 'snow',
        modules: {
            toolbar: '#{{ $editorId }}-toolbar',
            keyboard: {
                bindings: {
                    enter: {
                        key: 13,
                        shiftKey: false,
                        handler: function(range, context) {
                            if (context.format.list) return true; 
                            this.quill.insertEmbed(range.index, 'break', true, Quill.sources.USER);
                            this.quill.setSelection(range.index + 1, Quill.sources.USER);
                            return false; 
                        }
                    }
                }
            }
        },
        placeholder: @json($placeholder ?? 'Tulis deskripsi...'),
    });

    // Set nilai awal dari textarea hidden
    const initialValue = hiddenEl.value.trim();
    if (initialValue) {
        quill.root.innerHTML = initialValue;
    }

    // Fungsi untuk membersihkan HTML sebelum disimpan
    function cleanHtml() {
        let html = quill.getSemanticHTML();
        
        // 1. Hilangkan <p></p> yang kosong
        html = html.replace(/<p><\/p>/gi, '');
        // 2. Hilangkan <p><br></p> yang kosong
        html = html.replace(/<p><br><\/p>/gi, '');
        // 3. Ubah <p> menjadi <br> agar text mengalir natural
        html = html.replace(/<p>/gi, '');
        html = html.replace(/<\/p>/gi, '<br>');
        // 4. Hilangkan <br> di akhir teks
        html = html.replace(/(<br\s*\/?>)+$/i, '');
        
        return html.trim() === '<br>' ? '' : html.trim();
    }

    // Sync ke hidden textarea saat konten berubah
    quill.on('text-change', function() {
        hiddenEl.value = cleanHtml();
    });

    // Sync sebelum form submit
    const form = hiddenEl.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            hiddenEl.value = cleanHtml();
        });
    }
})();
</script>
@endpush