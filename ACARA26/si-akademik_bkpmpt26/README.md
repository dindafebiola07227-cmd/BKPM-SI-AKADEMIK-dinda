# BKPM Acara 26 — Manajemen Konten, Upload File dan Database Management

Proyek ini melanjutkan Acara 25 (authentication, RBAC, dashboard admin). Kode Router, Controller, Model, JWT, dan database dipertahankan.

## Yang baru di Acara 26
- Kolom `foto`, folder `public/uploads/`, upload + validasi (`exif_imagetype`, 2 MB, nama unik), `unlink()` foto lama
- Dashboard memakai `FormData`; backup/restore lewat phpMyAdmin dan `database/backup_db.bat`

Panduan: `docs/PANDUAN_ACARA26.md`.

## Fitur Acara 25 (tetap berlaku)
- `Auth::requireAdmin()` (RBAC) dan penerapannya pada `store()`, `update()`, `destroy()` di `MahasiswaController`
- Halaman `public/views/login.html` dan `dashboard.html` (Logout, total mahasiswa, Tambah/Ubah/Hapus)
- Folder "RBAC (Acara 25)" pada collection Postman, dan `database/jadikan_admin.sql`

Panduan lengkap: `docs/PANDUAN_ACARA25.md`.

## Menjalankan
1. Letakkan di `C:\xampp\htdocs\BKPM\ACARA26\si-akademik_bkpmpt26`, jalankan Apache dan MySQL.
2. Database `db_workshop` (impor `db_workshop.sql` dan `database/users.sql` bila belum ada).
3. Buka `http://localhost/BKPM/ACARA26/si-akademik_bkpmpt26/public/views/login.html`.
