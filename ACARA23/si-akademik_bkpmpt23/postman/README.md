# Postman - Acara 21 (Dokumentasi API)

1. Import semua file `.json` di folder ini: 1 collection dan 3 environment (Local, Local (php -S 8080), Staging).
2. Pilih environment:
   - **Local**: Apache/XAMPP, `http://localhost/BKPM/ACARA21/si-akademik_bkpmpt21/public`
   - **Local (php -S 8080)**: setelah menjalankan `jalankan.bat`, `http://localhost:8080`
   - **Staging**: `http://127.0.0.1/BKPM/ACARA21/si-akademik_bkpmpt21/public`
3. Tidak ada tabel baru: pakai database `db_workshop`.
4. Setiap request sudah berisi **Description** (nama Controller dan method). Simpan contoh response sukses dan gagal pada tab **Examples**, lalu klik **View Documentation** pada Collection.
5. Isi folder collection: **Auth** (register), **Login** (token tersimpan otomatis ke `{{token}}`), **Proteksi Token** (uji 401), dan **Mahasiswa** (POST/PUT/DELETE membawa `Authorization: Bearer {{token}}`).
6. Token berlaku 5 menit. Bila muncul 401 "Token tidak valid atau kedaluwarsa", jalankan ulang request login.
