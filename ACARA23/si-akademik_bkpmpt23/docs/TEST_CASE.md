# Laporan Pengujian — Acara 23 (Manual Testing & Blackbox Testing)

Proyek: `si-akademik_bkpmpt23` (lanjutan Acara 22). Teknik: *Equivalence Partitioning*. Alat: Postman, PHP, MySQL (XAMPP).

Ruang lingkup (peta Router): `POST /register`, `POST /login`, `GET|POST|PUT|DELETE /mahasiswa`.

Status **Pass** bila kode HTTP aktual termasuk dalam hasil yang diharapkan. **R1** = pengujian awal, **R2** = retest setelah perbaikan.

| ID | Skenario | Data Uji | Hasil Diharapkan | Aktual R1 | Status R1 | Aktual R2 | Status R2 |
|---|---|---|---|---|---|---|---|
| TC01 | NIM valid dan lengkap (MahasiswaController::store) | nim=220100123 | 201 | 201 | Pass | 201 | Pass |
| TC02 | NIM kosong (partisi tidak valid) | nim="" | 400 / 422 | 400 | Pass | 400 | Pass |
| TC03 | NIM duplikat (partisi tidak valid) | nim=220100123 (milik data TC01) | 422 | 409 | **Fail** | 422 | Pass |
| TC04 | Login kredensial benar (AuthController::login) | username & password benar | 200 | 200 | Pass | 200 | Pass |
| TC05 | Login kredensial salah | password salah | 401 | 401 | Pass | 401 | Pass |
| TC06 | Akses store tanpa token (Auth::requireAuth) | tanpa header Authorization | 401 | 401 | Pass | 401 | Pass |
| TC07 | NIM berisi huruf, panjang 9 (partisi tidak valid) | nim=ABCDEFGHI | 422 | 201 | **Fail** | 422 | Pass |
| TC08 | NIM kurang dari 9 karakter (batas bawah) | nim=22010012 | 422 | 422 | Pass | 422 | Pass |
| TC09 | Registrasi data valid (AuthController::register) | username=user_baru | 201 | 201 | Pass | 201 | Pass |
| TC10 | Registrasi username sudah terdaftar | username=penguji (sudah ada) | 409 / 422 | 409 | Pass | 409 | Pass |
| TC11 | GET daftar mahasiswa tanpa token (publik) | GET /mahasiswa | 200 | 200 | Pass | 200 | Pass |
| TC12 | Update data valid (MahasiswaController::update) | id=ID-A, nim=220100210 (baru) | 200 | 200 | Pass | 200 | Pass |
| TC13 | Update dengan NIM milik sendiri | id=ID-A, nim=220100210 | 200 | 200 | Pass | 200 | Pass |
| TC14 | Update ID tidak ada di database | id=999999 | 404 | 404 | Pass | 404 | Pass |
| TC15 | Update ID bukan angka | id=abc | 400 | 400 | Pass | 400 | Pass |
| TC16 | Update NIM kosong (partisi tidak valid) | id=ID-B, nim="" | 400 / 422 | 400 | Pass | 400 | Pass |
| TC17 | Update NIM duplikat milik data lain | id=ID-B, nim=220100202 | 422 | 409 | **Fail** | 422 | Pass |
| TC18 | Update NIM berisi huruf | id=ID-B, nim=ABCDEFGHI | 422 | 409 | **Fail** | 422 | Pass |
| TC19 | Update tanpa token | id=ID-B, tanpa Authorization | 401 | 401 | Pass | 401 | Pass |
| TC20 | Update body bukan JSON valid | body = teks biasa | 400 | 400 | Pass | 400 | Pass |
| TC21 | Hapus data valid (MahasiswaController::destroy) | id=ID-C | 200 | 200 | Pass | 200 | Pass |
| TC22 | Hapus ulang ID yang sudah terhapus | id=ID-C (sudah terhapus) | 404 | 404 | Pass | 404 | Pass |
| TC23 | Hapus ID tidak ada di database | id=999999 | 404 | 404 | Pass | 404 | Pass |
| TC24 | Hapus ID tidak valid (nol) | id=0 | 400 | 400 | Pass | 400 | Pass |
| TC25 | Hapus tanpa parameter id | tanpa id | 400 | 400 | Pass | 400 | Pass |
| TC26 | Hapus tanpa token | id=ID-B, tanpa Authorization | 401 | 401 | Pass | 401 | Pass |
| TC27 | Hapus dengan token kedaluwarsa | id=ID-B, token exp lewat | 401 | 401 | Pass | 401 | Pass |
| TC28 | Hapus dengan token dimanipulasi (signature salah) | id=ID-B, signature diubah | 401 | 401 | Pass | 401 | Pass |

**Ringkasan:** R1 = 24 Pass / 4 Fail dari 28. R2 (setelah perbaikan) = 28 Pass / 0 Fail.

## Temuan Fail dan Perbaikan

1. **TC03, TC17 — NIM duplikat dibalas 409, seharusnya 422.** `MahasiswaController::store` dan `::update` menangkap `DomainException` dengan kode 409. Diperbaiki menjadi 422 (Unprocessable Entity). Dokumentasi `API_DOCUMENTATION.md` dan `openapi.yaml` disesuaikan.
2. **TC07, TC18 — NIM berisi huruf (`ABCDEFGHI`) lolos validasi.** `validateFormat()` hanya memeriksa `strlen == 9`. Diperbaiki dengan menambah `ctype_digit()`. TC18 gagal karena efek berantai: nilai dari TC07 sudah tersimpan sehingga dibalas 409 (duplikat).

Seluruh test case dijalankan ulang (retest) dan semuanya Pass tanpa regresi.

## Cara menjalankan ulang di Postman

1. Jalankan `database/reset_pengujian.sql` di phpMyAdmin (database `db_workshop`) agar data uji bersih.
2. Import `postman/Acara23_TestCase.postman_collection.json` dan environment `Local` (atau `Local (php -S 8080)`), lalu pilih environment tersebut.
3. Jalankan folder **00 Persiapan**, lalu folder **01** sampai **04** (atau seluruh collection lewat Runner, dari atas ke bawah). Setiap request punya tab *Tests* yang menandai Pass/Fail otomatis.
4. Screenshot request/response (TC01–TC06 wajib) dan hasil Runner untuk laporan.
