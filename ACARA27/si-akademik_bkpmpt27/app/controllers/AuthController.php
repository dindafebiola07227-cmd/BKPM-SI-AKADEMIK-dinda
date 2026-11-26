<?php

/**
 * Controller yang tidak mengikuti pola CRUD (index/store/update/destroy).
 * Berisi aksi register() (Acara 19) dan login() (Acara 20, Langkah 2).
 */
class AuthController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }

        $username = isset($data['username']) && is_string($data['username']) ? trim($data['username']) : '';
        $email    = isset($data['email']) && is_string($data['email']) ? trim($data['email']) : '';
        $password = isset($data['password']) && is_string($data['password']) ? $data['password'] : '';

        $missing = [];
        if ($username === '') { $missing['username'] = ['Username wajib diisi']; }
        if ($email === '')    { $missing['email']    = ['Email wajib diisi']; }
        if ($password === '') { $missing['password'] = ['Password wajib diisi']; }

        if ($missing !== []) {
            Response::json(400, false, 'Username, email, dan password wajib diisi', null, $missing);
        }

        try {
            // Hanya 3 field ini yang disimpan; role selalu 'user' (default tabel), tidak bisa diisi dari request
            $id = $this->model->create([
                'username' => $username,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        } catch (ValidationException $e) {
            Response::json($e->getCode(), false, $e->getMessage(), null, $e->getErrors());
        }

        // Hash password tidak pernah dikembalikan ke client
        Response::json(201, true, 'Registrasi berhasil', ['id' => (int) $id]);
    }

    // Acara 20 - Langkah 2: verifikasi username + password, lalu kirim token bila cocok
    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }

        $username = isset($data['username']) && is_string($data['username']) ? trim($data['username']) : '';
        $password = isset($data['password']) && is_string($data['password']) ? $data['password'] : '';

        $missing = [];
        if ($username === '') { $missing['username'] = ['Username wajib diisi']; }
        if ($password === '') { $missing['password'] = ['Password wajib diisi']; }

        if ($missing !== []) {
            Response::json(400, false, 'Username dan password wajib diisi', null, $missing);
        }

        $user = $this->model->findByUsername($username);

        // Pesan sengaja sama untuk "username tidak ada" dan "password salah"
        // agar penyerang tidak bisa menebak username mana yang terdaftar
        if (!$user || !password_verify($password, $user['password'])) {
            Response::json(401, false, 'Username atau password salah');
        }

        $token = Auth::generateToken(['id' => (int) $user['id'], 'role' => $user['role']]);

        Response::json(200, true, 'Login berhasil', [
            'token'      => $token,
            'role'       => $user['role'],
            'expires_in' => Auth::TOKEN_TTL,
        ]);
    }
}
