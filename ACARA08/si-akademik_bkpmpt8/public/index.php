<?php

session_start();

require_once __DIR__ . '/../app/Models/Database.php';
require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/si-akademik_bkpmpt8/public';


/*
|--------------------------------------------------------------------------
| Hapus Base URL
|--------------------------------------------------------------------------
*/

if (stripos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];


/*
|--------------------------------------------------------------------------
| Route Mahasiswa dengan Parameter
|--------------------------------------------------------------------------
|
| GET:
| /mahasiswa/5
| /mahasiswa/5/edit
|
| POST:
| /mahasiswa/5/update
| /mahasiswa/5/delete
|
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        '#^/mahasiswa/([0-9]+)(?:/(edit|update|delete))?$#',
        $uri,
        $matches
    )
) {

    $id = (int) $matches[1];

    $action = $matches[2] ?? null;


    require_once __DIR__
        . '/../app/Controllers/MahasiswaController.php';


    $controller = new \App\Controllers\MahasiswaController();


    /*
    |--------------------------------------------------------------------------
    | Semua halaman mahasiswa membutuhkan login
    |--------------------------------------------------------------------------
    */

    require_once __DIR__
        . '/../app/Middleware/AuthMiddleware.php';

    $middleware = new \App\Middleware\AuthMiddleware();

    $middleware->handle();


    /*
    |--------------------------------------------------------------------------
    | GET /mahasiswa/{id}
    |--------------------------------------------------------------------------
    */

    if ($method === 'GET' && $action === null) {

        $controller->show($id);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | GET /mahasiswa/{id}/edit
    |--------------------------------------------------------------------------
    */

    if ($method === 'GET' && $action === 'edit') {

        $controller->edit($id);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | POST /mahasiswa/{id}/update
    |--------------------------------------------------------------------------
    */

    if ($method === 'POST' && $action === 'update') {

        $controller->update($id);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | POST /mahasiswa/{id}/delete
    |--------------------------------------------------------------------------
    */

    if ($method === 'POST' && $action === 'delete') {

        $controller->delete($id);

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Route Prodi dengan Parameter
|--------------------------------------------------------------------------
|
| GET:
| /prodi/1
| /prodi/1/edit
|
| POST:
| /prodi/1/update
| /prodi/1/delete
|
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        '#^/prodi/([0-9]+)(?:/(edit|update|delete))?$#',
        $uri,
        $matches
    )
) {

    $id = (int) $matches[1];

    $action = $matches[2] ?? null;


    require_once __DIR__
        . '/../app/Controllers/ProdiController.php';


    $controller = new \App\Controllers\ProdiController();


    /*
    |--------------------------------------------------------------------------
    | Semua halaman prodi membutuhkan login
    |--------------------------------------------------------------------------
    */

    require_once __DIR__
        . '/../app/Middleware/AuthMiddleware.php';

    $middleware = new \App\Middleware\AuthMiddleware();

    $middleware->handle();


    /*
    |--------------------------------------------------------------------------
    | GET /prodi/{id}
    |--------------------------------------------------------------------------
    */

    if ($method === 'GET' && $action === null) {

        $controller->show($id);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | GET /prodi/{id}/edit
    |--------------------------------------------------------------------------
    */

    if ($method === 'GET' && $action === 'edit') {

        $controller->edit($id);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | POST /prodi/{id}/update
    |--------------------------------------------------------------------------
    */

    if ($method === 'POST' && $action === 'update') {

        $controller->update($id);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | POST /prodi/{id}/delete
    |--------------------------------------------------------------------------
    */

    if ($method === 'POST' && $action === 'delete') {

        $controller->delete($id);

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Route Mata Kuliah dengan Parameter
|--------------------------------------------------------------------------
|
| GET:
| /matakuliah/1
| /matakuliah/1/edit
|
| POST:
| /matakuliah/1/update
| /matakuliah/1/delete
|
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        '#^/matakuliah/([0-9]+)(?:/(edit|update|delete))?$#',
        $uri,
        $matches
    )
) {

    $id = (int) $matches[1];

    $action = $matches[2] ?? null;


    require_once __DIR__
        . '/../app/Controllers/MatakuliahController.php';


    $controller = new \App\Controllers\MatakuliahController();


    /*
    |--------------------------------------------------------------------------
    | Semua halaman mata kuliah membutuhkan login
    |--------------------------------------------------------------------------
    */

    require_once __DIR__
        . '/../app/Middleware/AuthMiddleware.php';

    $middleware = new \App\Middleware\AuthMiddleware();

    $middleware->handle();


    /*
    |--------------------------------------------------------------------------
    | GET /matakuliah/{id}
    |--------------------------------------------------------------------------
    */

    if ($method === 'GET' && $action === null) {

        $controller->show($id);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | GET /matakuliah/{id}/edit
    |--------------------------------------------------------------------------
    */

    if ($method === 'GET' && $action === 'edit') {

        $controller->edit($id);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | POST /matakuliah/{id}/update
    |--------------------------------------------------------------------------
    */

    if ($method === 'POST' && $action === 'update') {

        $controller->update($id);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | POST /matakuliah/{id}/delete
    |--------------------------------------------------------------------------
    */

    if ($method === 'POST' && $action === 'delete') {

        $controller->delete($id);

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Route Biasa
|--------------------------------------------------------------------------
*/

if (isset($routes[$method][$uri])) {

    [$controllerName, $action] = $routes[$method][$uri];


    /*
    |--------------------------------------------------------------------------
    | Route yang Membutuhkan Login
    |--------------------------------------------------------------------------
    */

    $protectedRoutes = [
        '/dashboard',
        '/mahasiswa',
        '/mahasiswa/create',
        '/prodi',
        '/prodi/create',
        '/matakuliah',
        '/matakuliah/create',
    ];


    if (in_array($uri, $protectedRoutes, true)) {

        require_once __DIR__
            . '/../app/Middleware/AuthMiddleware.php';

        $middleware = new \App\Middleware\AuthMiddleware();

        $middleware->handle();
    }


    /*
    |--------------------------------------------------------------------------
    | Jalankan Controller
    |--------------------------------------------------------------------------
    */

    $controllerClass = "App\\Controllers\\{$controllerName}";


    require_once __DIR__
        . '/../app/Controllers/'
        . $controllerName
        . '.php';


    $controller = new $controllerClass();

    $controller->$action();

    exit;
}


/*
|--------------------------------------------------------------------------
| 404 - Halaman Tidak Ditemukan
|--------------------------------------------------------------------------
*/

http_response_code(404);

echo '
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

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

                        <a
                            href="' . $base . '/"
                            class="btn btn-primary"
                        >
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