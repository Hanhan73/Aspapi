@php
    $isEdit = isset($album) && $album->exists;
    $label  = 'display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;';
    $input  = 'width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;background:#fff;';
    $card   = 'background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;';
    $hint   = 'font-weight:400;color:#B0CCDF;text-transform:none;letter-spacing:0;';
    $err    = 'font-size:0.75rem;color:#C0392B;margin-top:0.375rem;';
    $val    = fn ($key, $default = '') => old($key, $isEdit ? $album->{$key} : $default);
    $dateVal = fn ($key) => old($key, $isEdit && $album->{$key} ? $album->{$key}->format('Y-m-d') : '');
@endphp

@if ($errors->any())
<div style="background:#FDF2F2;border:1px solid #F5C6C6;color:#9B2C2C;border-radius:6px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;font-size:0.85rem;">
    Periksa kembali isian yang ditandai merah.
</div>
@endif

<div class="gallery-form-grid" style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

    {{-- Kolom Kiri --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        <div style="{{ $card }}">
            <label style="{{ $label }}">Judul Album <span style="color:#C0392B;">*</span></label>
            <input type="text" name="title" value="{{ $val('title') }}" required maxlength="255"
                   placeholder="contoh: Rapat Kerja Nasional ASPAPI 2026"
                   style="{{ $input }}font-size:0.9rem;padding:0.75rem 1rem;"
                   onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            @error('title') <p style="{{ $err }}">{{ $message }}</p> @enderror

            <label style="{{ $label }}margin-top:1rem;">Slug <span style="{{ $hint }}">(opsional — dibuat otomatis dari judul)</span></label>
            <input type="text" name="slug" value="{{ $val('slug') }}" maxlength="255"
                   placeholder="rapat-kerja-nasional-aspapi-2026"
                   style="{{ $input }}"
                   onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            @error('slug') <p style="{{ $err }}">{{ $message }}</p> @enderror
        </div>

        <div style="{{ $card }}">
            <p style="{{ $label }}margin-bottom:1rem;">Info Kegiatan</p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="{{ $label }}">Tanggal Mulai <span style="color:#C0392B;">*</span></label>
                    <input type="date" name="event_date" value="{{ $dateVal('event_date') }}" required style="{{ $input }}"/>
                    @error('event_date') <p style="{{ $err }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $label }}">Tanggal Selesai <span style="{{ $hint }}">(opsional)</span></label>
                    <input type="date" name="event_date_end" value="{{ $dateVal('event_date_end') }}" style="{{ $input }}"/>
                    @error('event_date_end') <p style="{{ $err }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $label }}">Jenis Kegiatan</label>
                    <input type="text" name="activity_type" list="activity-types" maxlength="100"
                           value="{{ $val('activity_type', \App\Models\GalleryAlbum::DEFAULT_ACTIVITY_TYPE) }}"
                           style="{{ $input }}"/>
                    <datalist id="activity-types">
                        @foreach ($activityTypes as $type)
                            <option value="{{ $type }}">
                        @endforeach
                    </datalist>
                    @error('activity_type') <p style="{{ $err }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $label }}">Tempat <span style="{{ $hint }}">(opsional)</span></label>
                    <input type="text" name="place" value="{{ $val('place') }}" maxlength="255"
                           placeholder="contoh: Hotel Savoy Homann, Bandung" style="{{ $input }}"/>
                    @error('place') <p style="{{ $err }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div style="{{ $card }}">
            <label style="{{ $label }}">Deskripsi <span style="{{ $hint }}">(opsional — juga dipakai sebagai meta description)</span></label>
            <textarea name="description" rows="5" maxlength="5000"
                      placeholder="Ringkasan singkat kegiatan..."
                      style="{{ $input }}resize:vertical;line-height:1.6;">{{ $val('description') }}</textarea>
            @error('description') <p style="{{ $err }}">{{ $message }}</p> @enderror
        </div>

    </div>

    {{-- Kolom Kanan --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        <div style="{{ $card }}">
            <p style="{{ $label }}margin-bottom:1rem;">Publikasi</p>

            <label style="display:flex;align-items:center;gap:0.625rem;font-size:0.875rem;color:#1A2A3A;cursor:pointer;margin-bottom:1rem;">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1"
                       {{ old('is_published', $isEdit ? $album->is_published : false) ? 'checked' : '' }}
                       style="width:16px;height:16px;accent-color:#2A7FC1;">
                Tayangkan di halaman publik
            </label>

            <label style="{{ $label }}">Urutan <span style="{{ $hint }}">(opsional, untuk tanggal yang sama)</span></label>
            <input type="number" name="sort_order" min="0" value="{{ $val('sort_order', 0) }}" style="{{ $input }}margin-bottom:1rem;"/>
            @error('sort_order') <p style="{{ $err }}">{{ $message }}</p> @enderror

            <button type="submit"
                    style="width:100%;padding:0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                {{ $isEdit ? 'Simpan Perubahan' : 'Buat Album' }}
            </button>
            @unless ($isEdit)
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.5rem;">Foto ditambahkan setelah album dibuat.</p>
            @endunless
        </div>

        <div style="{{ $card }}">
            <p style="{{ $label }}margin-bottom:1rem;">Keterkaitan <span style="{{ $hint }}">(opsional)</span></p>

            <label style="{{ $label }}">Daerah</label>
            <select name="region_id" style="{{ $input }}margin-bottom:1rem;">
                <option value="">ASPAPI Pusat</option>
                @foreach ($regions as $r)
                    <option value="{{ $r->id }}" {{ (string) $val('region_id') === (string) $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                @endforeach
            </select>
            @error('region_id') <p style="{{ $err }}">{{ $message }}</p> @enderror

            <label style="{{ $label }}">Agenda Terkait</label>
            <select name="agenda_id" style="{{ $input }}margin-bottom:1rem;">
                <option value="">— Tidak ada —</option>
                @foreach ($agendas as $a)
                    <option value="{{ $a->id }}" {{ (string) $val('agenda_id') === (string) $a->id ? 'selected' : '' }}>
                        {{ $a->event_date?->format('d/m/Y') }} — {{ Str::limit($a->title, 50) }}{{ $a->region ? ' ('.$a->region->name.')' : '' }}
                    </option>
                @endforeach
            </select>
            @error('agenda_id') <p style="{{ $err }}">{{ $message }}</p> @enderror

            <label style="{{ $label }}">Seminar Terkait</label>
            <select name="seminar_id" style="{{ $input }}">
                <option value="">— Tidak ada —</option>
                @foreach ($seminars as $s)
                    <option value="{{ $s->id }}" {{ (string) $val('seminar_id') === (string) $s->id ? 'selected' : '' }}>{{ Str::limit($s->title, 60) }}</option>
                @endforeach
            </select>
            @error('seminar_id') <p style="{{ $err }}">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

<style>
    @media (max-width: 900px) {
        .gallery-form-grid { grid-template-columns: 1fr !important; }
    }
</style>
