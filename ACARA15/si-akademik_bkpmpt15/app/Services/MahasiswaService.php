<?php

namespace App\Services;

require_once __DIR__ . '/../Models/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/ProdiRepository.php';

use App\Models\MahasiswaRepository;
use App\Models\ProdiRepository;
use Exception;

class MahasiswaService
{
    private MahasiswaRepository $repository;
    private ProdiRepository $prodiRepository;

    public function __construct(
        MahasiswaRepository $repository,
        ProdiRepository $prodiRepository
    ) {
        $this->repository = $repository;
        $this->prodiRepository = $prodiRepository;
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(array $input): array
    {
        try {

            $data = $this->prepareData($input);

            $errors = $this->validate($data);

            if (!empty($errors)) {
                return [
                    'success' => false,
                    'errors'  => $errors
                ];
            }

            $berhasil = $this->repository->create($data);

            if (!$berhasil) {
                throw new Exception(
                    'Data mahasiswa gagal disimpan.'
                );
            }

            return [
                'success' => true,
                'message' =>
                    'Data mahasiswa berhasil ditambahkan.'
            ];

        } catch (Exception $e) {

            $this->logError($e);

            return [
                'success' => false,
                'errors' => [
                    'general' =>
                        'Data gagal disimpan.'
                ]
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        int $id,
        array $input
    ): array {

        try {

            $data = $this->prepareData($input);

            $errors =
                $this->validate(
                    $data,
                    $id
                );

            if (!empty($errors)) {
                return [
                    'success' => false,
                    'errors'  => $errors
                ];
            }

            $berhasil =
                $this->repository->update(
                    $id,
                    $data
                );

            if (!$berhasil) {
                throw new Exception(
                    'Data mahasiswa gagal diperbarui.'
                );
            }

            return [
                'success' => true,
                'message' =>
                    'Data mahasiswa berhasil diubah.'
            ];

        } catch (Exception $e) {

            $this->logError($e);

            return [
                'success' => false,
                'errors' => [
                    'general' =>
                        'Data gagal diperbarui.'
                ]
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA
    |--------------------------------------------------------------------------
    */

    private function prepareData(
        array $input
    ): array {

        return [
            'nim' =>
                trim($input['nim'] ?? ''),

            'nama' =>
                trim($input['nama'] ?? ''),

            'email' =>
                trim($input['email'] ?? ''),

            'prodi_id' =>
                (int) ($input['prodi_id'] ?? 0),

            'angkatan' =>
                (int) ($input['angkatan'] ?? 0),

            'status' =>
                $input['status'] ?? 'aktif'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    private function validate(
        array $data,
        ?int $exceptId = null
    ): array {

        $errors = [];


        // Validasi NIM
        if ($data['nim'] === '') {

            $errors['nim'] =
                'NIM wajib diisi.';

        } elseif (
            $this->repository->existsByNim(
                $data['nim'],
                $exceptId
            )
        ) {

            $errors['nim'] =
                'NIM sudah terdaftar.';
        }


        // Validasi nama
        if ($data['nama'] === '') {

            $errors['nama'] =
                'Nama wajib diisi.';
        }


        // Validasi email
        if ($data['email'] === '') {

            $errors['email'] =
                'Email wajib diisi.';

        } elseif (
            !filter_var(
                $data['email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $errors['email'] =
                'Format email tidak valid.';
        }


        // Validasi prodi
        if ($data['prodi_id'] <= 0) {

            $errors['prodi_id'] =
                'Program studi wajib dipilih.';

        } elseif (
            !$this->prodiRepository->exists(
                $data['prodi_id']
            )
        ) {

            $errors['prodi_id'] =
                'Program studi tidak tersedia.';
        }


        // Validasi angkatan
        if ($data['angkatan'] <= 0) {

            $errors['angkatan'] =
                'Angkatan wajib diisi.';
        }


        return $errors;
    }


    /*
    |--------------------------------------------------------------------------
    | LOGGING
    |--------------------------------------------------------------------------
    */

    private function logError(
        Exception $e
    ): void {

        $logDirectory =
            __DIR__ . '/../../storage/logs';

        if (!is_dir($logDirectory)) {

            mkdir(
                $logDirectory,
                0777,
                true
            );
        }

        error_log(
            date('Y-m-d H:i:s')
            . ' - '
            . $e->getMessage()
            . PHP_EOL,
            3,
            $logDirectory . '/app.log'
        );
    }
}

