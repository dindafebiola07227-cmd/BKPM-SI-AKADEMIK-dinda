<?php

namespace App\Models;

use PDO;

class ProdiModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /*
    |--------------------------------------------------------------------------
    | Menampilkan semua data prodi
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT *
             FROM prodi
             ORDER BY id ASC"
        );

        return $stmt->fetchAll();
    }


    /*
    |--------------------------------------------------------------------------
    | Mencari prodi berdasarkan ID
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM prodi
             WHERE id = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }


    /*
    |--------------------------------------------------------------------------
    | Menambahkan prodi
    |--------------------------------------------------------------------------
    */

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO prodi
            (
                kode,
                nama
            )
            VALUES
            (
                :kode,
                :nama
            )"
        );

        return $stmt->execute([
            ':kode' => $data['kode'],
            ':nama' => $data['nama'],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Memperbarui prodi
    |--------------------------------------------------------------------------
    */

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE prodi SET
                kode = :kode,
                nama = :nama
             WHERE id = :id"
        );

        return $stmt->execute([
            ':kode' => $data['kode'],
            ':nama' => $data['nama'],
            ':id'   => $id,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Menghapus prodi
    |--------------------------------------------------------------------------
    */

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM prodi
             WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}