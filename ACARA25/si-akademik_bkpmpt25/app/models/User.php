<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Tugas Mandiri 1: validasi format email dengan filter_var() sebelum data disimpan
    private function validate(array $data): void
    {
        $errors = [];

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'Format email tidak valid';
        }

        // Batas panjang mengikuti kolom pada tabel users
        if (mb_strlen($data['username']) > 50) {
            $errors['username'][] = 'Username maksimal 50 karakter';
        }
        if (mb_strlen($data['email']) > 100) {
            $errors['email'][] = 'Email maksimal 100 karakter';
        }

        if ($errors !== []) {
            $first = reset($errors);
            throw new ValidationException($first[0], $errors, 422);
        }
    }

    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        return $stmt->fetch();
    }

    // $data['password'] harus sudah berupa hash (password_hash) saat masuk ke sini
    public function create(array $data): string
    {
        $this->validate($data);

        if ($this->findByUsername($data['username'])) {
            throw new ValidationException('Username sudah terdaftar', ['username' => ['Username sudah terdaftar']], 409);
        }
        if ($this->findByEmail($data['email'])) {
            throw new ValidationException('Email sudah terdaftar', ['email' => ['Email sudah terdaftar']], 409);
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password) VALUES (:u, :e, :p)'
        );
        $stmt->execute([':u' => $data['username'], ':e' => $data['email'], ':p' => $data['password']]);
        return $this->db->lastInsertId();
    }
}
