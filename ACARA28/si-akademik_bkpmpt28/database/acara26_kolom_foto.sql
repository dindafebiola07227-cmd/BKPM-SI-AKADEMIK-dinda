-- ============================================================
-- Acara 26 - Langkah 1: menambahkan kolom foto pada tabel mahasiswa
-- Jalankan di phpMyAdmin: pilih db_workshopku -> tab SQL -> Go
-- (XAMPP memakai MariaDB sehingga IF NOT EXISTS didukung; aman dijalankan berulang)
-- ============================================================
USE db_workshopku;

ALTER TABLE mahasiswa ADD COLUMN IF NOT EXISTS foto VARCHAR(255) DEFAULT NULL AFTER prodi_id;

-- Periksa hasilnya
DESCRIBE mahasiswa;
