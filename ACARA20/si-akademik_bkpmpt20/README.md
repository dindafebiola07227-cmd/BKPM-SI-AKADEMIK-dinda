# Acara 20 - API Authentication dan Error Handling (si-akademik_bkpmpt20)

Lanjutan Acara 19. **Database tetap `db_workshop` dari Acara 19** (tabel `mahasiswa`, `buku`, `users`).
Tidak ada tabel baru dan tidak ada library tambahan (tanpa Composer).

## Menjalankan (pilih salah satu)

**A. XAMPP (Apache)**
1. Letakkan folder ini di `C:\xampp\htdocs\BKPM\ACARA20\si-akademik_bkpmpt20\`.
2. Nyalakan Apache dan MySQL.
3. Database sudah ada dari Acara 19. Bila belum, impor `db_workshop.sql` (aman diulang, tabel yang ada tidak ditimpa).
4. Cek di browser: `http://localhost/BKPM/ACARA20/si-akademik_bkpmpt20/` harus menampilkan JSON "API ... berjalan".
5. Di Postman pilih environment **Local**.

**B. Tanpa Apache (server bawaan PHP)**
1. Nyalakan MySQL saja, lalu klik dua kali `jalankan.bat` (server di `http://localhost:8080`).
2. Di Postman pilih environment **Local (php -S 8080)**.

Lalu import tiga file dari folder `postman/` dan jalankan collection lewat Runner (lihat `postman/README.md`).
Pastikan ada akun uji: folder **Auth** pada collection membuatnya otomatis.

## Struktur proyek
```
.htaccess                 BARU  arahkan semua request ke public/ + teruskan header Authorization
jalankan.bat              BARU  jalankan server php -S localhost:8080 (tanpa Apache)
TUGAS_MANDIRI.md          BARU  jawaban Tugas Praktik Mandiri 1 dan 2
postman/                  collection (39 request) + environment Local, Local (php -S 8080), Staging
docs/                     DOKUMENTASI_ENDPOINT.md, PANDUAN_PENGUJIAN.md, diagram (kelas, register, login)
database/users.sql        tabel users (Acara 19)
db_workshop.sql           seluruh skema (mahasiswa, buku, users)
app/core/Auth.php         BARU  generateToken(), validateToken(), requireAuth()
public/                   index.php + .htaccess (front controller)
```

## Yang baru dibanding Acara 19 (sesuai Prosedur Kerja)
```
Langkah 1  app/core/Auth.php                       BARU  JWT HS256 native (tanpa Composer)
Langkah 2  app/controllers/AuthController.php      + aksi login()
Langkah 3  app/core/Router.php                     + resource 'login' (hanya POST), + halaman cek koneksi
Langkah 4  app/controllers/MahasiswaController.php + Auth::requireAuth() di store(), update(), destroy()  (index/GET tetap publik)
Langkah 5  app/models/Mahasiswa.php                + try...catch (PDOException) pada create(), update(), delete()
Langkah 6  postman/ dan docs/PANDUAN_PENGUJIAN.md  collection diperbarui (39 request), uji alur autentikasi
Mandiri 1  TUGAS_MANDIRI.md                        diskusi JWT stateless dan logout
Mandiri 2  Auth::TOKEN_TTL = 300                   masa berlaku token 5 menit
Tambahan   .htaccess, public/.htaccess             meneruskan header Authorization ke PHP
Tambahan   jalankan.bat                            alternatif tanpa Apache
```

## Catatan penyesuaian terhadap modul
- `base64urlDecode()` ditambahkan agar payload yang mengandung karakter `-` dan `_` terbaca benar (modul memakai `base64_decode` biasa).
- `validateToken()` memeriksa payload valid dan ada `exp` sebelum dipakai, supaya token rusak tidak memicu warning PHP.
- `login()` mengikuti gaya `register()` pada project ini: body harus JSON (400), field wajib diisi (400), lalu 401 bila salah.
- `Response::json()` milik project ini dipakai apa adanya, jadi semua response tetap berformat `success, message, data, [errors], timestamp`.
- Try-catch di Model: NIM kembar yang lolos pemeriksaan (dua request bersamaan) diubah menjadi 409, kesalahan lain menjadi 500 tanpa membocorkan detail database.
- Uji token kedaluwarsa di collection memakai token yang ditandatangani benar tetapi `exp`-nya sudah lewat, jadi hasilnya pasti 401 tanpa menunggu 5 menit. Pengamatan menunggu 5 menit sungguhan tetap dilakukan untuk Tugas Mandiri 2.

## Hasil verifikasi
Collection dijalankan dengan Newman (Apache Local, Staging, dan `php -S`): **39 request, 228 assertion, 0 gagal**.
```
npm i -g newman
newman run postman/API_Sistem_Informasi_Akademik_MVC.postman_collection.json -e postman/Local.postman_environment.json
```
Angka untuk laporan tetap ambil dari Runner Postman milikmu sendiri.

## Git (untuk log aktivitas di laporan)
```
git add .
git commit -m "Acara 20: class Auth (JWT native), login, proteksi token, try-catch model"
git log --oneline
```
