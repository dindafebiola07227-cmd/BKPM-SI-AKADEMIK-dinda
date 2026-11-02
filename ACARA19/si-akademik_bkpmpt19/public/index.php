<?php

require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/core/autoload.php';

// Penjaga terakhir: error tak terduga tetap dibalas dalam format JSON yang seragam
set_exception_handler(function (Throwable $e): void {
    error_log($e->getMessage());
    Response::json(500, false, 'Terjadi kesalahan pada server');
});

(new Router())->dispatch();
