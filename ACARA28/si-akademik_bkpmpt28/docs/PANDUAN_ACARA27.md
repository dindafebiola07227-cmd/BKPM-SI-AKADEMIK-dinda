# Panduan Acara 27 — Integrasi Backend-Frontend (CRUD Penuh dari View)

## Perubahan dibanding Acara 26
| Berkas | Perubahan |
|---|---|
| `public/views/form_tambah.html`, `form_tambah.js` | Form Create dengan `FormData` + header `Authorization` (Langkah 1) |
| `public/views/form_edit.html`, `form_edit.js` | Form Update: memuat data lewat `GET /mahasiswa?id=`, mengirim perubahan (Langkah 2) |
| `public/views/dashboard.html`, `dashboard.js` | Tambah/Ubah membuka form terpisah, tombol Hapus + konfirmasi (Langkah 3) |
| `public/views/api.js` | Fungsi bersama: token, `panggilApi`, notifikasi, validasi sisi View |
| `public/views/style.css` | Gaya notifikasi dan pesan validasi |
| Backend (`app/`) | Tidak ada perubahan; backend Acara 26 sudah mendukung seluruh alur |

## Catatan penting
- **PUT dengan FormData:** modul menulis `method: 'PUT'` pada form_edit, tetapi PHP tidak mengisi `$_POST`/`$_FILES` untuk PUT bermultipart. Karena itu edit dikirim sebagai `POST` + `_method=PUT` (sudah ditangani Router sejak Acara 26).
- **Tugas mandiri 1:** notifikasi (toast hijau = berhasil, merah = gagal) untuk tambah, ubah, hapus. Setelah tambah/ubah, notifikasi tampil di dashboard.
- **Tugas mandiri 2:** validasi di View (field wajib diisi, NIM 9 digit angka, foto JPG/PNG maks. 2 MB) sebelum data dikirim; pesan tampil di bawah setiap field. Backend tetap memvalidasi ulang.

## Menjalankan
1. Letakkan di `C:\xampp\htdocs\BKPM\ACARA27\si-akademik_bkpmpt27`; Apache dan MySQL aktif.
2. Proyek ini menggunakan database Acara 26 `db_workshopku` yang sudah memiliki tabel `mahasiswa`, `prodi`, `users`, dan kolom `foto`. Jangan impor `db_workshop.sql`, karena skema itu untuk proyek lama dan tidak cocok dengan database ini.
3. Saat menambah atau mengubah mahasiswa, isi Program Studi dengan nama, kode, atau ID yang sudah ada pada tabel `prodi` (contoh: `Teknik Informatika`, `TI`, atau `1`).
4. Login admin: `http://localhost/BKPM/ACARA27/si-akademik_bkpmpt27/public/views/login.html`.

## Pengujian alur CRUD (Langkah 4)
1. Tambah mahasiswa lengkap dengan foto lewat `form_tambah.html`; kembali ke dashboard dengan notifikasi hijau.
2. Ubah salah satu data lewat `form_edit.html`; perubahan tampil di dashboard.
3. Hapus lewat tombol Hapus; baris hilang dari tabel dan dari database (cek phpMyAdmin); foto ikut terhapus dari `public/uploads/`.
4. Uji validasi: kosongkan field, isi NIM bukan 9 digit, pilih berkas > 2 MB; uji galat server: NIM kembar (409), login sebagai user lalu buka `form_tambah.html` langsung (403).
