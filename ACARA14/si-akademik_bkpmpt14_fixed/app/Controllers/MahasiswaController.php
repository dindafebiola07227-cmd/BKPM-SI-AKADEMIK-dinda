<?php

namespace App\Controllers;

require_once __DIR__ . '/../Models/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/ProdiRepository.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';
require_once __DIR__ . '/BaseController.php';

use App\Models\MahasiswaRepository;
use App\Models\ProdiRepository;
use App\Services\MahasiswaService;
use Exception;

class MahasiswaController extends BaseController
{
    private MahasiswaRepository $repository;
    private MahasiswaService $service;

    public function __construct(
        MahasiswaRepository $repository,
        MahasiswaService $service
    ) {
        $this->repository = $repository;
        $this->service = $service;
    }


    /*
    |--------------------------------------------------------------------------
    | DAFTAR MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        try {

            $keyword = trim($_GET['search'] ?? '');

            if ($keyword !== '') {
                $mahasiswa = $this->repository->search($keyword);
            } else {
                $mahasiswa = $this->repository->all();
            }

            $this->view(
                'mahasiswa/index',
                [
                    'mahasiswa' => $mahasiswa
                ]
            );

        } catch (Exception $e) {

            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data mahasiswa gagal ditampilkan.'
            ];

            $this->redirect(
                BASE_URL . '/dashboard'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        try {

            $this->view('mahasiswa/create');

        } catch (Exception $e) {

            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Halaman tambah mahasiswa gagal dibuka.'
            ];

            $this->redirect(
                BASE_URL . '/mahasiswa'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        try {

            $result = $this->service->create($_POST);

            if (!$result['success']) {

                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => $this->formatErrors(
                        $result['errors']
                    )
                ];

                $this->redirect(
                    BASE_URL . '/mahasiswa/create'
                );
            }

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => $result['message']
            ];

            /*
            | PRG
            | POST -> Redirect -> GET
            */

            $this->redirect(
                BASE_URL . '/mahasiswa'
            );

        } catch (Exception $e) {

            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data gagal disimpan.'
            ];

            $this->redirect(
                BASE_URL . '/mahasiswa/create'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        try {

            $mahasiswa = $this->repository->find(
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

        } catch (Exception $e) {

            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data mahasiswa gagal ditampilkan.'
            ];

            $this->redirect(
                BASE_URL . '/mahasiswa'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        try {

            $mahasiswa = $this->repository->find(
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

        } catch (Exception $e) {

            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data mahasiswa gagal ditampilkan.'
            ];

            $this->redirect(
                BASE_URL . '/mahasiswa'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update($id)
    {
        try {

            $result = $this->service->update(
                (int) $id,
                $_POST
            );

            if (!$result['success']) {

                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => $this->formatErrors(
                        $result['errors']
                    )
                ];

                $this->redirect(
                    BASE_URL . '/mahasiswa/'
                    . (int) $id
                    . '/edit'
                );
            }

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => $result['message']
            ];

            /*
            | PRG
            | POST -> Redirect -> GET
            */

            $this->redirect(
                BASE_URL . '/mahasiswa'
            );

        } catch (Exception $e) {

            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data gagal diperbarui.'
            ];

            $this->redirect(
                BASE_URL . '/mahasiswa/'
                . (int) $id
                . '/edit'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        try {

            $berhasil = $this->repository->delete(
                (int) $id
            );

            $_SESSION['flash'] = [
                'type' => $berhasil ? 'success' : 'danger',
                'message' => $berhasil
                    ? 'Data mahasiswa berhasil dihapus.'
                    : 'Data mahasiswa gagal dihapus.'
            ];

        } catch (Exception $e) {

            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data gagal dihapus.'
            ];
        }

        /*
        | PRG
        | POST -> Redirect -> GET
        */

        $this->redirect(
            BASE_URL . '/mahasiswa'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGGING
    |--------------------------------------------------------------------------
    */

    private function logError(Exception $e): void
    {
        $logFile =
            __DIR__
            . '/../../storage/logs/app.log';

        error_log(
            date('Y-m-d H:i:s')
            . ' - '
            . $e->getMessage()
            . PHP_EOL,
            3,
            $logFile
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT ERROR
    |--------------------------------------------------------------------------
    */

    private function formatErrors(array $errors): string
    {
        if (empty($errors)) {
            return 'Data gagal disimpan.';
        }

        return implode(' ', $errors);
    }
}
