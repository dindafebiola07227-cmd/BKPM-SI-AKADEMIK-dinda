<?php

namespace App\Models;

use PDO;

class MatakuliahModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan semua data mata kuliah
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT
                matakuliah.*,
                prodi.nama AS nama_prodi
             FROM matakuliah
             LEFT JOIN prodi
                ON matakuliah.prodi_id = prodi.id
             ORDER BY matakuliah.id ASC"
        );

        return $stmt->fetchAll();
    }


    /*
    |--------------------------------------------------------------------------
    | Mencari mata kuliah berdasarkan ID
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT
                matakuliah.*,
                prodi.nama AS nama_prodi
             FROM matakuliah
             LEFT JOIN prodi
                ON matakuliah.prodi_id = prodi.id
             WHERE matakuliah.id = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }


    /*
    |--------------------------------------------------------------------------
    | Menambahkan mata kuliah
    |--------------------------------------------------------------------------
    */

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO matakuliah
            (
                kode,
                nama,
                sks,
                prodi_id
            )
            VALUES
            (
                :kode,
                :nama,
                :sks,
                :prodi_id
            )"
        );

        return $stmt->execute([
            ':kode'     => $data['kode'],
            ':nama'     => $data['nama'],
            ':sks'      => $data['sks'],
            ':prodi_id' => $data['prodi_id'],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Memperbarui mata kuliah
    |--------------------------------------------------------------------------
    */

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE matakuliah SET
                kode = :kode,
                nama = :nama,
                sks = :sks,
                prodi_id = :prodi_id
             WHERE id = :id"
        );

        return $stmt->execute([
            ':kode'     => $data['kode'],
            ':nama'     => $data['nama'],
            ':sks'      => $data['sks'],
            ':prodi_id' => $data['prodi_id'],
            ':id'       => $id,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Menghapus mata kuliah
    |--------------------------------------------------------------------------
    */

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM matakuliah
             WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}