# Panduan Pengujian Acara 19 (Postman dan API Authentication)

## A. Persiapan
1. Folder berada di `C:\xampp\htdocs\BKPM\ACARA19\si-akademik_bkpmpt19\`.
2. XAMPP: nyalakan **Apache** dan **MySQL** (hijau).
3. phpMyAdmin -> database `db_workshop` -> tab **SQL** -> tempel isi `database/users.sql` -> **Go**.
   (Database `db_workshop` dari Acara 17-18 tidak perlu dihapus.)
4. Cek di browser: `http://localhost/BKPM/ACARA19/si-akademik_bkpmpt19/public/mahasiswa`
   -> harus tampil JSON `"success": true`.
5. Cek: `.../public/register` dibuka di browser (GET) -> harus tampil `405 Method tidak diizinkan`.

## B. Import ke Postman
Import 3 file dari folder `docs/`: collection, `Environment_Local`, `Environment_Staging`.
Pilih environment **Local** di kanan atas. Nilai `base_url` harus sama dengan alamat folder di langkah A.4.

## C. Pengujian register (Langkah 6)
Buka folder **Auth (Acara 19)** dan kirim berurutan 1 s.d. 8.

| No | Skenario | Status yang benar |
|---|---|---|
| 1 | Register sukses | 201 |
| 2 | Username sudah ada | 409 |
| 3 | Email sudah ada | 409 |
| 4 | Body `{}` | 400 |
| 5 | Hanya username | 400 |
| 6 | Format email tidak valid | 422 |
| 7 | Body bukan JSON valid | 400 |
| 8 | GET /register | 405 |

Cara cepat: klik collection -> **Run** -> **Run API Sistem Informasi Akademik (MVC)**.
Hasil benar: semua hijau (24 request, 133 assertions, 0 failed).

## D. Contoh response tersimpan (Langkah 6.2)
Klik panah di samping nama request -> contoh response asli sudah tersimpan.
Untuk laporan, tambahkan contoh milikmu: Send -> **Save Response** -> **Save as example**.

## E. Tugas Mandiri
1. Validasi email: request no. 6 harus 422 "Format email tidak valid" (kode: `app/models/User.php`, method `validate`).
2. Environment kedua: ganti ke **Staging**, jalankan Run lagi. Hasil harus sama.

## F. Bukti di database
phpMyAdmin -> `db_workshop` -> tabel `users` -> **Browse**:
- kolom `password` diawali `$2y$10$` (hash, bukan teks asli),
- kolom `role` = `user`.

## G. Git (log aktivitas)
```
git init
git add .
git commit -m "Acara 19: tabel users, Model User, AuthController register, environment Postman"
git log --oneline
```

## H. Screenshot untuk laporan
Struktur folder, tabel users (Structure + Browse), request/response 201 dan minimal 3 gagal (409, 400, 422),
hasil Run Local dan Staging, `git log --oneline`, serta `docs/diagram_alur_register.png` dan `docs/diagram_kelas.png`.
