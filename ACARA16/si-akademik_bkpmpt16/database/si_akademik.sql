-- Database SI Akademik (Acara 16 - RESTful API Mahasiswa)
CREATE DATABASE IF NOT EXISTS si_akademik CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE si_akademik;

DROP TABLE IF EXISTS matakuliah;
DROP TABLE IF EXISTS mahasiswa;
DROP TABLE IF EXISTS prodi;

CREATE TABLE prodi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    prodi_id INT NOT NULL,
    angkatan YEAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_mahasiswa_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE matakuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(150) NOT NULL,
    sks TINYINT NOT NULL,
    prodi_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_matakuliah_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

INSERT INTO prodi (kode, nama) VALUES
('TI', 'Teknik Informatika'),
('SI', 'Sistem Informasi'),
('TK', 'Teknik Komputer');

INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan) VALUES
('2401001', 'Dinda Febiola R', 'dinda.febiola@gmail.com', 1, 2024),
('2401002', 'Linda Ratnadela A', 'linda.ratnadela@gmail.com', 1, 2024),
('2402001', 'Sheryn Febrylia H', 'sheryn.febrylia@gmail.com', 2, 2024);

INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES
('TI101', 'Pemrograman Dasar', 3, 1),
('TI102', 'Basis Data', 3, 1),
('SI101', 'Pengantar SI', 2, 2);
