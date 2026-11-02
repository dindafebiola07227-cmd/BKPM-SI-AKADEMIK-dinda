<?php

namespace App\Controllers;

require_once __DIR__ . '/../Models/MatakuliahModel.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

use App\Models\MatakuliahModel;
use App\Models\ProdiModel;

class MatakuliahController
{
    /*
    |--------------------------------------------------------------------------
    | Menampilkan semua mata kuliah
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $model = new MatakuliahModel();

        $matakuliah = $model->all();

        require __DIR__ . '/../Views/matakuliah/index.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan form tambah mata kuliah
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $prodiModel = new ProdiModel();

        $prodi = $prodiModel->all();

        require __DIR__ . '/../Views/matakuliah/create.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Menyimpan mata kuliah baru
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        $model = new MatakuliahModel();

        $data = [
            'kode'     => trim($_POST['kode'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'sks'      => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];

        if (
            $data['kode'] === '' ||
            $data['nama'] === '' ||
            $data['sks'] <= 0 ||
            $data['prodi_id'] <= 0
        ) {
            die('Data mata kuliah belum lengkap.');
        }

        $berhasil = $model->create($data);

        if (!$berhasil) {
            die('Data mata kuliah gagal disimpan.');
        }

        header(
            'Location: /si-akademik_bkpmpt9/public/matakuliah'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan detail mata kuliah
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $model = new MatakuliahModel();

        $matakuliah = $model->find((int) $id);

        if (!$matakuliah) {
            http_response_code(404);

            echo 'Data mata kuliah tidak ditemukan.';

            return;
        }

        require __DIR__ . '/../Views/matakuliah/show.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan form edit mata kuliah
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $model = new MatakuliahModel();

        $matakuliah = $model->find((int) $id);

        if (!$matakuliah) {
            http_response_code(404);

            echo 'Data mata kuliah tidak ditemukan.';

            return;
        }

        $prodiModel = new ProdiModel();

        $prodi = $prodiModel->all();

        require __DIR__ . '/../Views/matakuliah/edit.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Memperbarui mata kuliah
    |--------------------------------------------------------------------------
    */

    public function update($id)
    {
        $model = new MatakuliahModel();

        $data = [
            'kode'     => trim($_POST['kode'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'sks'      => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];

        if (
            $data['kode'] === '' ||
            $data['nama'] === '' ||
            $data['sks'] <= 0 ||
            $data['prodi_id'] <= 0
        ) {
            die('Data mata kuliah belum lengkap.');
        }

        $berhasil = $model->update((int) $id, $data);

        if (!$berhasil) {
            die('Data mata kuliah gagal diperbarui.');
        }

        header(
            'Location: /si-akademik_bkpmpt9/public/matakuliah'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Menghapus mata kuliah
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $model = new MatakuliahModel();

        $berhasil = $model->delete((int) $id);

        if (!$berhasil) {
            die('Data mata kuliah gagal dihapus.');
        }

        header(
            'Location: /si-akademik_bkpmpt9/public/matakuliah'
        );

        exit;
    }
}