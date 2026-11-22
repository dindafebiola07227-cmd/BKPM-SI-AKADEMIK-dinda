-- Acara 23: bersihkan data uji sebelum menjalankan ulang collection di Postman
USE db_workshop;
DELETE FROM mahasiswa WHERE nim IN ('220100123','220100200','220100201','220100202','220100210','ABCDEFGHI','22010012','220100999');
DELETE FROM users WHERE username IN ('penguji','user_baru');
