-- ============================================================
-- Acara 26 - Langkah 1: menambahkan kolom foto pada tabel mahasiswa
-- Jalankan sekali di phpMyAdmin: pilih db_workshop -> tab SQL -> Go
-- (Jika dijalankan dua kali akan muncul error "Duplicate column name" - itu wajar.)
-- ============================================================
USE db_workshop;

ALTER TABLE mahasiswa ADD COLUMN foto VARCHAR(255) DEFAULT NULL AFTER prodi;
