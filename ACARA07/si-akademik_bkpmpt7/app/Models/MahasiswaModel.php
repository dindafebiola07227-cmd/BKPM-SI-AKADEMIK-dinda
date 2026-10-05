<?php

namespace App\Models;

require_once __DIR__ . '/Model.php';

class MahasiswaModel extends Model
{
    public function all(): array
    {
        $sql = "SELECT * FROM mahasiswa ORDER BY id ASC";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }
}