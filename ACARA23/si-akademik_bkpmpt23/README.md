# BKPM Acara 23 — Manual Testing dan Blackbox Testing (Equivalence Partitioning)

Proyek ini merupakan lanjutan Acara 22. Kode Router, Model, autentikasi, dan database dipertahankan.
Acara 23 menguji `AuthController` dan `MahasiswaController` secara manual dengan Postman.

## Lokasi dan Base URL

Letakkan folder ini di `C:\xampp\htdocs\BKPM\ACARA23\si-akademik_bkpmpt23`.
Base URL XAMPP: `http://localhost/BKPM/ACARA23/si-akademik_bkpmpt23/public`
(atau jalankan `jalankan.bat`, base URL `http://localhost:8080`).

## Berkas Acara 23

- `docs/TEST_CASE.md` — laporan pengujian (28 test case, hasil aktual, Pass/Fail, temuan, perbaikan, retest).
- `postman/Acara23_TestCase.postman_collection.json` — collection test case dengan tab *Tests* otomatis.
- `database/reset_pengujian.sql` — membersihkan data uji sebelum collection dijalankan.

## Cara menguji di Postman

1. Jalankan `database/reset_pengujian.sql` di phpMyAdmin (database `db_workshop`).
2. Import collection Acara 23 dan environment `Local` atau `Local (php -S 8080)` dari folder `postman/`, lalu pilih environment itu.
3. Jalankan folder `00 Persiapan`, lalu folder `01` sampai `04` dari atas ke bawah (boleh lewat Runner).
4. Ulangi langkah 1 bila ingin menjalankan collection untuk kedua kalinya.

## Perbaikan hasil pengujian (Fail -> Pass)

- `MahasiswaController`: NIM duplikat kini dibalas **422** (sebelumnya 409) pada `store` dan `update`.
- `MahasiswaController::validateFormat`: NIM wajib 9 digit **angka** (ditambah `ctype_digit`).
- `docs/API_DOCUMENTATION.md` dan `docs/openapi.yaml` disesuaikan.

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
