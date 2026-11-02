# Panduan Pengujian Acara 20 (API Authentication dan Error Handling)

## A. Persiapan
1. Folder berada di `C:\xampp\htdocs\BKPM\ACARA20\si-akademik_bkpmpt20\`.
2. XAMPP: nyalakan **Apache** dan **MySQL**.
3. Database `db_workshop` dari Acara 19 dipakai apa adanya (tabel `users` sudah ada). Tidak perlu impor ulang.
4. Cek di browser: `.../public/mahasiswa` harus tampil JSON `"success": true` (GET masih publik).
5. Cek di browser: `.../public/login` (GET) harus tampil `405 Method tidak diizinkan`.

## B. Import ke Postman
Import 3 file dari folder `docs/`: collection, `Environment_Local`, `Environment_Staging`. Pilih environment **Local**.

## C. Pengujian manual sesuai Langkah 6
1. Register akun (atau pakai akun dari Acara 19): `POST {{base_url}}/register`.
2. **Login:** `POST {{base_url}}/login` dengan body `{"username":"...","password":"..."}`. Salin nilai `data.token`.
3. **Uji endpoint terlindungi:** pada request `POST {{base_url}}/mahasiswa`, buka tab **Authorization** -> Type **Bearer Token** -> tempel token (atau tab Headers: `Authorization` = `Bearer <token>`). Hasil benar: 201. Ulangi untuk PUT dan DELETE.
4. **Uji kondisi gagal:**
   - Kirim POST tanpa token -> **401** "Token tidak ditemukan".
   - Ubah satu karakter pada token lalu kirim -> **401** "Token tidak valid atau kedaluwarsa".

## D. Pengujian otomatis (Runner)
Collection -> **Run** -> jalankan dari atas ke bawah. Urutan folder penting:

| Folder | Isi | Fungsi |
|---|---|---|
| Auth (Acara 19) | 8 request | membuat akun uji `{{unameBaru}}` |
| Login (Acara 20) | 7 request | request no. 1 menyimpan token ke `{{token}}` |
| Proteksi Token (Acara 20) | 7 request | tanpa token, token diubah, token acak, skema Basic -> 401; GET tetap 200 |
| Mahasiswa | 16 request | POST/PUT/DELETE kini membawa `Bearer {{token}}` |

Jangan menjalankan folder Login sendirian pada sesi baru: akun `{{unameBaru}}` dibuat oleh request no. 1 folder Auth.

| No | Skenario login | Status benar |
|---|---|---|
| 1 | Login sukses | 200 |
| 2 | Password salah | 401 |
| 3 | Username tidak terdaftar | 401 |
| 4 | Body `{}` | 400 |
| 5 | Hanya username | 400 |
| 6 | Body bukan JSON | 400 |
| 7 | GET /login | 405 |

Jumlah assertion pada Runner tidak saya klaim di sini karena belum dijalankan; catat angka dari Runner milikmu untuk laporan.

## E. Tugas Praktik Mandiri
1. **Diskusi JWT stateless dan logout:** jawaban lengkap ada di `docs/TUGAS_MANDIRI.md`.
2. **Token 5 menit** (`Auth::TOKEN_TTL = 300`). Cara mengamati token kedaluwarsa:
   1. Login, catat jam saat token diterima.
   2. Kirim `POST /mahasiswa` dengan token itu sebelum 5 menit -> 201.
   3. Tunggu lebih dari 5 menit tanpa login ulang, kirim request yang sama -> **401** "Token tidak valid atau kedaluwarsa".
   4. Login lagi, pakai token baru -> 201.
   Tips agar tidak menunggu lama: sementara ubah `TOKEN_TTL` menjadi `20`, uji, lalu kembalikan ke `300`.

## F. Bukti di database
phpMyAdmin -> `db_workshop` -> tabel `users` -> Browse: kolom `password` berawalan `$2y$10$`. Token **tidak** disimpan di database (stateless).

## G. Git (log aktivitas)
```
git init
git add .
git commit -m "Acara 20: class Auth (JWT native), login, proteksi token, try-catch model"
git log --oneline
```

## H. Screenshot untuk laporan
Struktur folder (ada `Auth.php`), request login + response 200 (token), POST tanpa token (401), POST token diubah (401), POST dengan token (201), percobaan token kedaluwarsa (Tugas Mandiri 2), hasil Run Local dan Staging, `git log --oneline`, dan `docs/diagram_alur_login.png`.
