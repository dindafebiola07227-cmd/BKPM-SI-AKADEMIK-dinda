<?php

require_once __DIR__ . '/../app/core/autoload.php';

// Penjaga terakhir: error tak terduga tetap dibalas dalam format JSON
set_exception_handler(function (Throwable $e): void {
    error_log($e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan pada server']);
});

(new Router())->dispatch();
