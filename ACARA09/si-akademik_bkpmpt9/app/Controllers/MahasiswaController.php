<?php

namespace App\Controllers;

require_once __DIR__ . '/../Models/MahasiswaRepository.php';

use App\Models\MahasiswaRepository;

class MahasiswaController
{
    private MahasiswaRepository $repository;


    /*
    |--------------------------------------------------------------------------
    | Constructor Injection
    |--------------------------------------------------------------------------
    */

    public function __construct(MahasiswaRepository $repository)
    {
        $this->repository = $repository;
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan semua data mahasiswa
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $keyword = trim($_GET['search'] ?? '');

        if ($keyword !== '') {
            $mahasiswa = $this->repository->search($keyword);
        } else {
            $mahasiswa = $this->repository->all();
        }

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan form tambah mahasiswa
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        require __DIR__ . '/../Views/mahasiswa/create.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Menyimpan mahasiswa baru
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        $data = [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0),
            'status'   => $_POST['status'] ?? 'aktif',
        ];

        if (
            $data['nim'] === '' ||
            $data['nama'] === '' ||
            $data['email'] === '' ||
            $data['prodi_id'] <= 0 ||
            $data['angkatan'] <= 0
        ) {
            die('Data mahasiswa belum lengkap.');
        }

        $berhasil = $this->repository->create($data);

        if (!$berhasil) {
            die('Data mahasiswa gagal disimpan.');
        }

        header(
            'Location: /si-akademik_bkpmpt9/public/mahasiswa'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan detail mahasiswa
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $mahasiswa = $this->repository->find((int) $id);

        if (!$mahasiswa) {
            http_response_code(404);

            echo 'Data mahasiswa tidak ditemukan.';

            return;
        }

        require __DIR__ . '/../Views/mahasiswa/show.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan form edit mahasiswa
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $mahasiswa = $this->repository->find((int) $id);

        if (!$mahasiswa) {
            http_response_code(404);

            echo 'Data mahasiswa tidak ditemukan.';

            return;
        }

        require __DIR__ . '/../Views/mahasiswa/edit.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Memperbarui data mahasiswa
    |--------------------------------------------------------------------------
    */

    public function update($id)
    {
        $data = [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0),
            'status'   => $_POST['status'] ?? 'aktif',
        ];

        if (
            $data['nim'] === '' ||
            $data['nama'] === '' ||
            $data['email'] === '' ||
            $data['prodi_id'] <= 0 ||
            $data['angkatan'] <= 0
        ) {
            die('Data mahasiswa belum lengkap.');
        }

        $berhasil = $this->repository->update(
            (int) $id,
            $data
        );

        if (!$berhasil) {
            die('Data mahasiswa gagal diperbarui.');
        }

        header(
            'Location: /si-akademik_bkpmpt9/public/mahasiswa'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Menghapus mahasiswa
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $berhasil = $this->repository->delete((int) $id);

        if (!$berhasil) {
            die('Data mahasiswa gagal dihapus.');
        }

        header(
            'Location: /si-akademik_bkpmpt9/public/mahasiswa'
        );

        exit;
    }
}