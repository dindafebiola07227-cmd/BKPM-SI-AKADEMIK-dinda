<?php

namespace App\Controllers;

require_once __DIR__ . '/../Models/ProdiModel.php';

use App\Models\ProdiModel;

class ProdiController
{
    /*
    |--------------------------------------------------------------------------
    | Menampilkan semua data prodi
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $model = new ProdiModel();

        $prodi = $model->all();

        require __DIR__ . '/../Views/prodi/index.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan form tambah prodi
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        require __DIR__ . '/../Views/prodi/create.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Menyimpan prodi baru
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        $model = new ProdiModel();

        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
        ];

        if (
            $data['kode'] === '' ||
            $data['nama'] === ''
        ) {
            die('Data prodi belum lengkap.');
        }

        $berhasil = $model->create($data);

        if (!$berhasil) {
            die('Data prodi gagal disimpan.');
        }

        header(
            'Location: /si-akademik_bkpmpt8/public/prodi'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan detail prodi
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $model = new ProdiModel();

        $prodi = $model->find((int) $id);

        if (!$prodi) {
            http_response_code(404);

            echo 'Data prodi tidak ditemukan.';

            return;
        }

        require __DIR__ . '/../Views/prodi/show.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan form edit prodi
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $model = new ProdiModel();

        $prodi = $model->find((int) $id);

        if (!$prodi) {
            http_response_code(404);

            echo 'Data prodi tidak ditemukan.';

            return;
        }

        require __DIR__ . '/../Views/prodi/edit.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Memperbarui prodi
    |--------------------------------------------------------------------------
    */

    public function update($id)
    {
        $model = new ProdiModel();

        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
        ];

        if (
            $data['kode'] === '' ||
            $data['nama'] === ''
        ) {
            die('Data prodi belum lengkap.');
        }

        $berhasil = $model->update((int) $id, $data);

        if (!$berhasil) {
            die('Data prodi gagal diperbarui.');
        }

        header(
            'Location: /si-akademik_bkpmpt8/public/prodi'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Menghapus prodi
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $model = new ProdiModel();

        $berhasil = $model->delete((int) $id);

        if (!$berhasil) {
            die('Data prodi gagal dihapus.');
        }

        header(
            'Location: /si-akademik_bkpmpt8/public/prodi'
        );

        exit;
    }
}