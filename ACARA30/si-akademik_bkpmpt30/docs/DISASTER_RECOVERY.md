# Simulasi Disaster Recovery (Acara 30 — Tugas Praktik Mandiri 3)

Tujuan: membuktikan bahwa berkas backup Acara 26 benar-benar dapat memulihkan data setelah sebuah tabel hilang.

**Keamanan:** simulasi hanya menyentuh basis data percobaan `db_workshopku_dr` (salinan dari `db_workshopku`). Basis data asli tidak diubah.

## Cara otomatis
Pastikan MySQL XAMPP aktif, lalu jalankan `database\simulasi_dr.bat`. Skrip akan:
1. membuat `db_workshopku_dr` dari salinan `db_workshopku`;
2. membuat backup (`database\backup\dr_sebelum_bencana.sql`);
3. menampilkan jumlah baris tiap tabel (sebelum);
4. **menghapus tabel `mahasiswa`** (bencana) lalu menunjukkan galat `ERROR 1146 ... doesn't exist`;
5. memulihkan dari berkas backup;
6. menampilkan jumlah baris tiap tabel (sesudah) — harus sama dengan langkah 3.

## Cara manual (untuk tangkapan layar)
```
C:\xampp\mysql\bin\mysql.exe -u root -e "DROP DATABASE IF EXISTS db_workshopku_dr; CREATE DATABASE db_workshopku_dr CHARACTER SET utf8mb4;"
C:\xampp\mysql\bin\mysqldump.exe -u root db_workshopku > salin.sql
C:\xampp\mysql\bin\mysql.exe -u root db_workshopku_dr < salin.sql
C:\xampp\mysql\bin\mysqldump.exe -u root db_workshopku_dr > backup_dr.sql
C:\xampp\mysql\bin\mysql.exe -u root db_workshopku_dr -e "SELECT COUNT(*) FROM mahasiswa;"
C:\xampp\mysql\bin\mysql.exe -u root db_workshopku_dr -e "DROP TABLE mahasiswa;"
C:\xampp\mysql\bin\mysql.exe -u root db_workshopku_dr -e "SELECT COUNT(*) FROM mahasiswa;"
C:\xampp\mysql\bin\mysql.exe -u root db_workshopku_dr < backup_dr.sql
C:\xampp\mysql\bin\mysql.exe -u root db_workshopku_dr -e "SELECT COUNT(*) FROM mahasiswa;"
```
Catatan: `mysqldump` menyertakan `DROP TABLE IF EXISTS` + `CREATE TABLE` + `INSERT`, sehingga impor ulang membangun kembali tabel yang hilang beserta isinya.
Berkas foto di `public/uploads/` tidak termasuk backup database dan perlu dicadangkan terpisah.
