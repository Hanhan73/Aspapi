@php $isEdit = isset($news); @endphp

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

    {{-- Kolom Kiri --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Judul --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label
                style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                Judul Berita <span style="color:#C0392B;">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}"
                placeholder="Masukkan judul berita..." required
                style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.9rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
            @error('title') <p style="font-size:0.75rem;color:#C0392B;margin-top:0.375rem;">{{ $message }}</p> @enderror
        </div>

        {{-- Excerpt --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label
                style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                Ringkasan <span style="font-weight:400;color:#B0CCDF;">(opsional, maks 500 karakter)</span>
            </label>
            <textarea name="excerpt" rows="3" placeholder="Ringkasan singkat berita untuk tampilan di halaman daftar..."
                style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;resize:vertical;"
                onfocus="this.style.borderColor='#2A7FC1'"
                onblur="this.style.borderColor='#D6E8F7'">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
        </div>

        {{-- Body / Editor --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label
                style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                Isi Berita <span style="color:#C0392B;">*</span>
            </label>
            {{-- Toolbar --}}
            <div id="editor-toolbar"
                style="display:flex;flex-wrap:wrap;gap:0.25rem;padding:0.5rem;background:#F8FAFC;border:1.5px solid #D6E8F7;border-bottom:none;border-radius:4px 4px 0 0;">
                @foreach ([
                    ['cmd' => 'bold',               'label' => 'B',  'style' => 'font-weight:700;'],
                    ['cmd' => 'italic',             'label' => 'I',  'style' => 'font-style:italic;'],
                    ['cmd' => 'underline',          'label' => 'U',  'style' => 'text-decoration:underline;'],
                    ['cmd' => 'insertOrderedList',  'label' => 'OL', 'style' => ''],
                    ['cmd' => 'insertUnorderedList','label' => 'UL', 'style' => ''],
                    ['cmd' => 'justifyLeft',        'label' => '⬅',  'style' => ''],
                    ['cmd' => 'justifyCenter',      'label' => '↔',  'style' => ''],
                    ['cmd' => 'justifyRight',       'label' => '➡', 'style' => ''],
                ] as $btn)
                <button type="button" onclick="document.execCommand('{{ $btn['cmd'] }}', false, null)"
                    style="padding:0.25rem 0.5rem;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;{{ $btn['style'] }}min-width:28px;">
                    {{ $btn['label'] }}
                </button>
                @endforeach
                <button type="button" onclick="insertLink()"
                    style="padding:0.25rem 0.5rem;border:1px solid #D6E8F7;border-radius:3px;background:#fff;cursor:pointer;font-size:0.8rem;">
                    Link
                </button>
                <select onchange="document.execCommand('formatBlock', false, this.value); this.value=''"
                    style="padding:0.25rem 0.5rem;border:1px solid #D6E8F7;border-radius:3px;background:#fff;font-size:0.8rem;cursor:pointer;">
                    <option value="">Heading</option>
                    <option value="h2">H2</option>
                    <option value="h3">H3</option>
                    <option value="h4">H4</option>
                    <option value="p">Paragraf</option>
                </select>
            </div>
            <div id="editor" contenteditable="true"
                style="min-height:320px;padding:1rem;border:1.5px solid #D6E8F7;border-radius:0 0 4px 4px;font-size:0.9rem;color:#1A2A3A;line-height:1.8;outline:none;"
                onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'">{!! old('body', $news->body ?? '') !!}</div>
            <textarea name="body" id="body-input" style="display:none;">{{ old('body', $news->body ?? '') }}</textarea>
            @error('body') <p style="font-size:0.75rem;color:#C0392B;margin-top:0.375rem;">{{ $message }}</p> @enderror
        </div>

    </div>

    {{-- Kolom Kanan (Sidebar) --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Publish --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">
                Publikasi
            </p>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Status
                </label>
                <select name="status"
                    style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;">
                    <option value="draft"     {{ old('status', $news->status ?? 'draft') === 'draft'     ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $news->status ?? 'draft') === 'published' ? 'selected' : '' }}>Tayang</option>
                </select>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Tanggal Tayang <span style="font-weight:400;color:#B0CCDF;">(opsional)</span>
                </label>
                <input type="datetime-local" name="published_at"
                    value="{{ old('published_at', isset($news) && $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}"
                    style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                    onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.25rem;">Kosongkan = otomatis saat ditayangkan.</p>
            </div>

            {{-- Tombol submit saja — tombol hapus ada di edit.blade.php di luar form ini --}}
            <button type="submit"
                style="width:100%;padding:0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Berita' }}
            </button>
        </div>

        {{-- Kategori --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label
                style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                Kategori <span style="font-weight:400;color:#B0CCDF;">(opsional)</span>
            </label>
            <input type="text" name="category" value="{{ old('category', $news->category ?? '') }}"
                placeholder="contoh: Kegiatan, Pengumuman..."
                style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
        </div>

        {{-- Thumbnail --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label
                style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">
                Thumbnail <span style="font-weight:400;color:#B0CCDF;">(opsional)</span>
            </label>

            <div id="thumbnail-preview"
                style="width:100%;height:160px;background:#EEF4FB;border-radius:4px;margin-bottom:0.75rem;overflow:hidden;display:flex;align-items:center;justify-content:center;border:1.5px dashed #D6E8F7;">
                @if ($isEdit && $news->thumbnail)
                    <img src="{{ Storage::url($news->thumbnail) }}"
                         style="width:100%;height:100%;object-fit:cover;" />
                @else
                    <div style="text-align:center;">
                        <svg style="width:32px;height:32px;color:#B0CCDF;margin:0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.375rem;">Belum ada gambar</p>
                    </div>
                @endif
            </div>

            <input type="file" name="thumbnail" id="thumbnail-input" accept="image/*"
                style="width:100%;font-size:0.8rem;color:#4A6580;" onchange="previewThumbnail(this)" />
            <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.375rem;">JPG, PNG, WebP. Maks 2MB.</p>
        </div>

    </div>
</div>

<script>
document.getElementById('editor').closest('form').addEventListener('submit', function() {
    document.getElementById('body-input').value = document.getElementById('editor').innerHTML;
});

function insertLink() {
    const url = prompt('Masukkan URL:');
    if (url) document.execCommand('createLink', false, url);
}

function previewThumbnail(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('thumbnail-preview').innerHTML =
                '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;"/>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>