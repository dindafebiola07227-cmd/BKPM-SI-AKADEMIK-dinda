<?php

class MahasiswaController extends BaseController
{
    private Mahasiswa $model;

    // Aturan field: nama field => panjang maksimal (sesuai kolom pada tabel)
    private const RULES = ['nim' => 15, 'nama' => 100, 'prodi' => 50];

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    // Langkah 3: validasi format NIM (harus 9 karakter) -> kode 422
    private function validateFormat(array $data): void
    {
        // Perbaikan Acara 23 (TC07/TC18): NIM harus 9 digit ANGKA, bukan sekadar 9 karakter
        if (strlen($data['nim']) !== 9 || !ctype_digit($data['nim'])) {
            throw new ValidationException(
                'Format NIM tidak valid, harus 9 digit angka',
                ['nim' => ['NIM harus terdiri dari 9 digit angka']],
                422
            );
        }
    }

    // GET tetap publik (tanpa token) untuk kebutuhan pengujian pada acara berikutnya
    public function index(): void
    {
        if (isset($_GET['id'])) {
            $id = $this->validId($_GET['id']);
            if ($id === null) {
                Response::json(400, false, 'ID harus berupa angka positif');
            }

            $data = $this->model->find($id);
            if (!$data) {
                Response::json(404, false, 'Data tidak ditemukan');
            }

            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    public function store(): void
    {
        Auth::requireAdmin(); // Acara 25 (Langkah 2): hanya admin yang boleh menambah data

        $body = $this->readJson();
        if ($body === null) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }

        try {
            $data = $this->pickFields($body, self::RULES);
            $this->validateFormat($data);
            $id = $this->model->create($data);
        } catch (ValidationException $e) {
            Response::json($e->getCode(), false, $e->getMessage(), null, $e->getErrors());
        } catch (DomainException $e) {
            Response::json(422, false, $e->getMessage(), null, ['nim' => [$e->getMessage()]]); // Perbaikan Acara 23 (TC03/TC17): NIM duplikat = 422, bukan 409
        }

        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => (int) $id]);
    }

    public function update($id): void
    {
        Auth::requireAdmin(); // Acara 25 (Langkah 2)

        $id = $this->validId($id);
        if ($id === null) {
            Response::json(400, false, 'ID wajib diisi dan harus berupa angka positif');
        }

        $body = $this->readJson();
        if ($body === null) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        try {
            $data = $this->pickFields($body, self::RULES);
            $this->validateFormat($data);
            $this->model->update($id, $data);
        } catch (ValidationException $e) {
            Response::json($e->getCode(), false, $e->getMessage(), null, $e->getErrors());
        } catch (DomainException $e) {
            Response::json(422, false, $e->getMessage(), null, ['nim' => [$e->getMessage()]]); // Perbaikan Acara 23 (TC03/TC17): NIM duplikat = 422, bukan 409
        }

        Response::json(200, true, 'Data berhasil diperbarui', $this->model->find($id));
    }

    public function destroy($id): void
    {
        Auth::requireAdmin(); // Acara 25 (Langkah 2)

        $id = $this->validId($id);
        if ($id === null) {
            Response::json(400, false, 'ID wajib diisi dan harus berupa angka positif');
        }

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->delete($id);
        Response::json(200, true, 'Data berhasil dihapus');
    }
}
