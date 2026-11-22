-- ============================================================
-- Acara 25 - menjadikan akun admin_uji sebagai admin
-- Langkah: 1) daftarkan akun lewat POST /register (Postman, folder RBAC request 1)
--          2) jalankan file ini di phpMyAdmin: pilih db_workshop -> tab SQL -> Go
-- Untuk akun lain, ganti 'admin_uji' dengan username yang diinginkan.
-- ============================================================
USE db_workshop;

UPDATE users SET role = 'admin' WHERE username = 'admin_uji';

-- Periksa hasilnya
SELECT id, username, role FROM users;
