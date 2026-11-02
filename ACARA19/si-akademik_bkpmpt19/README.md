# Acara 19 - Postman dan API Authentication (si-akademik_bkpmpt19)

Lanjutan Acara 18. Database tetap `db_workshop`; hanya ditambah tabel `users`.

## Menjalankan
1. Letakkan folder ini di `C:\xampp\htdocs\BKPM\ACARA19\`.
2. Nyalakan Apache dan MySQL.
3. phpMyAdmin -> database `db_workshop` -> tab SQL -> jalankan isi `database/users.sql`.
4. Postman: Import tiga file di folder `docs/`:
   `API_Sistem_Informasi_Akademik_MVC.postman_collection.json`, `Environment_Local...json`, `Environment_Staging...json`.
5. Pilih environment **Local** (kanan atas Postman), lalu Run collection.
   Ulangi dengan environment **Staging** untuk Tugas Mandiri 2.

## Yang baru dibanding Acara 18
```
database/users.sql                  tabel users
app/models/User.php                 Model: create(), findByUsername(), findByEmail(), validasi email (filter_var)
app/controllers/AuthController.php  aksi tunggal register() + password_hash()
app/core/Router.php                 resource 'register' -> AuthController (hanya POST)
app/config/app.php                  + zona waktu Asia/Jakarta
```

## Contoh response tersimpan (Langkah 6.2)
Setiap request di collection sudah memiliki satu *saved example* (response asli hasil pengujian: sukses dan gagal).
Di Postman: buka request -> panah di sebelah namanya -> klik nama contoh untuk melihat. Kamu juga bisa membuat
contoh milikmu sendiri dengan Send -> Save Response -> Save as example.

## Pengujian otomatis (opsional, tanpa membuka Postman)
```
npm i -g newman
newman run docs/API_Sistem_Informasi_Akademik_MVC.postman_collection.json -e docs/Environment_Local.postman_environment.json
```
Hasil yang benar: 24 request, 133 assertions, 0 failed. Panduan lengkap: `docs/PANDUAN_PENGUJIAN.md`.

## Git (untuk log aktivitas di laporan)
```
git add .
git commit -m "Acara 19: tabel users, Model User, AuthController register, environment Postman"
git log --oneline
```
