<?php

class Router
{
    // Peta sederhana: nama resource pada URL => nama class Controller
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
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
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Endpoint tidak ditemukan']);
            exit;
        }

        $controllerName = $this->map[$resource];
        $controller     = new $controllerName();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':    $controller->index(); break;
            case 'POST':   $controller->store(); break;
            case 'PUT':    $controller->update($_GET['id'] ?? null); break;
            case 'DELETE': $controller->destroy($_GET['id'] ?? null); break;
            default:
                http_response_code(405);
                header('Allow: GET, POST, PUT, DELETE');
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
        }
    }
}
