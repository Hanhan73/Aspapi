@extends('layouts.admin')
@php
    $title = 'Kelola Foto';
    $breadcrumbs = [
        ['label' => 'Galeri', 'url' => route('admin.gallery.index')],
        ['label' => Str::limit($album->title, 30), 'url' => route('admin.gallery.edit', $album)],
        ['label' => 'Foto', 'url' => '#'],
    ];
    $btn = 'font-size:0.7rem;font-weight:700;padding:0.3rem 0.625rem;border-radius:3px;background:transparent;cursor:pointer;';
@endphp

@section('content')

<div x-data="galleryManager(@js([
        'photos'       => $photos,
        'coverId'      => $album->cover_photo_id,
        'uploadUrl'    => route('admin.gallery.photos.store', $album),
        'reorderUrl'   => route('admin.gallery.photos.reorder', $album),
        'photoUrlBase' => url('admin/galeri/'.$album->id.'/foto'),
     ]))"
     x-init="init()">

{{-- Header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.25rem;">
    <div>
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#C0392B;">Kelola Foto</p>
        <h2 style="font-family:'DM Serif Display',serif;font-size:1.5rem;color:#1A2A3A;margin-top:0.125rem;">{{ $album->title }}</h2>
        <p style="font-size:0.8rem;color:#4A6580;margin-top:0.25rem;">
            <span x-text="photos.length"></span> foto ·
            {{ $album->is_published ? 'Tayang' : 'Draft' }}
        </p>
    </div>
    <div style="display:flex;gap:0.5rem;">
        @if ($album->is_published && Route::has('galleries.show'))
        <a href="{{ route('galleries.show', $album->slug) }}" target="_blank"
           style="{{ $btn }}border:1.5px solid #D6E8F7;color:#4A6580;text-decoration:none;padding:0.5rem 0.875rem;">Lihat Publik</a>
        @endif
        <a href="{{ route('admin.gallery.edit', $album) }}"
           style="{{ $btn }}border:1.5px solid #2A7FC1;color:#2A7FC1;text-decoration:none;padding:0.5rem 0.875rem;">Edit Info Album</a>
    </div>
</div>

{{-- Dropzone --}}
<div @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)"
     :style="dragging ? { borderColor: '#2A7FC1', background: '#EEF4FB' } : {}"
     style="background:#fff;border:2px dashed #B0CCDF;border-radius:6px;padding:2rem 1.25rem;text-align:center;margin-bottom:1rem;transition:all .15s;">
    <svg style="width:36px;height:36px;color:#2A7FC1;margin:0 auto 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
    </svg>
    <p style="font-size:0.9rem;color:#1A2A3A;font-weight:600;">Seret & lepas foto ke sini</p>
    <p style="font-size:0.75rem;color:#7A93AB;margin:0.25rem 0 0.875rem;">JPG, PNG, WebP · maks 8 MB per file · bisa banyak sekaligus</p>
    <label style="display:inline-block;background:#2A7FC1;color:#fff;padding:0.55rem 1.1rem;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
        Pilih File
        <input type="file" multiple accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only"
               @change="addFiles($event.target.files); $event.target.value = ''">
    </label>
</div>

{{-- Antrian upload --}}
<template x-if="queue.length">
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
            <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;">
                Upload: <span x-text="queue.filter(q => q.status === 'done').length"></span> / <span x-text="queue.length"></span>
                <span x-show="queue.some(q => q.status === 'error')" style="color:#C0392B;">
                    · <span x-text="queue.filter(q => q.status === 'error').length"></span> gagal
                </span>
            </p>
            <button type="button" @click="clearFinished()" x-show="!busy" style="{{ $btn }}border:1.5px solid #D6E8F7;color:#4A6580;">Bersihkan</button>
        </div>
        <div style="display:flex;flex-direction:column;gap:0.5rem;max-height:260px;overflow:auto;">
            <template x-for="item in queue" :key="item.key">
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:0.78rem;color:#1A2A3A;gap:1rem;">
                        <span x-text="item.name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
                        <span style="flex-shrink:0;"
                              :style="{ color: item.status === 'error' ? '#C0392B' : (item.status === 'done' ? '#276749' : '#7A93AB') }"
                              x-text="item.status === 'error' ? item.error
                                    : item.status === 'done' ? 'Selesai'
                                    : item.status === 'processing' ? 'Memproses…'
                                    : item.status === 'uploading' ? item.progress + '%'
                                    : 'Menunggu'"></span>
                    </div>
                    <div style="height:4px;background:#EEF4FB;border-radius:2px;overflow:hidden;margin-top:0.25rem;">
                        <div style="height:100%;transition:width .15s;"
                             :style="{ width: (item.status === 'error' ? 100 : item.progress) + '%', background: item.status === 'error' ? '#C0392B' : (item.status === 'done' ? '#38A169' : '#2A7FC1') }"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<div class="gallery-photos-layout" style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;">

    {{-- Grid foto --}}
    <div>
        <p x-show="photos.length > 1" style="font-size:0.75rem;color:#7A93AB;margin-bottom:0.625rem;">
            Seret <strong>⠿</strong> untuk mengubah urutan. Catatan tersimpan otomatis saat kolom ditinggalkan.
        </p>

        <div x-ref="grid" class="gallery-admin-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
            <template x-for="photo in photos" :key="photo.id">
                <div class="photo-card" :data-id="photo.id"
                     @click="selectedId = photo.id"
                     :style="{ boxShadow: selectedId === photo.id ? '0 0 0 2px #2A7FC1' : 'none' }"
                     style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;display:flex;flex-direction:column;">
                    <div style="position:relative;aspect-ratio:4/3;background:#EEF4FB;">
                        <img :src="photo.thumb_url" alt="" loading="lazy"
                             :width="photo.thumb_width" :height="photo.thumb_height"
                             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block;">
                        <span class="drag-handle" title="Seret untuk mengurutkan"
                              style="position:absolute;top:6px;left:6px;background:rgba(26,42,58,.75);color:#fff;border-radius:3px;padding:2px 7px;cursor:grab;font-size:0.9rem;user-select:none;">⠿</span>
                        <span x-show="photo.id === coverId"
                              style="position:absolute;top:6px;right:6px;background:#2A7FC1;color:#fff;border-radius:2px;padding:2px 7px;font-size:0.6rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Sampul</span>
                    </div>
                    <div style="padding:0.625rem;display:flex;flex-direction:column;gap:0.5rem;flex:1;">
                        <div style="position:relative;">
                            <textarea rows="2" maxlength="500" placeholder="Catatan foto (opsional)"
                                      x-model="photo.note"
                                      @focus="selectedId = photo.id; photo._orig = photo.note"
                                      @blur="saveNote(photo)"
                                      style="width:100%;padding:0.4rem 0.55rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.78rem;color:#1A2A3A;outline:none;resize:vertical;box-sizing:border-box;"></textarea>
                            <span x-show="photo._saved" x-transition.opacity
                                  style="position:absolute;right:6px;bottom:8px;font-size:0.65rem;color:#276749;">Tersimpan ✓</span>
                            <span x-show="photo._error" style="font-size:0.65rem;color:#C0392B;" x-text="photo._error"></span>
                        </div>
                        <div style="display:flex;gap:0.375rem;margin-top:auto;">
                            <button type="button" @click.stop="setCover(photo)" :disabled="photo.id === coverId"
                                    :style="{ opacity: photo.id === coverId ? .45 : 1, cursor: photo.id === coverId ? 'default' : 'pointer' }"
                                    style="{{ $btn }}border:1.5px solid #2A7FC1;color:#2A7FC1;flex:1;">Jadikan Sampul</button>
                            <button type="button" @click.stop="remove(photo)"
                                    style="{{ $btn }}border:1.5px solid #C0392B;color:#C0392B;">Hapus</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="!photos.length" style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
            Belum ada foto di album ini.
        </div>
    </div>

    {{-- Preview caption (markup sama dengan caption lightbox publik) --}}
    <div style="position:sticky;top:1rem;">
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.25rem;">
            <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.75rem;">
                Preview Caption
            </p>
            <div style="background:#0f1720;border-radius:4px;overflow:hidden;">
                <template x-if="selected()">
                    <img :src="selected().thumb_url" alt="" style="width:100%;max-height:220px;object-fit:contain;display:block;background:#000;">
                </template>
                <div class="pswp-caption-preview">
                    <x-gallery-caption :album="$album" />
                    {{-- Baris 3 (note) — mengikuti kelas di komponen x-gallery-caption --}}
                    <div class="gallery-caption text-sm leading-snug" x-show="selected() && selected().note && selected().note.trim()">
                        <p class="italic opacity-90" x-text="selected() ? selected().note.trim() : ''"></p>
                    </div>
                </div>
            </div>
            <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.5rem;">
                Baris 1–2 diambil dari info album. Klik foto untuk melihat caption-nya.
            </p>
        </div>
    </div>
</div>

</div>

@endsection

@push('styles')
<style>
    .sortable-ghost { opacity: .4; }
    .sr-only { position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0; }
    .pswp-caption-preview { padding: .75rem 1rem; color: #fff; }
    .pswp-caption-preview .gallery-caption + .gallery-caption { margin-top: .125rem; }
    @media (max-width: 1000px) { .gallery-photos-layout { grid-template-columns: 1fr !important; } }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
window.galleryManager = function (cfg) {
    const ALLOWED  = ['image/jpeg', 'image/png', 'image/webp'];
    const EXT      = /\.(jpe?g|png|webp)$/i;
    const MAX_SIZE = 8 * 1024 * 1024;
    const PARALLEL = 3;

    return {
        photos: cfg.photos.map(p => ({ ...p, _saved: false, _error: '' })),
        coverId: cfg.coverId,
        selectedId: cfg.photos.length ? cfg.photos[0].id : null,
        queue: [],
        active: 0,
        dragging: false,
        keySeq: 0,

        get busy() { return this.active > 0 || this.queue.some(q => q.status === 'queued'); },

        init() {
            Sortable.create(this.$refs.grid, {
                handle: '.drag-handle',
                draggable: '.photo-card',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: (evt) => {
                    const from = evt.oldDraggableIndex, to = evt.newDraggableIndex;
                    if (from === to) return;

                    // Kembalikan DOM, biarkan Alpine (x-for) yang menyusun ulang.
                    const cards = [...this.$refs.grid.querySelectorAll('.photo-card')].filter(el => el !== evt.item);
                    cards[from] ? this.$refs.grid.insertBefore(evt.item, cards[from]) : this.$refs.grid.appendChild(evt.item);

                    const moved = this.photos.splice(from, 1)[0];
                    this.photos.splice(to, 0, moved);
                    this.saveOrder();
                },
            });

            window.addEventListener('beforeunload', (e) => {
                if (this.busy) { e.preventDefault(); e.returnValue = ''; }
            });
        },

        selected() {
            return this.photos.find(p => p.id === this.selectedId) || null;
        },

        // ── Upload ───────────────────────────────────────────────
        addFiles(fileList) {
            for (const file of fileList) {
                const item = { key: ++this.keySeq, file, name: file.name, progress: 0, status: 'queued', error: '' };

                if (!ALLOWED.includes(file.type) && !EXT.test(file.name)) {
                    Object.assign(item, { status: 'error', error: 'Format tidak didukung' });
                } else if (file.size > MAX_SIZE) {
                    Object.assign(item, { status: 'error', error: 'Lebih dari 8 MB' });
                }
                this.queue.push(item);
            }
            this.pump();
        },

        pump() {
            while (this.active < PARALLEL) {
                const next = this.queue.find(q => q.status === 'queued');
                if (!next) return;
                this.upload(next);
            }
        },

        upload(item) {
            this.active++;
            item.status = 'uploading';

            const form = new FormData();
            form.append('photo', item.file);

            axios.post(cfg.uploadUrl, form, {
                onUploadProgress: (e) => {
                    if (!e.total) return;
                    item.progress = Math.min(99, Math.round(e.loaded * 100 / e.total));
                    if (e.loaded >= e.total) item.status = 'processing';
                },
            })
            .then(({ data }) => {
                item.status = 'done';
                item.progress = 100;
                item.file = null;
                this.photos.push({ ...data.photo, _saved: false, _error: '' });
                if (!this.selectedId) this.selectedId = data.photo.id;
            })
            .catch((err) => {
                item.status = 'error';
                item.error = this.errorMessage(err);
            })
            .finally(() => {
                this.active--;
                this.pump();
            });
        },

        errorMessage(err) {
            const r = err.response;
            if (!r) return 'Koneksi terputus';
            if (r.status === 413) return 'File terlalu besar untuk server';
            if (r.status === 419) return 'Sesi habis, muat ulang halaman';
            return r.data?.errors?.photo?.[0] || r.data?.message || ('Gagal (' + r.status + ')');
        },

        clearFinished() {
            this.queue = this.queue.filter(q => q.status === 'queued' || q.status === 'uploading' || q.status === 'processing');
        },

        // ── Aksi per foto ────────────────────────────────────────
        photoUrl(photo, suffix = '') {
            return cfg.photoUrlBase + '/' + photo.id + suffix;
        },

        saveNote(photo) {
            if ((photo.note ?? '') === (photo._orig ?? '')) return;
            photo._error = '';
            axios.patch(this.photoUrl(photo), { note: photo.note })
                .then(({ data }) => {
                    photo.note = data.photo.note;
                    photo._orig = photo.note;
                    photo._saved = true;
                    setTimeout(() => photo._saved = false, 1800);
                })
                .catch((err) => { photo._error = this.errorMessage(err); });
        },

        setCover(photo) {
            axios.post(this.photoUrl(photo, '/cover'))
                .then(({ data }) => { this.coverId = data.cover_photo_id; })
                .catch((err) => alert('Gagal menjadikan sampul: ' + this.errorMessage(err)));
        },

        remove(photo) {
            if (!confirm('Hapus foto ini? File akan dihapus permanen.')) return;
            axios.delete(this.photoUrl(photo))
                .then(({ data }) => {
                    this.photos = this.photos.filter(p => p.id !== photo.id);
                    this.coverId = data.cover_photo_id;
                    if (this.selectedId === photo.id) this.selectedId = this.photos[0]?.id ?? null;
                })
                .catch((err) => alert('Gagal menghapus: ' + this.errorMessage(err)));
        },

        saveOrder() {
            axios.post(cfg.reorderUrl, { ids: this.photos.map(p => p.id) })
                .catch(() => alert('Gagal menyimpan urutan. Muat ulang halaman.'));
        },
    };
};
</script>
@endpush
