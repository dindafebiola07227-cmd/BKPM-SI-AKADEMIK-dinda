<?php

namespace App\Controllers;

require_once __DIR__ . '/../Models/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/ProdiRepository.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';
require_once __DIR__ . '/BaseController.php';

use App\Models\MahasiswaRepository;
use App\Models\ProdiRepository;
use App\Services\MahasiswaService;

class MahasiswaController extends BaseController
{
    private MahasiswaRepository $repository;
    private MahasiswaService $service;


    /*
    |--------------------------------------------------------------------------
    | Constructor Injection
    |--------------------------------------------------------------------------
    */

    public function __construct(
        MahasiswaRepository $repository,
        MahasiswaService $service
    ) {
        $this->repository = $repository;
        $this->service = $service;
    }


    /*
    |--------------------------------------------------------------------------
    | Menampilkan mahasiswa
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $keyword = trim(
            $_GET['search'] ?? ''
        );

        if ($keyword !== '') {

            $mahasiswa =
                $this->repository->search($keyword);

        } else {

            $mahasiswa =
                $this->repository->all();
        }

        $this->view(
            'mahasiswa/index',
            [
                'mahasiswa' => $mahasiswa
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Form tambah
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $this->view(
            'mahasiswa/create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Menyimpan mahasiswa
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        $result = $this->service->create($_POST);

        if (!$result['success']) {

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' =>
                    $this->formatErrors(
                        $result['errors']
                    )
            ];

            header(
                'Location: /si-akademik_bkpmpt13/public/mahasiswa/create'
            );

            exit;
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => $result['message']
        ];

        header(
            'Location: /si-akademik_bkpmpt13/public/mahasiswa'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Detail mahasiswa
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $mahasiswa =
            $this->repository->find(
                (int) $id
            );

        if (!$mahasiswa) {

            http_response_code(404);

            echo 'Data mahasiswa tidak ditemukan.';

            return;
        }

        $this->view(
            'mahasiswa/show',
            [
                'mahasiswa' => $mahasiswa
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Form edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $mahasiswa =
            $this->repository->find(
                (int) $id
            );

        if (!$mahasiswa) {

            http_response_code(404);

            echo 'Data mahasiswa tidak ditemukan.';

            return;
        }

        $this->view(
            'mahasiswa/edit',
            [
                'mahasiswa' => $mahasiswa
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Memperbarui mahasiswa
    |--------------------------------------------------------------------------
    */

    public function update($id)
    {
        $result =
            $this->service->update(
                (int) $id,
                $_POST
            );

        if (!$result['success']) {

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' =>
                    $this->formatErrors(
                        $result['errors']
                    )
            ];

            header(
                'Location: /si-akademik_bkpmpt13/public/mahasiswa/'
                . $id
                . '/edit'
            );

            exit;
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => $result['message']
        ];

        header(
            'Location: /si-akademik_bkpmpt13/public/mahasiswa'
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
        $berhasil =
            $this->repository->delete(
                (int) $id
            );

        $_SESSION['flash'] = [
            'type' =>
                $berhasil ? 'success' : 'danger',

            'message' =>
                $berhasil
                    ? 'Data mahasiswa berhasil dihapus.'
                    : 'Data mahasiswa gagal dihapus.'
        ];

        header(
            'Location: /si-akademik_bkpmpt13/public/mahasiswa'
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Format Error
    |--------------------------------------------------------------------------
    */

    private function formatErrors(
        array $errors
    ): string {

        if (empty($errors)) {
            return 'Data gagal disimpan.';
        }

        return implode(
            ' ',
            $errors
        );
    }
}