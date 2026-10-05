<?php

namespace App\Models;

use PDO;

class MahasiswaRepository
{
    private PDO $db;


    /*
    |--------------------------------------------------------------------------
    | Constructor Injection
    |--------------------------------------------------------------------------
    */

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan semua data mahasiswa
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT
                mahasiswa.*,
                prodi.nama AS nama_prodi
             FROM mahasiswa
             LEFT JOIN prodi
                ON mahasiswa.prodi_id = prodi.id
             ORDER BY mahasiswa.id ASC"
        );

        return $stmt->fetchAll();
    }


    /*
    |--------------------------------------------------------------------------
    | Mencari mahasiswa berdasarkan NIM atau nama
    |--------------------------------------------------------------------------
    */

    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                mahasiswa.*,
                prodi.nama AS nama_prodi
             FROM mahasiswa
             LEFT JOIN prodi
                ON mahasiswa.prodi_id = prodi.id
             WHERE mahasiswa.nama LIKE :keyword
                OR mahasiswa.nim LIKE :keyword
             ORDER BY mahasiswa.id ASC"
        );

        $stmt->execute([
            ':keyword' => '%' . $keyword . '%'
        ]);

        return $stmt->fetchAll();
    }


    /*
    |--------------------------------------------------------------------------
    | Mencari satu mahasiswa berdasarkan ID
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT
                mahasiswa.*,
                prodi.nama AS nama_prodi
             FROM mahasiswa
             LEFT JOIN prodi
                ON mahasiswa.prodi_id = prodi.id
             WHERE mahasiswa.id = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }


    /*
    |--------------------------------------------------------------------------
    | Menambahkan mahasiswa
    |--------------------------------------------------------------------------
    */

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mahasiswa
            (
                nim,
                nama,
                email,
                prodi_id,
                angkatan,
                status
            )
            VALUES
            (
                :nim,
                :nama,
                :email,
                :prodi_id,
                :angkatan,
                :status
            )"
        );

        return $stmt->execute([
            ':nim'      => $data['nim'],
            ':nama'     => $data['nama'],
            ':email'    => $data['email'],
            ':prodi_id' => $data['prodi_id'],
            ':angkatan' => $data['angkatan'],
            ':status'   => $data['status'],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Memperbarui data mahasiswa
    |--------------------------------------------------------------------------
    */

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa SET
                nim = :nim,
                nama = :nama,
                email = :email,
                prodi_id = :prodi_id,
                angkatan = :angkatan,
                status = :status
             WHERE id = :id"
        );

        return $stmt->execute([
            ':nim'      => $data['nim'],
            ':nama'     => $data['nama'],
            ':email'    => $data['email'],
            ':prodi_id' => $data['prodi_id'],
            ':angkatan' => $data['angkatan'],
            ':status'   => $data['status'],
            ':id'       => $id,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Menghapus data mahasiswa
    |--------------------------------------------------------------------------
    */

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM mahasiswa
             WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}