<?php

namespace App\Models;

class ProdiRepository extends BaseModel
{
    /*
    |--------------------------------------------------------------------------
    | Mengecek apakah prodi tersedia
    |--------------------------------------------------------------------------
    */

    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id
             FROM prodi
             WHERE id = :id
             LIMIT 1"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch() !== false;
    }
}

