-- ============================================================
-- Ganti data contoh mahasiswa TANPA menghapus tabel
-- Jalankan di phpMyAdmin: pilih database si_akademik -> tab SQL -> Go
-- (aman dijalankan berulang kali)
-- ============================================================
USE si_akademik;

UPDATE mahasiswa SET nama = 'Dinda Febiola R',     email = 'dinda.febiola@gmail.com'     WHERE nim = '2401001';
UPDATE mahasiswa SET nama = 'Linda Ratnadela A', email = 'linda.ratnadela@gmail.com' WHERE nim = '2401002';
UPDATE mahasiswa SET nama = 'Sheryn Febrylia H',    email = 'sheryn.febrylia@gmail.com'    WHERE nim = '2402001';

-- Cek hasilnya (ini yang akan tampil di GET /api/mahasiswa):
SELECT id, nim, nama, email FROM mahasiswa ORDER BY nim;

-- Cek kamu sedang berada di server/database yang benar:
SELECT DATABASE() AS database_aktif, @@port AS port_mysql, @@datadir AS lokasi_data;
