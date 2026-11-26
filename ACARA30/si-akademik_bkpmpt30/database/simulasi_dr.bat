@echo off
rem Acara 30 - Tugas Mandiri 3: simulasi disaster recovery pada basis data PERCOBAAN db_workshopku_dr
rem Basis data asli db_workshopku hanya dibaca (disalin), tidak diubah.
setlocal
set BIN=C:\xampp\mysql\bin
set DB_ASLI=db_workshopku
set DB_DR=db_workshopku_dr
set TABEL=mahasiswa
if not exist "%BIN%\mysql.exe" (
  echo mysql.exe tidak ditemukan di %BIN%. Sesuaikan path XAMPP pada berkas ini.
  pause
  exit /b 1
)
if not exist "%~dp0backup" mkdir "%~dp0backup"
set BACKUP=%~dp0backup\dr_sebelum_bencana.sql

echo.
echo === 1. Menyiapkan basis data percobaan %DB_DR% (salinan %DB_ASLI%) ===
"%BIN%\mysql.exe" -u root -e "DROP DATABASE IF EXISTS %DB_DR%; CREATE DATABASE %DB_DR% CHARACTER SET utf8mb4;" || goto gagal
"%BIN%\mysqldump.exe" -u root %DB_ASLI% > "%~dp0backup\salinan_asli.sql" || goto gagal
"%BIN%\mysql.exe" -u root %DB_DR% < "%~dp0backup\salinan_asli.sql" || goto gagal

echo.
echo === 2. Membuat berkas backup: %BACKUP% ===
"%BIN%\mysqldump.exe" -u root %DB_DR% > "%BACKUP%" || goto gagal

echo.
echo === 3. Jumlah baris SEBELUM bencana ===
"%BIN%\mysql.exe" -u root %DB_DR% -e "SELECT 'mahasiswa' AS tabel, COUNT(*) AS jumlah FROM mahasiswa UNION ALL SELECT 'buku', COUNT(*) FROM buku UNION ALL SELECT 'users', COUNT(*) FROM users;"

echo.
echo === 4. BENCANA: menghapus tabel %TABEL% dari %DB_DR% ===
"%BIN%\mysql.exe" -u root %DB_DR% -e "DROP TABLE %TABEL%;"
echo Mencoba membaca tabel %TABEL% (galat 1146 adalah hasil yang diharapkan):
"%BIN%\mysql.exe" -u root %DB_DR% -e "SELECT COUNT(*) FROM %TABEL%;"

echo.
echo === 5. PEMULIHAN dari berkas backup ===
"%BIN%\mysql.exe" -u root %DB_DR% < "%BACKUP%" || goto gagal

echo.
echo === 6. Jumlah baris SESUDAH pemulihan (harus sama dengan langkah 3) ===
"%BIN%\mysql.exe" -u root %DB_DR% -e "SELECT 'mahasiswa' AS tabel, COUNT(*) AS jumlah FROM mahasiswa UNION ALL SELECT 'buku', COUNT(*) FROM buku UNION ALL SELECT 'users', COUNT(*) FROM users;"
echo.
echo Simulasi selesai. Ambil tangkapan layar langkah 3, 4, dan 6 untuk laporan.
pause
exit /b 0

:gagal
echo.
echo Terjadi kegagalan. Pastikan MySQL di XAMPP berjalan dan database %DB_ASLI% ada.
pause
exit /b 1
