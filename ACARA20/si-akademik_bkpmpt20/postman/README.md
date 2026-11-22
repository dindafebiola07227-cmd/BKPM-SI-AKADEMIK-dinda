# Postman - Acara 20

1. Import semua file `.json` di folder ini: 1 collection dan 3 environment (Local, Local (php -S 8080), Staging).
2. Pilih environment:
   - **Local**: Apache/XAMPP, `http://localhost/BKPM/ACARA20/si-akademik_bkpmpt20`
   - **Local (php -S 8080)**: setelah menjalankan `jalankan.bat`, `http://localhost:8080`
   - **Staging**: `http://127.0.0.1/BKPM/ACARA20/si-akademik_bkpmpt20`
3. Tidak ada tabel baru: pakai database `db_workshop` dan tabel `users` dari Acara 19.
4. Jalankan collection lewat **Runner**, dari atas ke bawah:
   - **Auth**: membuat akun uji (`{{unameBaru}}` / `RahasiaUji123`).
   - **Login**: request no. 1 menyimpan token ke `{{token}}` (collection variable) secara otomatis.
   - **Proteksi Token**: tanpa token, token diubah, token acak, skema bukan Bearer, token kedaluwarsa -> semua 401; GET tetap publik (200).
   - **Mahasiswa**: POST/PUT/DELETE otomatis membawa header `Authorization: Bearer {{token}}`.
5. Token berlaku 5 menit (Tugas Mandiri 2). Untuk pengamatan nyata: login, tunggu lebih dari 5 menit, lalu kirim ulang POST/PUT/DELETE -> `401 Token tidak valid atau kedaluwarsa`.
6. Runner aman dijalankan berulang kali: nama akun dan NIM uji dibuat unik oleh collection, tidak perlu mengosongkan tabel.

Hasil yang benar: 39 request, 228 assertion, 0 gagal.
