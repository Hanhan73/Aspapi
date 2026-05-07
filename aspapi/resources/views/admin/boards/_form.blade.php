@php $isEdit = isset($board); $item = $board ?? null; @endphp

<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

    {{-- Kiri --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Nama --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                Nama Lengkap <span style="color:#C0392B;">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}"
                   placeholder="Nama lengkap beserta gelar..." required
                   style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            @error('name') <p style="font-size:0.75rem;color:#C0392B;margin-top:0.375rem;">{{ $message }}</p> @enderror
        </div>

        {{-- Jabatan & Kategori --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Jabatan <span style="color:#C0392B;">*</span>
                </label>
                <input type="text" name="position" value="{{ old('position', $item->position ?? '') }}"
                       placeholder="contoh: Ketua Umum" required
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                @error('position') <p style="font-size:0.75rem;color:#C0392B;margin-top:0.375rem;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Kategori <span style="font-weight:400;color:#B0CCDF;">(opsional)</span>
                </label>
                <input type="text" name="position_category" value="{{ old('position_category', $item->position_category ?? '') }}"
                       placeholder="contoh: Ketua, Sekretaris, Departemen..."
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>
        </div>

        {{-- Institusi & Periode --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Institusi
                </label>
                <input type="text" name="institution" value="{{ old('institution', $item->institution ?? '') }}"
                       placeholder="Universitas / Instansi..."
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Periode
                </label>
                <input type="text" name="period" value="{{ old('period', $item->period ?? '') }}"
                       placeholder="contoh: 2022–2026"
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>
        </div>

        {{-- Email & Phone --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Email</label>
                <input type="email" name="email" value="{{ old('email', $item->email ?? '') }}"
                       placeholder="email@contoh.com"
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $item->phone ?? '') }}"
                       placeholder="08xx-xxxx-xxxx"
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>
        </div>

    </div>

    {{-- Kanan --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Simpan --}}
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
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Urutan Tampil
                </label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}"
                       min="0" placeholder="0"
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.25rem;">Angka kecil = tampil lebih awal</p>
            </div>

            <button type="submit"
                    style="width:100%;padding:0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengurus' }}
            </button>

            @if ($isEdit)
            <form method="POST" action="{{ route('admin.boards.destroy', $item) }}"
                  onsubmit="return confirm('Hapus data ini?')" style="margin-top:0.75rem;">
                @csrf @method('DELETE')
                <button type="submit"
                        style="width:100%;padding:0.625rem;background:transparent;border:1.5px solid #C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#C0392B;cursor:pointer;">
                    Hapus
                </button>
            </form>
            @endif
        </div>

        {{-- Foto --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">
                Foto <span style="font-weight:400;color:#B0CCDF;">(opsional)</span>
            </label>
            <div id="board-photo-preview"
                 style="width:100%;height:180px;background:#EEF4FB;border-radius:4px;margin-bottom:0.75rem;overflow:hidden;display:flex;align-items:center;justify-content:center;border:1.5px dashed #D6E8F7;">
                @if ($isEdit && $item->photo)
                    <img src="{{ Storage::url($item->photo) }}"
                         style="width:100%;height:100%;object-fit:cover;object-position:top;"/>
                @else
                    <div style="text-align:center;">
                        <svg style="width:32px;height:32px;color:#B0CCDF;margin:0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.375rem;">Belum ada foto</p>
                    </div>
                @endif
            </div>
            <input type="file" name="photo" accept="image/*"
                   style="width:100%;font-size:0.8rem;color:#4A6580;"
                   onchange="previewBoardPhoto(this)"/>
            <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.375rem;">JPG, PNG, WebP. Maks 2MB.</p>
        </div>

    </div>
</div>

<script>
function previewBoardPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('board-photo-preview').innerHTML =
                '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;object-position:top;"/>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>