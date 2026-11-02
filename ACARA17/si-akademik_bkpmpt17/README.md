# Acara 17 - Skeleton MVC PHP Native (si-akademik_bkpmpt17)

## Menjalankan
1. Letakkan folder ini di `C:\xampp\htdocs\BKPM\ACARA17\`.
2. Nyalakan Apache dan MySQL di XAMPP (mod_rewrite harus aktif, bawaan XAMPP sudah aktif).
3. phpMyAdmin -> tab Import -> pilih `db_workshop.sql` -> Import.
4. Buka `http://localhost/BKPM/ACARA17/si-akademik_bkpmpt17/public/mahasiswa`
   (hasilnya `{"success":true,"data":[]}` karena tabel masih kosong).
5. Postman: Import `docs/Acara17_MVC_Mahasiswa_Buku.postman_collection.json`, ubah variabel `baseUrl`
   di tab Variables collection, lalu Run collection.

## Struktur
```
app/config/Database.php        koneksi PDO (singleton)
app/core/autoload.php          autoloader class
app/core/Router.php            peta URL -> Controller
app/core/BaseController.php    kelas induk Controller (abstract)
app/models/                    Mahasiswa.php, Buku.php
app/controllers/               MahasiswaController.php, BukuController.php
public/index.php + .htaccess   Front Controller
views/                         kosong (diisi mulai acara 24)
db_workshop.sql                skema database
docs/                          dokumentasi, diagram, collection Postman
```

## Git (untuk log aktivitas di laporan)
```
git init
git add .
git commit -m "Acara 17: skeleton MVC, resource mahasiswa dan buku"
git log --oneline
```
