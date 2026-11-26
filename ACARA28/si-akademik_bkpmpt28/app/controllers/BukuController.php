<?php

class BukuController extends BaseController
{
    private Buku $model;

    // Aturan field: nama field => panjang maksimal (sesuai kolom pada tabel)
    private const RULES = ['judul' => 150, 'penulis' => 100, 'tahun_terbit' => 4];

    public function __construct()
    {
        $this->model = new Buku();
    }

    // Validasi tambahan: tahun terbit harus 4 digit angka, dari 1900 sampai tahun berjalan -> kode 422
    private function validateTahun(array $data): void
    {
        $tahun = $data['tahun_terbit'];

        if (!ctype_digit($tahun) || (int) $tahun < 1900 || (int) $tahun > (int) date('Y')) {
            throw new ValidationException(
                'Format tahun_terbit tidak valid',
                ['tahun_terbit' => ['Tahun terbit harus berupa tahun 1900 sampai tahun ini']],
                422
            );
        }
    }

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
        $body = $this->readJson();
        if ($body === null) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }

        try {
            $data = $this->pickFields($body, self::RULES);
            $this->validateTahun($data);
            $id = $this->model->create($data);
        } catch (ValidationException $e) {
            Response::json($e->getCode(), false, $e->getMessage(), null, $e->getErrors());
        }

        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => (int) $id]);
    }

    public function update($id): void
    {
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
            $this->validateTahun($data);
            $this->model->update($id, $data);
        } catch (ValidationException $e) {
            Response::json($e->getCode(), false, $e->getMessage(), null, $e->getErrors());
        }

        Response::json(200, true, 'Data berhasil diperbarui', $this->model->find($id));
    }

    public function destroy($id): void
    {
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
