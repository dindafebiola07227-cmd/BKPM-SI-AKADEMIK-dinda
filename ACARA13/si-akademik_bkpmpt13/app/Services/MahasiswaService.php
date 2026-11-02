<?php

namespace App\Services;

require_once __DIR__ . '/../Models/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/ProdiRepository.php';

use App\Models\MahasiswaRepository;
use App\Models\ProdiRepository;

class MahasiswaService
{
    private MahasiswaRepository $repository;
    private ProdiRepository $prodiRepository;


    /*
    |--------------------------------------------------------------------------
    | Constructor Injection
    |--------------------------------------------------------------------------
    */

    public function __construct(
        MahasiswaRepository $repository,
        ProdiRepository $prodiRepository
    ) {
        $this->repository = $repository;
        $this->prodiRepository = $prodiRepository;
    }


    /*
    |--------------------------------------------------------------------------
    | Menambahkan mahasiswa
    |--------------------------------------------------------------------------
    */

    public function create(array $input): array
    {
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
            return [
                'success' => false,
                'errors'  => [
                    'general' => 'Data mahasiswa gagal disimpan.'
                ]
            ];
        }

        return [
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan.'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Memperbarui mahasiswa
    |--------------------------------------------------------------------------
    */

    public function update(int $id, array $input): array
    {
        $data = $this->prepareData($input);

        $errors = $this->validate($data, $id);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors'  => $errors
            ];
        }

        $berhasil = $this->repository->update($id, $data);

        if (!$berhasil) {
            return [
                'success' => false,
                'errors'  => [
                    'general' => 'Data mahasiswa gagal diperbarui.'
                ]
            ];
        }

        return [
            'success' => true,
            'message' => 'Data mahasiswa berhasil diubah.'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Menyiapkan data
    |--------------------------------------------------------------------------
    */

    private function prepareData(array $input): array
    {
        return [
            'nim'      => trim($input['nim'] ?? ''),
            'nama'     => trim($input['nama'] ?? ''),
            'email'    => trim($input['email'] ?? ''),
            'prodi_id' => (int) ($input['prodi_id'] ?? 0),
            'angkatan' => (int) ($input['angkatan'] ?? 0),
            'status'   => $input['status'] ?? 'aktif'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Validasi
    |--------------------------------------------------------------------------
    */

    private function validate(
        array $data,
        ?int $exceptId = null
    ): array {

        $errors = [];


        if ($data['nim'] === '') {

            $errors['nim'] = 'NIM wajib diisi.';

        } elseif (
            $this->repository->existsByNim(
                $data['nim'],
                $exceptId
            )
        ) {

            $errors['nim'] = 'NIM sudah terdaftar.';
        }


        if ($data['nama'] === '') {

            $errors['nama'] = 'Nama wajib diisi.';
        }


        if ($data['email'] === '') {

            $errors['email'] = 'Email wajib diisi.';

        } elseif (
            !filter_var(
                $data['email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $errors['email'] = 'Format email tidak valid.';
        }


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


        if ($data['angkatan'] <= 0) {

            $errors['angkatan'] =
                'Angkatan wajib diisi.';
        }


        return $errors;
    }
}