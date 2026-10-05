<?php

namespace App\Controllers;

require_once __DIR__ . '/../Models/MahasiswaModel.php';

use App\Models\MahasiswaModel;

class MahasiswaController
{
    public function index()
    {
        $model = new MahasiswaModel();

        $mahasiswa = $model->all();

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }
}