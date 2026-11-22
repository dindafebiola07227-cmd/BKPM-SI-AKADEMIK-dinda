# BKPM Acara 25 — Authentication, Authorization dan Dashboard Admin

Proyek ini melanjutkan Acara 24 (view pertama dan CORS). Kode Router, Controller, Model, JWT, dan database dipertahankan.

## Yang baru di Acara 25
- `Auth::requireAdmin()` (RBAC) dan penerapannya pada `store()`, `update()`, `destroy()` di `MahasiswaController`
- Halaman `public/views/login.html` dan `dashboard.html` (Logout, total mahasiswa, Tambah/Ubah/Hapus)
- Folder "RBAC (Acara 25)" pada collection Postman, dan `database/jadikan_admin.sql`

Panduan lengkap: `docs/PANDUAN_ACARA25.md`.

## Menjalankan
1. Letakkan di `C:\xampp\htdocs\BKPM\ACARA25\si-akademik_bkpmpt25`, jalankan Apache dan MySQL.
2. Database `db_workshop` (impor `db_workshop.sql` dan `database/users.sql` bila belum ada).
3. Buka `http://localhost/BKPM/ACARA25/si-akademik_bkpmpt25/public/views/login.html`.
