{{--
    Rich Text Editor Component (contenteditable)
    Usage:
        @include('components.quill-editor', [
            'name'        => 'description',
            'value'       => old('description', $model->description ?? ''),
            'placeholder' => 'Tulis deskripsi...',
            'height'      => '200px',
        ])
--}}

@php
    // Ganti - dengan _ agar aman dipakai sebagai nama function JS
    $editorId     = 'editor_' . preg_replace('/[^a-zA-Z0-9]/', '_', $name) . '_' . str_replace('.', '_', uniqid());
    $editorHeight = $height ?? '180px';
@endphp

<div>
    {{-- Toolbar --}}
    <div style="display:flex;flex-wrap:wrap;gap:0.25rem;padding:6px 8px;background:#F8FBFE;border:1.5px solid #D6E8F7;border-bottom:none;border-radius:6px 6px 0 0;">
        <button type="button" onclick="document.execCommand('bold')"
                style="padding:3px 7px;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;font-weight:700;color:#4A6580;"
                title="Tebal">B</button>
        <button type="button" onclick="document.execCommand('italic')"
                style="padding:3px 7px;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;font-style:italic;color:#4A6580;"
                title="Miring">I</button>
        <button type="button" onclick="document.execCommand('underline')"
                style="padding:3px 7px;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;text-decoration:underline;color:#4A6580;"
                title="Garis bawah">U</button>
        <button type="button" onclick="document.execCommand('strikeThrough')"
                style="padding:3px 7px;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;text-decoration:line-through;color:#4A6580;"
                title="Coret">S</button>
        <span style="width:1px;background:#D6E8F7;margin:2px 4px;"></span>
        <button type="button" onclick="document.execCommand('insertUnorderedList')"
                style="padding:3px 7px;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;color:#4A6580;"
                title="Daftar poin">• UL</button>
        <button type="button" onclick="document.execCommand('insertOrderedList')"
                style="padding:3px 7px;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;color:#4A6580;"
                title="Daftar angka">1. OL</button>
        <span style="width:1px;background:#D6E8F7;margin:2px 4px;"></span>
        <button type="button" onclick="insertEditorLink_{{ $editorId }}()"
                style="padding:3px 7px;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;color:#4A6580;"
                title="Link">Link</button>
        <button type="button" onclick="document.execCommand('removeFormat')"
                style="padding:3px 7px;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;color:#4A6580;"
                title="Hapus format">✕</button>
    </div>

    {{-- Editor area --}}
    <div id="{{ $editorId }}"
         contenteditable="true"
         style="min-height:{{ $editorHeight }};padding:0.875rem 1rem;border:1.5px solid #D6E8F7;border-radius:0 0 6px 6px;font-size:0.875rem;color:#1A2A3A;line-height:1.75;outline:none;background:#fff;"
         onfocus="this.style.borderColor='#2A7FC1'"
         onblur="this.style.borderColor='#D6E8F7'"
         data-placeholder="{{ $placeholder ?? 'Tulis deskripsi...' }}">{!! $value ?? '' !!}</div>

    {{-- Placeholder via CSS --}}
    @once
    @push('styles')
    <style>
    [contenteditable][data-placeholder]:empty::before {
        content: attr(data-placeholder);
        color: #B0CCDF;
        pointer-events: none;
    }
    [contenteditable] p { margin-bottom: 0.75em; }
    [contenteditable] p:last-child { margin-bottom: 0; }

    /* ── Output rich editor di semua tempat tampil ── */
    .rich-output p, .desc-content p, .seminar-desc p, .seminar-modal-desc p, #modal-desc p { margin-bottom: 0.7em; }
    .rich-output p:last-child, .desc-content p:last-child, .seminar-desc p:last-child, .seminar-modal-desc p:last-child, #modal-desc p:last-child { margin-bottom: 0; }
    .rich-output strong, .desc-content strong, .seminar-desc strong, .seminar-modal-desc strong, #modal-desc strong { font-weight: 700; }
    .rich-output em, .desc-content em, .seminar-desc em, .seminar-modal-desc em, #modal-desc em { font-style: italic; }
    .rich-output u, .desc-content u, .seminar-desc u, .seminar-modal-desc u, #modal-desc u { text-decoration: underline; }
    .rich-output ul, .desc-content ul, .seminar-desc ul, .seminar-modal-desc ul, #modal-desc ul { list-style: disc; padding-left: 1.25rem; margin-bottom: 0.7em; }
    .rich-output ol, .desc-content ol, .seminar-desc ol, .seminar-modal-desc ol, #modal-desc ol { list-style: decimal; padding-left: 1.25rem; margin-bottom: 0.7em; }
    .rich-output li, .desc-content li, .seminar-desc li, .seminar-modal-desc li, #modal-desc li { margin-bottom: 0.25em; }
    .rich-output a, .desc-content a, .seminar-desc a, .seminar-modal-desc a, #modal-desc a { color: #2A7FC1; text-decoration: underline; text-underline-offset: 2px; }
    .rich-output br, .desc-content br, .seminar-desc br, .seminar-modal-desc br, #modal-desc br { display: block; content: ''; margin-top: 0.5em; }
    </style>
    @endpush
    @endonce

    {{-- Hidden textarea untuk submit --}}
    <textarea name="{{ $name }}" id="{{ $editorId }}-hidden" style="display:none;">{{ $value ?? '' }}</textarea>
</div>

<script>
(function() {
    const editorEl = document.getElementById('{{ $editorId }}');
    const hiddenEl = document.getElementById('{{ $editorId }}-hidden');
    if (!editorEl || !hiddenEl) return;

    function syncContent() {
        hiddenEl.value = editorEl.innerHTML === '<br>' ? '' : editorEl.innerHTML;
    }

    editorEl.addEventListener('input', syncContent);

    editorEl.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.execCommand('insertParagraph');
        }
    });

    const form = hiddenEl.closest('form');
    if (form) {
        form.addEventListener('submit', syncContent);
    }
})();

function insertEditorLink_{{ $editorId }}() {
    const url = prompt('Masukkan URL:');
    if (url) document.execCommand('createLink', false, url);
}
</script>