@echo off
REM Acara 26 - Tugas Mandiri 2: backup db_workshop (jalankan setiap akhir sesi praktikum)
REM Hasil: folder backup\db_workshop_YYYY-MM-DD_HHMM.sql  (sesuaikan MYSQLDUMP bila XAMPP tidak di C:\xampp)
set MYSQLDUMP=C:\xampp\mysql\bin\mysqldump.exe
set TUJUAN=%~dp0..\backup
if not exist "%TUJUAN%" mkdir "%TUJUAN%"
for /f %%i in ('powershell -NoProfile -Command "Get-Date -Format yyyy-MM-dd_HHmm"') do set STAMP=%%i
"%MYSQLDUMP%" -u root --routines --single-transaction db_workshop > "%TUJUAN%\db_workshop_%STAMP%.sql"
if errorlevel 1 (echo Backup GAGAL) else (echo Backup berhasil: %TUJUAN%\db_workshop_%STAMP%.sql)
