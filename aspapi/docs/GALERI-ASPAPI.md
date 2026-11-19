# Fitur Galeri untuk ASPAPI (port dari website LSP-KAP)

Taruh file ini di root proyek ASPAPI (mis. `docs/GALERI.md`), lalu kirim prompt di bawah ke Claude Code.

## Konteks proyek ASPAPI (sudah dicek dari repo)

- Laravel 12, **Blade + Alpine.js + Tailwind 3 + Vite**. Bukan React/Inertia, jadi galeri LSP-KAP
  **tidak di-copy kodenya**, tapi dibangun ulang dengan pola yang sudah ada di ASPAPI.
- Pola admin: `routes/web.php` grup `admin.` dengan middleware `['auth','admin']`,
  resource controller di `App\Http\Controllers\Admin\*AdminController`, view di `resources/views/admin/<fitur>/`
  (`index`, `create`, `edit`, `_form`). Contoh terdekat: Berita (`NewsAdminController`).
- Pola publik: controller di `App\Http\Controllers\Public\*`, view di `resources/views/public/<fitur>/`,
  layout `layouts.app`.
- Upload: disk `public` + `Storage::url()` (seperti thumbnail berita).
- Reorder drag & drop sudah ada di Mitra & Dokumen (`admin/partners/index`, `admin/documents/sort`).
- Ada **Agenda** (per daerah) dan **Seminar**, jadi album bisa (opsional) ditautkan ke salah satunya.

## Perbedaan dengan galeri LSP-KAP

| LSP-KAP | ASPAPI |
|---|---|
| Caption: tanggal · kegiatan · skema · tempat · TUK · asesor | Caption: tanggal · kegiatan · tempat · daerah · (agenda/seminar terkait) |
| Lightbox: yet-another-react-lightbox | Lightbox: **PhotoSwipe v5** (vanilla JS, zoom + swipe + caption) |
| Upload React (useForm) | Upload Alpine.js + fetch/axios dengan progress per file |
| Seed dari SIKAP | Tidak ada seed, diisi lewat admin |

---

## Prompt untuk Claude Code (proyek ASPAPI)

```
Tambahkan fitur Galeri (admin + publik). Ikuti pola yang SUDAH ADA di proyek ini
(lihat NewsAdminController & view admin/news untuk admin, public/news untuk publik,
layouts.admin & layouts.app, disk 'public' + Storage::url, Alpine.js, Tailwind 3).
Jangan memasang React/Vue. Baca docs/GALERI.md untuk konteks.

DATA
- gallery_albums: title, slug (unik), activity_type (string, default "Kegiatan ASPAPI",
  contoh: Seminar, Rapat Kerja, Kunjungan, Pelatihan), event_date, event_date_end (nullable),
  place (nullable), region_id (nullable, FK regions — untuk kegiatan ASPAPI daerah),
  agenda_id (nullable, FK agendas), seminar_id (nullable, FK seminars),
  description (nullable), cover_photo_id (nullable), is_published (bool), sort_order, timestamps.
- gallery_photos: gallery_album_id (FK, cascade), path, thumb_path, note (nullable),
  sort_order, timestamps.
- Model GalleryAlbum (relasi photos, region, agenda, seminar, cover; scope published)
  dan GalleryPhoto. Lengkapi $fillable & $casts.

CAPTION (dibentuk otomatis dari data album, satu helper/komponen Blade dipakai di mana-mana)
- Baris 1: tanggal (format Indonesia; rentang "16–18 Februari 2026" kalau ada event_date_end)
  · activity_type
- Baris 2: Tempat: … · Daerah: … · Agenda/Seminar: … (field kosong disembunyikan)
- Baris 3 (kalau ada): note foto
- Sanitasi semua teks (escape Blade biasa, jangan {!! !!}).

ADMIN (/admin/galeri, route name admin.gallery.*, middleware sama dengan Berita)
- Index: tabel (cover, judul, tanggal, daerah, jumlah foto, status), pencarian, filter tahun
  & daerah, paginasi 15 — samakan gaya dengan admin berita.
- Form album (_form): semua field di atas; daerah/agenda/seminar pakai select.
- Halaman "Kelola foto" per album:
  * Upload banyak foto sekaligus (drag & drop + pilih file), Alpine.js + axios, progress bar
    per file, upload paralel maks 3, validasi jpg/jpeg/png/webp maks 8 MB per file.
  * Grid foto: input note per foto (simpan otomatis saat blur), set sebagai cover, hapus
    (konfirmasi), drag untuk urutan — pakai cara reorder yang sama dengan Mitra/Dokumen.
  * Preview caption persis seperti di publik.
- Proses gambar di server: resize maks 1600px + thumbnail 600px, simpan WebP.
  Pasang intervention/image v3 (driver GD). Buat service App\Services\ImageStore yang
  bisa dipakai ulang.
- Hapus album → hapus file foto & thumbnail dari disk.
- Tambahkan menu "Galeri" di sidebar layouts/admin dekat menu Berita.

PUBLIK
- /galeri (route galleries.index): grid album (cover, judul, tanggal, daerah, jumlah foto),
  filter tahun & daerah, paginasi 12.
- /galeri/{slug} (galleries.show): header info kegiatan + grid foto (pakai thumbnail,
  loading="lazy", width/height diisi).
- Klik foto → lightbox PhotoSwipe v5 (npm photoswipe + photoswipe-dynamic-caption-plugin):
  zoom (pinch di HP, scroll/klik di desktop), swipe & panah, counter "3 / 24",
  caption di bawah foto. Inisialisasi lewat Alpine component atau modul JS terpisah
  yang hanya dimuat di halaman galeri.
- Album yang tidak published → 404.
- Di halaman detail Agenda & Seminar: kalau ada album terkait, tampilkan section
  "Dokumentasi" (beberapa thumbnail + link ke album).
- Di beranda: section cuplikan galeri (album terbaru) — ikuti gaya section beranda yang ada.
- Tambahkan menu Galeri di navbar publik.
- SEO: title & meta description per album, og:image = cover, masukkan ke sitemap.blade.php
  yang sudah ada.

TEST
- Feature test: admin bisa buat album & upload foto; publik tidak bisa lihat album draft (404);
  hapus album menghapus file.

Kerjakan bertahap: migrasi & model → admin album → kelola foto → publik + lightbox →
integrasi agenda/seminar/beranda/sitemap. Tunjukkan hasil tiap tahap.
```

---

## Catatan deploy

- Fitur ini menambah dependensi PHP (`intervention/image`) dan JS (`photoswipe`,
  `photoswipe-dynamic-caption-plugin`): jalankan `composer install` di server dan
  `npm run build` lalu upload hasil build seperti biasa.
- Cek cara ASPAPI menyajikan file disk `public` di hosting (symlink `storage` atau folder copy).
  Kalau di Hostinger symlink mati, pakai solusi yang sama dengan upload berita yang sudah jalan.
- Cek `upload_max_filesize` & `post_max_size` di hPanel (minimal 10M untuk foto 8 MB).
