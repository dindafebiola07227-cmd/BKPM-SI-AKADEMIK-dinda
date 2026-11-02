<?php

class Router
{
    // Peta sederhana: nama resource pada URL => nama class Controller
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
        'register'  => 'AuthController',
    ];

    public function dispatch(): void
    {
        $uri      = trim((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = $uri === '' ? [] : explode('/', $uri);
        $count    = count($segments);
        $last     = $segments[$count - 1] ?? '';
        $prev     = $segments[$count - 2] ?? '';

        // Mendukung  /mahasiswa  dan  /mahasiswa/1  (id juga boleh lewat ?id=1)
        $resource = null;
        if (isset($this->map[$last])) {
            $resource = $last;
        } elseif (ctype_digit($last) && isset($this->map[$prev])) {
            $resource   = $prev;
            $_GET['id'] = $last;
        }

        if ($resource === null) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
        }

        $controllerName = $this->map[$resource];
        $controller     = new $controllerName();

        // register bukan resource CRUD biasa: hanya menerima POST dan langsung ke aksi register()
        if ($resource === 'register') {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Allow: POST');
                Response::json(405, false, 'Method tidak diizinkan');
            }
            $controller->register();
            return;
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':    $controller->index(); break;
            case 'POST':   $controller->store(); break;
            case 'PUT':    $controller->update($_GET['id'] ?? null); break;
            case 'DELETE': $controller->destroy($_GET['id'] ?? null); break;
            default:
                header('Allow: GET, POST, PUT, DELETE');
                Response::json(405, false, 'Method tidak diizinkan');
        }
    }
}
