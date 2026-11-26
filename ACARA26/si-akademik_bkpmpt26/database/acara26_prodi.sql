-- Acara 26: tambahkan program studi Manajemen Agribisnis bila belum tersedia.
USE db_workshopku;

INSERT INTO prodi (kode, nama)
SELECT 'MA', 'Manajemen Agribisnis'
WHERE NOT EXISTS (
    SELECT 1
    FROM prodi
    WHERE kode = 'MA' OR nama = 'Manajemen Agribisnis'
);
