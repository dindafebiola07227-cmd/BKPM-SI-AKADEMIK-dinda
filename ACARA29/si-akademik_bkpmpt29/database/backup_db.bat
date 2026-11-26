@echo off
rem Acara 27 - backup database db_workshopku (jalankan di akhir setiap sesi praktikum)
rem Hasil: folder backup\db_workshopku_TANGGAL_JAM.sql di samping berkas ini
setlocal
set MYSQLDUMP=C:\xampp\mysql\bin\mysqldump.exe
if not exist "%MYSQLDUMP%" (
  echo mysqldump tidak ditemukan di %MYSQLDUMP%. Sesuaikan path XAMPP pada berkas ini.
  pause
  exit /b 1
)
if not exist "%~dp0backup" mkdir "%~dp0backup"
for /f %%I in ('powershell -NoProfile -Command "Get-Date -Format yyyyMMdd_HHmm"') do set STAMP=%%I
set OUT=%~dp0backup\db_workshopku_%STAMP%.sql
"%MYSQLDUMP%" -u root db_workshopku > "%OUT%"
if %errorlevel%==0 (echo Backup berhasil: %OUT%) else (echo Backup gagal. Pastikan MySQL di XAMPP sedang berjalan.)
pause
