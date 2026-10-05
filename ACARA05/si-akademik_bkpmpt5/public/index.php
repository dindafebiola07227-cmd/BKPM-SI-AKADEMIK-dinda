<?php

require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/SI-AKADEMIK_BKPMPT5/public';

if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method][$uri])) {

    [$controllerName, $action] = $routes[$method][$uri];

    $controllerClass = "App\\Controllers\\{$controllerName}";

    require_once __DIR__ . '/../app/Controllers/'
        . $controllerName . '.php';

    $controller = new $controllerClass();

    $controller->$action();

} elseif (
    $method === 'GET'
    && preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches)
) {

    require_once __DIR__
        . '/../app/Controllers/MahasiswaController.php';

    $controller = new \App\Controllers\MahasiswaController();

    $id = $matches[1];

    $controller->show($id);

} else {

    http_response_code(404);

    echo '
    <!DOCTYPE html>
    <html lang="id">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport"
              content="width=device-width, initial-scale=1">

        <title>404 - Halaman Tidak Ditemukan</title>

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
        >

    </head>

    <body class="bg-light">

        <div class="container py-5">

            <div class="row justify-content-center">

                <div class="col-md-7">

                    <div class="card border-0 shadow text-center">

                        <div class="card-body p-5">

                            <div class="display-1 fw-bold text-danger">
                                404
                            </div>

                            <h1 class="h3 mt-3">
                                Halaman Tidak Ditemukan
                            </h1>

                            <p class="text-muted">
                                Maaf, halaman yang kamu cari
                                tidak tersedia.
                            </p>

                            <a href="' . $base . '/"
                               class="btn btn-primary">
                                ← Kembali ke Beranda
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </body>

    </html>
    ';
}