<?php

// Mode aplikasi: 'development' => JSON dirapikan (pretty print), 'production' => JSON ringkas.
// Acara 30: bila APP_ENV tidak diatur, mode ditentukan dari nama server:
// localhost / 127.0.0.1 => development, domain lain (hosting) => production.
// Bisa dipaksa lewat environment variable APP_ENV tanpa mengubah kode.
define('APP_ENV', getenv('APP_ENV') ?: (
    preg_match('/^(localhost|127\.0\.0\.1|\[::1\])$/i', (string) ($_SERVER['SERVER_NAME'] ?? 'localhost'))
        ? 'development'
        : 'production'
));

// Acara 30 (Langkah 3): pada produksi pesan error PHP tidak boleh tampil ke pengguna.
// Pengaturan utama ada di MultiPHP INI Editor cPanel (lihat docs/php_production.ini);
// blok ini adalah lapisan cadangan bila pengaturan di panel belum diubah.
if (APP_ENV === 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
}

// Zona waktu server agar timestamp response tampil dalam WIB (+07:00)
date_default_timezone_set('Asia/Jakarta');
