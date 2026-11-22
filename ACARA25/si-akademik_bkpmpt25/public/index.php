<?php

// Acara 24 (Langkah 1): header CORS dipasang satu kali pada Front Controller,
// sebelum Router dijalankan, sehingga berlaku untuk seluruh resource.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight request dari browser (method OPTIONS) cukup dijawab 204 tanpa isi
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Khusus php -S (jalankan.bat): berkas statis di public/ (views/*.html, *.js, *.css) dilayani langsung,
// tidak dilempar ke Router. Pada Apache/XAMPP hal ini sudah ditangani .htaccess.
if (PHP_SAPI === 'cli-server') {
    $pathStatis = (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($pathStatis !== '/' && $pathStatis !== '/index.php' && is_file(__DIR__ . $pathStatis)) {
        return false;
    }
}

require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/core/autoload.php';

// Penjaga terakhir: error tak terduga tetap dibalas dalam format JSON yang seragam
set_exception_handler(function (Throwable $e): void {
    error_log($e->getMessage());
    Response::json(500, false, 'Terjadi kesalahan pada server');
});

(new Router())->dispatch();
