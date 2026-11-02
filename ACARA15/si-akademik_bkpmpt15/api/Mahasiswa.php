
<?php
header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$dbname = 'si_akademik';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare(
                'SELECT id, nim, nama, email FROM mahasiswa WHERE id = ?'
            );
            $stmt->execute([$_GET['id']]);
            $data = $stmt->fetch();

            if (!$data) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ]);
                exit;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Data mahasiswa berhasil diambil',
                'data' => $data
            ]);
        } else {
            $stmt = $pdo->query(
                'SELECT id, nim, nama, email FROM mahasiswa'
            );
            $data = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'message' => 'Data mahasiswa berhasil diambil',
                'data' => $data
            ]);
        }

    } elseif ($method === 'POST') {
        $input = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (
            !is_array($input) ||
            empty($input['nim']) ||
            empty($input['nama']) ||
            empty($input['email'])
        ) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'NIM, nama, dan email wajib diisi'
            ]);
            exit;
        }

        $stmt = $pdo->prepare(
            'INSERT INTO mahasiswa (nim, nama, email)
             VALUES (?, ?, ?)'
        );
        $stmt->execute([
            $input['nim'],
            $input['nama'],
            $input['email']
        ]);

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => [
                'id' => $pdo->lastInsertId(),
                'nim' => $input['nim'],
                'nama' => $input['nama'],
                'email' => $input['email']
            ]
        ]);

    } else {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method tidak diizinkan'
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada database'
    ]);
}
