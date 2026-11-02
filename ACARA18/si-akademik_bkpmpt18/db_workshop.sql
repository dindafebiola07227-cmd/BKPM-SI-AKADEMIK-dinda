-- ============================================================
-- Acara 17 - Skeleton MVC PHP Native
-- Database: db_workshop
-- Aman diimpor ulang: tabel yang sudah ada tidak dihapus/ditimpa
-- ============================================================
CREATE DATABASE IF NOT EXISTS db_workshop
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_general_ci;
USE db_workshop;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nim        VARCHAR(15)  NOT NULL,
    nama       VARCHAR(100) NOT NULL,
    prodi      VARCHAR(50)  NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_mahasiswa_nim (nim)   -- Tugas mandiri 2: NIM tidak boleh duplikat (pengaman di level database)
);

-- Tugas mandiri 1: resource baru "buku"
CREATE TABLE IF NOT EXISTS buku (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    judul        VARCHAR(150) NOT NULL,
    penulis      VARCHAR(100) NOT NULL,
    tahun_terbit SMALLINT UNSIGNED NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
