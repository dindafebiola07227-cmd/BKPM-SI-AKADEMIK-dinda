<?php

define('BASE_PATH', dirname(__DIR__));

/*
| BASE_URL dihitung otomatis dari lokasi index.php, jadi proyek
| tetap jalan walau folder/path-nya dipindah (mis. /BKPM/ACARA14/...).
*/
define(
    'BASE_URL',
    rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/')
);

/* Session dibedakan per folder proyek, supaya tidak bentrok dengan acara lain di htdocs */
session_name('SIAKADEMIK_' . substr(md5(BASE_PATH), 0, 8));
session_start();

/* Autoloader: App\Models\X => app/Models/X.php */
spl_autoload_register(function (string $class): void {
    if (strncmp($class, 'App\\', 4) !== 0) {
        return;
    }
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

use App\Models\Database;
use App\Models\MahasiswaRepository;
use App\Models\ProdiRepository;
use App\Services\MahasiswaService;
use App\Controllers\MahasiswaController;
use App\Middleware\AuthMiddleware;

require_once BASE_PATH . '/routes/web.php';   // menghasilkan $routes

/* URI & method */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

if (BASE_URL !== '' && stripos($uri, BASE_URL) === 0) {
    $uri = substr($uri, strlen(BASE_URL));
}
if ($uri === '' || $uri === '/index.php') {
    $uri = '/';
}
$uri = $uri === '/' ? '/' : rtrim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

function createMahasiswaController(): MahasiswaController
{
    $db = Database::getInstance();
    $repository = new MahasiswaRepository($db);
    $service = new MahasiswaService($repository, new ProdiRepository($db));

    return new MahasiswaController($repository, $service);
}

function notFound(): void
{
    http_response_code(404);
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow text-center">
                <div class="card-body p-5">
                    <div class="display-1 fw-bold text-danger">404</div>
                    <h1 class="h3 mt-3">Halaman Tidak Ditemukan</h1>
                    <p class="text-muted">Maaf, halaman yang kamu cari tidak tersedia.</p>
                    <a href="<?= BASE_URL ?>/" class="btn btn-primary">&larr; Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
    <?php
}

/* Cari route yang cocok ({id} => angka) */
$matched = null;
$params = [];

foreach ($routes[$method] ?? [] as $pattern => $target) {
    $regex = '#^' . str_replace('\{id\}', '([0-9]+)', preg_quote($pattern, '#')) . '$#';

    if (preg_match($regex, $uri, $m)) {
        array_shift($m);
        $matched = $target;
        $params = array_map('intval', $m);
        break;
    }
}

if ($matched === null) {
    notFound();
    exit;
}

[$controllerName, $action] = $matched;

/* Halaman yang boleh diakses tanpa login */
$publicRoutes = ['/', '/login'];

try {

    if (!in_array($uri, $publicRoutes, true)) {
        (new AuthMiddleware())->handle();
    }

    if ($controllerName === 'MahasiswaController') {
        $controller = createMahasiswaController();
    } else {
        $class = "App\\Controllers\\{$controllerName}";
        $controller = new $class();
    }

    if (!method_exists($controller, $action)) {
        notFound();
        exit;
    }

    $controller->$action(...$params);

} catch (Throwable $e) {

    $logDir = BASE_PATH . '/storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    error_log(
        date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . ' (' . $e->getFile() . ':' . $e->getLine() . ')' . PHP_EOL,
        3,
        $logDir . '/app.log'
    );

    http_response_code(500);
    echo '<h1>Terjadi kesalahan</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
}
