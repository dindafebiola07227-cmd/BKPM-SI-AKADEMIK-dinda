# Panduan Acara 26 — Manajemen Konten, Upload File, dan Database Management

## Perubahan dibanding Acara 25
| Berkas | Perubahan |
|---|---|
| `database/acara26_foto.sql` (dan `db_workshop.sql`) | Kolom baru `foto VARCHAR(255) DEFAULT NULL` setelah `prodi` |
| `public/uploads/` (+ `.htaccess`, `.gitkeep`) | Folder penyimpanan foto; eksekusi skrip dilarang di folder ini |
| `app/models/Mahasiswa.php` | `create()` menyimpan `foto`; `update()` mengubah `foto` hanya bila ada foto baru |
| `app/controllers/MahasiswaController.php` | `handleUpload()` (validasi + `move_uploaded_file`), `readInput()` (JSON atau multipart), `hapusBerkasFoto()` (`unlink`) |
| `app/core/Router.php` | `POST /mahasiswa?id=N&_method=PUT` diperlakukan sebagai PUT |
| `public/views/dashboard.html`, `dashboard.js`, `style.css` | Input foto, kolom foto, pengiriman memakai `FormData` |
| `database/backup_db.bat` | Skrip backup `db_workshop` dengan `mysqldump` (Tugas Mandiri 2) |

## Cara menjalankan
1. Letakkan proyek di `C:\xampp\htdocs\BKPM\ACARA26\si-akademik_bkpmpt26`, nyalakan Apache dan MySQL.
2. Basis data lama (Acara 25): jalankan `database/acara26_foto.sql` di phpMyAdmin. Instalasi baru: impor `db_workshop.sql`.
3. Pastikan ekstensi `exif` aktif di `php.ini` (`extension=exif`), lalu restart Apache.
4. Buka `.../public/views/login.html`, login sebagai admin (`admin_uji`), tambah mahasiswa dengan foto.

## Catatan teknis
- **Mengapa `_method=PUT`?** PHP hanya mengisi `$_POST` dan `$_FILES` untuk request POST. Pada PUT bertipe multipart, keduanya kosong, sehingga foto tidak terbaca. Maka pengubahan data dikirim sebagai POST dengan `_method=PUT`.
- **Mengapa tidak ada `Content-Type` pada fetch FormData?** Browser harus membuat sendiri header `multipart/form-data; boundary=...`.
- **Pemeriksaan upload kosong:** input file yang tidak diisi tetap muncul di `$_FILES` (error `UPLOAD_ERR_NO_FILE`), sehingga `!empty($_FILES['foto'])` saja tidak cukup; kode memeriksa kode error-nya.
- **Berkas yatim:** bila INSERT/UPDATE gagal (mis. NIM duplikat), foto yang sudah dipindahkan dihapus kembali. Foto lama hanya dihapus setelah UPDATE berhasil.
- **Backup:** `database/backup_db.bat` menghasilkan `backup\db_workshop_<tanggal>.sql`. Restore: buat basis data baru → Import berkas tersebut.
- JSON tanpa foto (Postman Acara 23/25) tetap diterima karena foto bersifat opsional.
