# BKPM Acara 24 — Integrasi API dengan Frontend (Fetch API dan CORS)

Lanjutan Acara 23. Backend (Router, Controller, Model, autentikasi) tidak berubah. Yang ditambahkan:

- `public/index.php` — header CORS dipasang satu kali sebelum Router dijalankan (Langkah 1).
- `public/views/index.html` dan `public/views/app.js` — view pertama yang mengambil data `GET /mahasiswa` dengan Fetch API (Langkah 2 sampai 4).
- Tugas Praktik Mandiri: indikator "Memuat data..." dan pesan "Belum ada data".

## Membuka halaman view

Letakkan folder di `C:\xampp\htdocs\BKPM\ACARA24\si-akademik_bkpmpt24`, nyalakan Apache dan MySQL, lalu buka:

`http://localhost/BKPM/ACARA24/si-akademik_bkpmpt24/public/views/index.html`

(atau jalankan `jalankan.bat`, lalu buka `http://localhost:8080/views/index.html`). Langkah pengujian lengkap ada di `docs/PANDUAN_ACARA24.md`.

## Cara membuka proyek

1. Jalankan Apache dan MySQL pada XAMPP.
2. Pastikan database `db_workshop` sudah tersedia dan sesuai isi proyek Acara 21.
3. Buka folder proyek ini di VS Code.
4. Buka `docs/openapi.yaml`.
5. Buka https://editor.swagger.io/.
6. Salin seluruh isi `openapi.yaml` ke panel kiri Swagger Editor.
7. Periksa pratinjau di panel kanan dan pastikan tidak ada pesan kesalahan parsing.
8. Simpan screenshot hasil visualisasi ke folder `docs/`.

## Catatan kesesuaian endpoint

Router proyek menggunakan parameter query `id` untuk GET satu mahasiswa, PUT, dan DELETE. Karena itu dokumentasi memakai `/mahasiswa?id=1` untuk ketiga operasi tersebut, bukan `/mahasiswa/{id}`. Hal ini mengikuti implementasi `app/core/Router.php` dan `MahasiswaController.php`.

Endpoint `/buku` memang tersedia pada Router dari proyek Acara 21, tetapi tidak ditambahkan ke spesifikasi Acara 22 ini karena langkah prosedur dan tugas mandiri Acara 22 yang menjadi acuan secara khusus meminta `/login`, `/mahasiswa`, `/register`, serta PUT dan DELETE `/mahasiswa`.

File `API_DOCUMENTATION.md` dari Acara 21 tetap dipertahankan sebagai dokumentasi sebelumnya.
