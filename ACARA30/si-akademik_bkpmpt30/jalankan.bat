@echo off
title API Acara 30 - http://localhost:8080
cd /d "%~dp0public"
set PHPEXE=
where php >nul 2>nul
if %errorlevel%==0 (set PHPEXE=php) else (
  if exist "C:\xampp\php\php.exe" set PHPEXE=C:\xampp\php\php.exe
  if not defined PHPEXE for /d %%D in (C:\laragon\bin\php\php-*) do set PHPEXE=%%D\php.exe
)
if not defined PHPEXE (
  echo PHP tidak ditemukan. Pasang XAMPP/Laragon atau tambahkan php ke PATH.
  pause
  exit /b 1
)
echo Server berjalan di http://localhost:8080  (tekan Ctrl+C untuk berhenti)
echo Gunakan environment Postman "Local (php -S 8080)".
"%PHPEXE%" -S localhost:8080 index.php
pause
