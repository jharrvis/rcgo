# Plan Penambahan Fitur Artikel RCGO

## Ringkasan

Fitur artikel akan dibuat secara native menggunakan Laravel, Livewire, Blade, dan Tailwind CSS. Artikel dikelola oleh Admin melalui dashboard dan ditampilkan secara publik untuk mendukung edukasi calon mitra, kepercayaan pengguna, serta SEO.

Implementasi mengikuti pola CRUD yang sudah digunakan pada modul Paket Usaha, dengan halaman publik yang dirender di server agar mudah diindeks mesin pencari.

## Sasaran

- Admin dapat membuat, mengedit, melihat preview, menerbitkan, dan menghapus artikel.
- Draft tidak dapat diakses dari frontend publik.
- Artikel dapat dijadwalkan menggunakan tanggal publikasi.
- Frontend memiliki halaman daftar dan detail artikel.
- Landing page menampilkan artikel terbaru atau unggulan.
- Artikel memiliki metadata SEO, Open Graph, dan structured data.
- Tampilan konsisten dengan identitas visual RCGO dan responsif pada perangkat mobile.

## Ruang Lingkup MVP

- Artikel hanya dikelola oleh pengguna dengan role Admin.
- Isi artikel menggunakan Markdown dengan preview.
- Featured image tunggal untuk setiap artikel.
- Status utama: `draft` dan `published`.
- Artikel berstatus `published` dengan `published_at` di masa depan dianggap terjadwal.
- Kategori disimpan sebagai string sederhana.
- Tags disimpan dalam format JSON.
- Artikel dapat ditandai sebagai artikel unggulan.
- Komentar, galeri, inline image manager, dan workflow approval tidak termasuk MVP.

## 1. Database dan Model

### Tabel `articles`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `author_id` | foreign key nullable | Relasi ke tabel `users` |
| `title` | string | Judul artikel |
| `slug` | string unique | URL artikel |
| `excerpt` | text nullable | Ringkasan kartu dan meta description default |
| `content` | longText | Isi artikel dalam Markdown |
| `featured_image` | string nullable | Path gambar pada public storage |
| `featured_image_alt` | string nullable | Alt text featured image |
| `category` | string nullable, indexed | Kategori artikel |
| `tags` | json nullable | Daftar tag |
| `status` | string, indexed | `draft` atau `published` |
| `is_featured` | boolean, default false | Penanda artikel unggulan |
| `published_at` | timestamp nullable, indexed | Waktu publikasi atau jadwal |
| `seo_title` | string nullable | Judul SEO khusus |
| `seo_description` | string nullable | Deskripsi SEO khusus |
| timestamps | timestamp | `created_at` dan `updated_at` |
| soft deletes | timestamp nullable | Mendukung pemulihan artikel |

### Aturan Relasi

- `Article` memiliki relasi `belongsTo(User::class, 'author_id')`.
- Foreign key author menggunakan `nullOnDelete` agar artikel tidak ikut terhapus ketika akun Admin dihapus.
- Model `User` memiliki relasi `hasMany(Article::class, 'author_id')`.

### Model `Article`

Model perlu memiliki:

- Cast untuk `tags`, `is_featured`, dan `published_at`.
- Scope `published()` yang memastikan:
  - status adalah `published`;
  - `published_at` tidak kosong;
  - `published_at` lebih kecil atau sama dengan waktu saat ini.
- Scope `featured()` untuk artikel unggulan.
- Route model binding menggunakan `slug`.
- Helper atau service untuk menghasilkan slug unik.
- Helper untuk merender Markdown secara aman.

### File

```text
database/migrations/..._create_articles_table.php
app/Models/Article.php
database/factories/ArticleFactory.php
database/seeders/ArticleSeeder.php
```

## 2. CRUD Artikel di Admin Dashboard

### Struktur Komponen

```text
app/Livewire/Admin/Article/
├── ArticleIndex.php
└── ArticleForm.php

resources/views/livewire/admin/article/
├── article-index.blade.php
└── article-form.blade.php
```

Komponen mengikuti pola modul berikut:

- `app/Livewire/Admin/Product/PackageIndex.php`
- `app/Livewire/Admin/Product/PackageForm.php`
- `resources/views/livewire/admin/product/package-index.blade.php`
- `resources/views/livewire/admin/product/package-form.blade.php`

### Halaman Daftar Artikel

Fitur yang diperlukan:

- Pencarian berdasarkan judul, excerpt, kategori, atau author.
- Search menggunakan debounce sekitar 300 ms.
- Filter status: Semua, Draft, Terjadwal, dan Terbit.
- Filter kategori.
- Sorting berdasarkan tanggal dibuat atau tanggal publikasi.
- Pagination 10 sampai 20 artikel per halaman.
- Query string untuk mempertahankan search, filter, sort, dan halaman.
- Kolom atau informasi:
  - thumbnail;
  - judul;
  - kategori;
  - author;
  - status;
  - tanggal publikasi;
  - tanggal terakhir diperbarui.
- Status badge untuk Draft, Terjadwal, dan Terbit.
- Aksi edit, preview, publish/unpublish, dan delete.
- Konfirmasi sebelum delete.
- Empty state dengan tombol `Buat Artikel`.
- Tampilan mobile menggunakan kartu atau tabel dengan horizontal scroll yang terkontrol.

### Form Artikel

Form dibagi menjadi beberapa kelompok.

#### Konten Utama

- Judul.
- Slug otomatis dari judul tetapi tetap dapat diedit.
- Excerpt.
- Isi Markdown.
- Preview hasil Markdown.

#### Media

- Featured image.
- Preview gambar sebelum upload.
- Alt text gambar.
- Informasi ukuran dan tipe file yang didukung.

#### Klasifikasi

- Kategori.
- Tags.

#### Publikasi

- Status.
- Tanggal dan waktu publikasi.
- Penanda artikel unggulan.

#### SEO

- SEO title dengan indikator sekitar 60 karakter.
- SEO description dengan indikator sekitar 160 karakter.
- Preview sederhana hasil tampilan pada mesin pencari.

### Perilaku Form

- Menampilkan label yang selalu terlihat.
- Error ditampilkan di bawah field terkait.
- Fokus diarahkan ke field invalid pertama setelah submit gagal.
- Tombol submit disabled saat request berlangsung.
- Loading state saat menyimpan dan mengunggah gambar.
- Aksi utama:
  - Simpan Draft;
  - Terbitkan atau Jadwalkan;
  - Preview.
- Peringatan saat pengguna meninggalkan form dengan perubahan yang belum disimpan.
- Tombol delete dipisahkan secara visual dari aksi simpan.
- Semua target klik utama memiliki tinggi minimal 44 px.

### Upload Gambar

- Menggunakan trait `WithFileUploads`.
- Disimpan ke `storage/app/public/articles/{year}/{month}`.
- Format yang diizinkan: JPEG, PNG, dan WebP.
- Ukuran maksimal awal: 2 MB.
- Nama file tidak menggunakan nama mentah dari pengguna.
- Gambar lama dihapus saat diganti.
- Gambar dihapus saat artikel dihapus permanen, bukan saat soft delete.
- Frontend menetapkan dimensi atau aspect ratio untuk mencegah layout shift.

## 3. Route dan Otorisasi

### Route Admin

Route ditempatkan di dalam middleware `auth` dan `admin`.

```text
GET /admin/articles
GET /admin/articles/create
GET /admin/articles/{article}/edit
GET /admin/articles/{article}/preview
```

Nama route:

```text
admin.articles.index
admin.articles.create
admin.articles.edit
admin.articles.preview
```

### Route Publik

```text
GET /artikel
GET /artikel/{article:slug}
```

Nama route:

```text
articles.index
articles.show
```

### Aturan Akses

- Guest dan Mitra hanya dapat melihat artikel yang sudah diterbitkan.
- Draft tidak boleh dapat ditemukan melalui route publik.
- Artikel terjadwal belum dapat dibuka sebelum `published_at`.
- Preview draft hanya dapat diakses oleh Admin.
- Query artikel publik selalu menggunakan scope `published()`.

## 4. Integrasi Admin Dashboard

### Sidebar

Tambahkan grup menu baru:

```text
Konten
└── Artikel
```

Menu menggunakan route `admin.articles.index` dan active state `admin.articles.*`.

File integrasi:

```text
resources/views/components/dashboard/sidebar.blade.php
```

### Global Search

Tambahkan pencarian artikel pada:

```text
app/Livewire/GlobalSearch.php
```

Hasil pencarian Admin menampilkan:

- tipe `Artikel`;
- judul;
- kategori dan status;
- URL menuju form edit;
- icon yang konsisten dengan sidebar.

### Statistik Dashboard Opsional

Setelah CRUD stabil, dashboard dapat menampilkan:

- total artikel terbit;
- total draft;
- total artikel terjadwal;
- lima artikel yang terakhir diperbarui.

Statistik ini bukan bagian wajib MVP awal.

## 5. Frontend Publik

### Struktur File

```text
app/Http/Controllers/ArticleController.php

resources/views/articles/
├── index.blade.php
└── show.blade.php

resources/views/partials/landing/
└── articles.blade.php
```

### Landing Page

Landing page menampilkan maksimal tiga artikel terbaru atau unggulan.

Urutan section yang direkomendasikan:

```text
Testimoni
→ Artikel / Tips Bisnis RC
→ FAQ
→ CTA
```

Alasannya:

- Testimoni membangun kepercayaan.
- Artikel memberi edukasi dan menjawab kebutuhan informasi.
- FAQ mengatasi keberatan yang tersisa.
- CTA menjadi langkah konversi terakhir.

Section artikel memuat:

- heading `Tips & Insight Bisnis RC`;
- maksimal tiga kartu;
- gambar rasio 16:9;
- kategori dan tanggal publikasi;
- judul maksimal dua baris;
- excerpt maksimal tiga baris;
- link `Baca Selengkapnya`;
- tombol `Lihat Semua Artikel`.

`LandingPageController` mengambil artikel dengan aturan:

1. hanya artikel dari scope `published()`;
2. artikel `is_featured` diprioritaskan;
3. diurutkan berdasarkan `published_at` terbaru;
4. maksimal tiga artikel.

File yang diperbarui:

```text
app/Http/Controllers/LandingPageController.php
resources/views/welcome.blade.php
resources/views/partials/landing/articles.blade.php
```

### Navbar dan Footer

Tambahkan link `Artikel` pada:

- navigasi desktop;
- navigasi mobile;
- footer.

Link mengarah ke route `articles.index`, bukan anchor landing page.

File:

```text
resources/views/partials/landing/nav.blade.php
resources/views/partials/landing/footer.blade.php
```

### Halaman Daftar Artikel

Struktur halaman:

- hero ringkas dengan heading tunggal;
- artikel unggulan;
- pencarian artikel;
- filter kategori;
- grid artikel;
- pagination;
- empty state saat tidak ada hasil.

Responsiveness:

- satu kolom pada mobile;
- dua kolom pada tablet;
- tiga kolom pada desktop;
- tidak ada horizontal scroll;
- ukuran teks body minimal 16 px pada mobile.

### Halaman Detail Artikel

Struktur halaman:

- breadcrumb;
- judul artikel;
- kategori, tanggal, dan author;
- featured image;
- isi artikel;
- CTA menuju paket usaha;
- maksimal tiga artikel terkait;
- link kembali ke daftar artikel.

Area isi artikel menggunakan ukuran baca yang nyaman:

- lebar maksimal sekitar 65 sampai 75 karakter;
- line-height sekitar 1.6 sampai 1.75;
- heading berurutan tanpa melewati level;
- link memiliki focus state yang jelas;
- gambar memiliki alt text dan dimensi.

## 6. SEO

Setiap halaman detail artikel memiliki:

- title dari `seo_title` atau judul artikel;
- meta description dari `seo_description` atau excerpt;
- canonical URL;
- Open Graph title, description, image, dan URL;
- Twitter Card;
- JSON-LD dengan tipe `Article`;
- `datePublished` dan `dateModified`;
- informasi author;
- satu elemen `h1`;
- breadcrumb yang konsisten.

Aturan tambahan:

- Preview draft menggunakan `noindex, nofollow`.
- Artikel terbit ditambahkan ke sitemap jika sitemap dibuat pada fase implementasi.
- Slug lama perlu dipertahankan atau dialihkan apabila fitur perubahan slug dikembangkan kemudian.

## 7. Keamanan Konten

- Jangan mencetak HTML editor secara langsung tanpa sanitasi.
- Markdown dirender dengan raw HTML dinonaktifkan atau dibuang.
- Unsafe links harus ditolak.
- Gunakan konfigurasi renderer yang konsisten pada preview Admin dan frontend.
- Validasi panjang judul, slug, excerpt, SEO title, dan SEO description.
- Semua operasi CRUD berada di middleware Admin.
- Jangan menggunakan nilai `author_id` yang dikirim langsung oleh browser; gunakan user Admin yang sedang login.

Implementasi dapat menggunakan `Str::markdown()` jika dependency CommonMark tersedia. Jika belum tersedia, tambahkan `league/commonmark` secara eksplisit atau buat service renderer yang dapat diuji.

## 8. Pengujian

### Model dan Scope

- Relasi artikel dengan author.
- Cast tags dan tanggal publikasi.
- Scope published tidak mengambil draft.
- Scope published tidak mengambil artikel terjadwal.
- Scope featured bekerja dengan benar.
- Slug unik untuk judul yang sama.

### Route Publik

- Guest dapat membuka daftar artikel.
- Guest dapat membuka artikel terbit.
- Draft menghasilkan 404 pada route publik.
- Artikel terjadwal menghasilkan 404 sebelum waktunya.
- Slug yang tidak ada menghasilkan 404.
- Pagination dan filter kategori bekerja.
- Search hanya mencari artikel terbit.

### Otorisasi Admin

- Guest diarahkan ke login.
- Mitra mendapat 403 pada semua route CRUD artikel.
- Admin dapat membuka daftar, create, edit, dan preview.

### Livewire CRUD

- Admin dapat membuat draft.
- Admin dapat menerbitkan artikel.
- Admin dapat menjadwalkan artikel.
- Validasi field wajib berjalan.
- Upload gambar menggunakan `Storage::fake('public')`.
- Gambar lama dibersihkan setelah penggantian berhasil.
- Search, filter, sort, dan pagination berjalan.
- Soft delete bekerja.

### Frontend dan SEO

- Landing page mengambil maksimal tiga artikel.
- Draft tidak muncul pada landing page.
- Artikel unggulan diprioritaskan.
- Meta title dan description tersedia.
- Canonical URL benar.
- JSON-LD valid secara struktur.
- Featured image memiliki alt text.

## 9. Tahapan Implementasi

### Fase 1 — Fondasi Data

- Buat migration tabel `articles`.
- Buat model dan relasi.
- Tambahkan factory dan seeder.
- Implementasikan scope publikasi dan slug unik.
- Tambahkan test model.

### Fase 2 — CRUD Admin

- Buat `ArticleIndex`.
- Buat `ArticleForm`.
- Buat view daftar dan form.
- Implementasikan upload featured image.
- Tambahkan route Admin.
- Tambahkan test Livewire dan otorisasi.

### Fase 3 — Navigasi Admin

- Tambahkan menu Artikel pada sidebar.
- Tambahkan Artikel ke Global Search.
- Verifikasi active state dan tampilan mobile dashboard.

### Fase 4 — Frontend Publik

- Buat `ArticleController`.
- Buat halaman index dan detail.
- Tambahkan route publik.
- Tambahkan filter, search, dan pagination.
- Tambahkan related articles dan CTA.

### Fase 5 — Integrasi Landing Page

- Tambahkan section artikel terbaru.
- Perbarui `LandingPageController`.
- Tambahkan link navbar desktop dan mobile.
- Tambahkan link footer.

### Fase 6 — SEO dan Hardening

- Tambahkan meta tag dan canonical.
- Tambahkan Open Graph dan Twitter Card.
- Tambahkan JSON-LD.
- Pastikan Markdown disanitasi.
- Tambahkan sitemap bila dibutuhkan.

### Fase 7 — QA dan Dokumentasi

- Jalankan test suite.
- Uji pada mobile 375 px, tablet, dan desktop.
- Uji keyboard navigation dan focus state.
- Uji contrast text dan status badge.
- Uji upload, replace, soft delete, dan restore.
- Perbarui README dengan cara mengelola artikel.

## 10. Definition of Done

Fitur dianggap selesai jika:

- Admin dapat membuat, mengedit, preview, menerbitkan, menjadwalkan, dan menghapus artikel.
- Mitra dan Guest tidak dapat mengakses CRUD Admin.
- Draft dan artikel terjadwal tidak bocor ke frontend.
- Artikel tampil pada landing page, halaman daftar, dan halaman detail.
- Search, filter, pagination, dan upload featured image berjalan.
- Konten Markdown dirender secara aman.
- Metadata SEO dan structured data tersedia.
- Navbar desktop, navbar mobile, sidebar Admin, dan footer telah diperbarui.
- Tampilan responsif dan dapat digunakan dengan keyboard.
- Test artikel lulus.
- Dokumentasi pengelolaan artikel tersedia.

## 11. Pengembangan Lanjutan

Fitur berikut dapat ditambahkan setelah MVP stabil:

- tabel kategori dan tag terpisah;
- rich-text block editor;
- inline media library;
- autosave draft;
- revision history;
- multi-author workflow dan approval;
- scheduled command untuk notifikasi artikel terbit;
- view counter dan analytics;
- artikel rekomendasi berbasis tag;
- RSS feed;
- share buttons;
- redirect history saat slug berubah;
- bulk publish dan bulk delete.

