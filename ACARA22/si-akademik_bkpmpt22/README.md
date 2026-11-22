# BKPM Acara 22 — Dokumentasi Endpoint OpenAPI

Proyek ini merupakan salinan kerja Acara 21 yang dilanjutkan untuk memenuhi materi Acara 22.
Kode aplikasi, Router, Controller, Model, autentikasi, dan database dipertahankan. Penambahan utama Acara 22 berada pada `docs/openapi.yaml`.

## Lokasi dan Base URL

Letakkan folder ini di:

`C:\xampp\htdocs\BKPM\ACARA22\si-akademik_bkpmpt22`

Base URL XAMPP:

`http://localhost/BKPM/ACARA22/si-akademik_bkpmpt22/public`

Database tetap menggunakan `db_workshop` seperti konfigurasi `app/config/Database.php`. Tidak perlu membuat database baru hanya untuk dokumentasi Acara 22.

## Berkas utama Acara 22

- `docs/openapi.yaml` — spesifikasi OpenAPI 3.0.0 yang mendokumentasikan:
  - `POST /register`
  - `POST /login`
  - `GET /mahasiswa` (opsional `?id=`)
  - `POST /mahasiswa` (Bearer Token)
  - `PUT /mahasiswa?id=...` (Bearer Token)
  - `DELETE /mahasiswa?id=...` (Bearer Token)
- `docs/PANDUAN_ACARA22.md` — petunjuk validasi Swagger dan bukti screenshot.

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
