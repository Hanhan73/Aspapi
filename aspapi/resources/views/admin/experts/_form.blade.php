@php $isEdit = isset($expert); $item = $expert ?? null; @endphp
<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Nama Lengkap <span style="color:#C0392B;">*</span></label>
            <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" required
                   style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
        </div>

        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Gelar</label>
                <input type="text" name="title" value="{{ old('title', $item->title ?? '') }}"
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Bidang Keahlian</label>
                <input type="text" name="expertise" value="{{ old('expertise', $item->expertise ?? '') }}"
                       placeholder="Administrasi Perkantoran, dst."
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>
        </div>

        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Institusi</label>
            <input type="text" name="institution" value="{{ old('institution', $item->institution ?? '') }}"
                   style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
        </div>

        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Bio <span style="font-weight:400;color:#B0CCDF;">(opsional)</span></label>
            <textarea name="bio" rows="4"
                      style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;resize:vertical;"
                      onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'">{{ old('bio', $item->bio ?? '') }}</textarea>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Pengaturan</p>
            <div style="margin-bottom:1rem;">
                <label style="display:flex;align-items:center;gap:0.625rem;cursor:pointer;">
                    <input type="hidden" name="is_active" value="0"/>
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}
                           style="width:16px;height:16px;accent-color:#2A7FC1;"/>
                    <span style="font-size:0.825rem;color:#4A6580;">Tampilkan (Aktif)</span>
                </label>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Urutan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0"
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>
            <button type="submit" style="width:100%;padding:0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah' }}
            </button>
        </div>

        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">Foto</label>
            <div id="expert-photo-preview"
                 style="width:100%;height:180px;background:#EEF4FB;border-radius:4px;margin-bottom:0.75rem;overflow:hidden;display:flex;align-items:center;justify-content:center;border:1.5px dashed #D6E8F7;">
                @if ($isEdit && $item->photo)
                    <img src="{{ Storage::url($item->photo) }}" style="width:100%;height:100%;object-fit:cover;object-position:top;"/>
                @else
                    <p style="font-size:0.72rem;color:#B0CCDF;">Belum ada foto</p>
                @endif
            </div>
            <input type="file" name="photo" accept="image/*"
                   style="width:100%;font-size:0.8rem;color:#4A6580;"
                   onchange="previewExpertPhoto(this)"/>
        </div>
    </div>
</div>
<script>
function previewExpertPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('expert-photo-preview').innerHTML =
                '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;object-position:top;"/>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>