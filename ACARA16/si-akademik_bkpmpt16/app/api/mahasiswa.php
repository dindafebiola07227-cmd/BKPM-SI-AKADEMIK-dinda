<?php

/**
 * Entry point RESTful API Mahasiswa.
 *
 * Dipanggil oleh public/index.php untuk URL:
 *   GET    /api/mahasiswa
 *   GET    /api/mahasiswa/{id}
 *   POST   /api/mahasiswa
 *   PUT    /api/mahasiswa/{id}
 *   DELETE /api/mahasiswa/{id}
 */

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/BaseModel.php';
require_once __DIR__ . '/../Core/ApiException.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../repositories/ProdiRepository.php';
require_once __DIR__ . '/../services/MahasiswaApiService.php';
require_once __DIR__ . '/../controllers/MahasiswaApiController.php';

try {
    $pdo = Database::getInstance();
} catch (PDOException $e) {
    error_log('[API Mahasiswa] ' . $e->getMessage());
    Response::error('Tidak dapat terhubung ke database', 500);
    exit;
}

$controller = new MahasiswaApiController(
    new MahasiswaApiService(
        new MahasiswaRepository($pdo),
        new ProdiRepository($pdo)
    ),
    new Request()
);

// ID dari URL /api/mahasiswa/{id} (diisi router di public/index.php)
$controller->handle($_GET['id'] ?? null);
