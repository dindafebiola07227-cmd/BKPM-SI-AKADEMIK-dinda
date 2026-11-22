-- ============================================================
-- Acara 19 - tabel users untuk autentikasi (registrasi)
-- Jalankan di phpMyAdmin: pilih database db_workshop -> tab SQL -> Go
-- Aman dijalankan berulang kali (tidak menimpa tabel yang sudah ada).
-- ============================================================
USE db_workshop;

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
