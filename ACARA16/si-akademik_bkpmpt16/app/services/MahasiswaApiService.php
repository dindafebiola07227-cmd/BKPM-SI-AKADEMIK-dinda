<?php

/**
 * Service RESTful API Mahasiswa.
 * Berisi logika bisnis + validasi. Tidak tahu apa pun tentang HTTP,
 * hanya melempar ApiException ketika ada masalah.
 */
class MahasiswaApiService
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

    /** GET /api/mahasiswa */
    public function getAll(): array
    {
        return array_map([$this, 'format'], $this->repository->allDetail());
    }

    /** GET /api/mahasiswa/{id} */
    public function getById(int $id): array
    {
        $mahasiswa = $this->repository->findDetail($id);

        if ($mahasiswa === null) {
            throw new ApiException("Data mahasiswa dengan ID {$id} tidak ditemukan", 404);
        }

        return $this->format($mahasiswa);
    }

    /** POST /api/mahasiswa */
    public function create(array $input): array
    {
        $data = $this->validate($input);

        if ($this->repository->existsByNim($data['nim'])) {
            throw new ApiException('NIM sudah terdaftar', 409, ['nim' => 'NIM sudah digunakan mahasiswa lain']);
        }

        $id = $this->repository->create($data);

        return $this->getById($id);
    }

    /** PUT /api/mahasiswa/{id} */
    public function update(int $id, array $input): array
    {
        $existing = $this->getById($id); // 404 jika ID tidak ada

        $data = $this->validate($input, $existing);

        if ($this->repository->existsByNim($data['nim'], $id)) {
            throw new ApiException('NIM sudah terdaftar', 409, ['nim' => 'NIM sudah digunakan mahasiswa lain']);
        }

        $this->repository->update($id, $data);

        return $this->getById($id);
    }

    /** DELETE /api/mahasiswa/{id} */
    public function delete(int $id): void
    {
        $this->getById($id); // 404 jika ID tidak ada

        $this->repository->delete($id);
    }

    /**
     * Validasi input JSON.
     * - nim, nama, email: WAJIB (data tidak lengkap => 400)
     * - prodi_id, angkatan: opsional. Jika tidak dikirim, dipakai nilai lama
     *   (PUT) atau default (POST: prodi pertama & tahun berjalan).
     */
    private function validate(array $input, ?array $existing = null): array
    {
        $errors = [];

        // 1. Field wajib ada (data JSON lengkap)
        foreach (['nim', 'nama', 'email'] as $field) {
            if (!array_key_exists($field, $input)) {
                $errors[$field] = "Field {$field} wajib dikirim";
            } elseif (!is_string($input[$field])) {
                $errors[$field] = "Field {$field} harus berupa teks";
            }
        }

        if (!empty($errors)) {
            throw new ApiException('Data JSON tidak lengkap atau tipe data salah', 400, $errors);
        }

        $nim = trim($input['nim']);
        $nama = trim($input['nama']);
        $email = trim($input['email']);

        // 2. Isi field tidak boleh kosong & format benar
        if ($nim === '') {
            $errors['nim'] = 'NIM tidak boleh kosong';
        } elseif (strlen($nim) < 5 || strlen($nim) > 20) {
            $errors['nim'] = 'NIM harus 5 sampai 20 karakter';
        }

        if ($nama === '') {
            $errors['nama'] = 'Nama tidak boleh kosong';
        } elseif (strlen($nama) > 100) {
            $errors['nama'] = 'Nama maksimal 100 karakter';
        }

        if ($email === '') {
            $errors['email'] = 'Email tidak boleh kosong';
        } elseif (strlen($email) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Format email tidak valid';
        }

        // 3. prodi_id (opsional)
        $prodiId = null;

        if (array_key_exists('prodi_id', $input)) {
            $prodiId = filter_var($input['prodi_id'], FILTER_VALIDATE_INT);

            if ($prodiId === false || $prodiId < 1) {
                $errors['prodi_id'] = 'prodi_id harus berupa angka bulat positif';
            } elseif ($this->prodiRepository->find($prodiId) === null) {
                $errors['prodi_id'] = "Program studi dengan ID {$prodiId} tidak ditemukan";
            }
        } elseif ($existing !== null) {
            $prodiId = (int) $existing['prodi_id'];
        } else {
            $ids = array_map('intval', array_column($this->prodiRepository->all(), 'id'));

            if (empty($ids)) {
                throw new ApiException('Belum ada data program studi. Tambahkan prodi terlebih dahulu', 409);
            }

            $prodiId = min($ids); // default: prodi dengan ID terkecil
        }

        // 4. angkatan (opsional)
        if (array_key_exists('angkatan', $input)) {
            $angkatan = filter_var($input['angkatan'], FILTER_VALIDATE_INT);

            if ($angkatan === false || $angkatan < 1901 || $angkatan > 2155) {
                $errors['angkatan'] = 'angkatan harus berupa tahun yang valid (1901-2155)';
            }
        } elseif ($existing !== null) {
            $angkatan = (int) $existing['angkatan'];
        } else {
            $angkatan = (int) date('Y');
        }

        if (!empty($errors)) {
            throw new ApiException('Validasi gagal', 400, $errors);
        }

        return [
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodiId,
            'angkatan' => $angkatan,
        ];
    }

    /** Pastikan tipe data angka tampil sebagai number di JSON. */
    private function format(array $row): array
    {
        $row['id'] = (int) $row['id'];
        $row['prodi_id'] = (int) $row['prodi_id'];
        $row['angkatan'] = (int) $row['angkatan'];

        return $row;
    }
}
