<?php

class Router
{
    // Peta sederhana: nama resource pada URL => nama class Controller
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
        'register'  => 'AuthController',
        'login'     => 'AuthController',
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

        // Halaman cek koneksi: alamat dasar (berakhir "/"), /public, /index.php, atau /ping
        // (membantu memastikan Apache / php -S sudah berjalan sebelum menguji lewat Postman)
        $path = (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($resource === null && (substr($path, -1) === '/' || in_array($last, ['public', 'index.php', 'ping'], true))) {
            Response::json(200, true, 'API Sistem Informasi Akademik (Acara 27) berjalan', [
                'endpoints' => [
                    'POST /register',
                    'POST /login',
                    'GET /mahasiswa, GET /mahasiswa?id=1',
                    'POST|PUT|DELETE /mahasiswa (butuh Bearer token admin; foto lewat multipart)',
                    'GET|POST|PUT|DELETE /buku',
                ],
            ]);
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

        // Acara 20 (Langkah 3): login juga aksi tunggal, hanya menerima POST
        if ($resource === 'login') {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Allow: POST');
                Response::json(405, false, 'Method tidak diizinkan');
            }
            $controller->login();
            return;
        }

        // Acara 26: PHP tidak mengisi $_POST/$_FILES untuk method PUT, sehingga update yang membawa
        // berkas dikirim sebagai POST dengan field _method=PUT (atau header X-HTTP-Method-Override: PUT)
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            $override = strtoupper((string) ($_POST['_method'] ?? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ?? ''));
            if ($override === 'PUT' && ctype_digit((string) ($_GET['id'] ?? ''))) {
                $method = 'PUT';
            }
        }

        switch ($method) {
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
