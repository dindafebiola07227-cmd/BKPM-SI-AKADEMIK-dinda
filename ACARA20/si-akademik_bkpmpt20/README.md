# Acara 20 - API Authentication dan Error Handling (si-akademik_bkpmpt20)

Lanjutan Acara 19. **Database tetap `db_workshop` dari Acara 19** (tabel `mahasiswa`, `buku`, `users`).
Tidak ada tabel baru dan tidak ada library tambahan (tanpa Composer).

## Menjalankan
1. Letakkan folder ini di `C:\xampp\htdocs\BKPM\ACARA20\`.
2. Nyalakan Apache dan MySQL di XAMPP.
3. Database sudah ada dari Acara 19. Bila belum, impor `db_workshop.sql` (aman diulang, tabel yang ada tidak ditimpa).
4. Pastikan sudah ada minimal satu akun hasil registrasi (Acara 19), atau jalankan request register di Postman.
5. Postman: import tiga file di folder `docs/` (collection + environment Local + Staging), pilih environment **Local**, lalu Run collection.

## Yang baru dibanding Acara 19 (sesuai Prosedur Kerja)
```
Langkah 1  app/core/Auth.php                       BARU  generateToken(), validateToken(), requireAuth()
Langkah 2  app/controllers/AuthController.php      + aksi login()
Langkah 3  app/core/Router.php                     + resource 'login' (hanya POST)
Langkah 4  app/controllers/MahasiswaController.php + Auth::requireAuth() di store(), update(), destroy()  (index/GET tetap publik)
Langkah 5  app/models/Mahasiswa.php                + try...catch (PDOException) pada create(), update(), delete()
Langkah 6  docs/                                   collection Postman diperbarui (38 request), PANDUAN_PENGUJIAN.md
Mandiri 1  docs/TUGAS_MANDIRI.md                   diskusi JWT stateless dan logout
Mandiri 2  Auth::TOKEN_TTL = 300                   masa berlaku token 5 menit
Tambahan   public/.htaccess                        meneruskan header Authorization ke PHP
```

## Catatan penyesuaian terhadap modul
- `base64urlDecode()` ditambahkan agar payload yang mengandung karakter `-` dan `_` terbaca benar (modul memakai `base64_decode` biasa).
- `validateToken()` memeriksa payload valid dan ada `exp` sebelum dipakai, supaya token rusak tidak memicu warning PHP.
- `login()` mengikuti gaya `register()` pada project ini: body harus JSON (400), field wajib diisi (400), lalu 401 bila salah.
- `Response::json()` milik project ini dipakai apa adanya, jadi semua response tetap berformat `success, message, data, [errors], timestamp`.
- Try-catch di Model: NIM kembar yang lolos pemeriksaan (dua request bersamaan) diubah menjadi 409, kesalahan lain menjadi 500 tanpa membocorkan detail database.

## Git (untuk log aktivitas di laporan)
```
git add .
git commit -m "Acara 20: class Auth (JWT native), login, proteksi token, try-catch model"
git log --oneline
```
