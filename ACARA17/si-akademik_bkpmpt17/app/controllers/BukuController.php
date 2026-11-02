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

    // Validasi tambahan: tahun terbit harus 4 digit angka, dari 1900 sampai tahun berjalan
    private function validateTahun(array $data): void
    {
        $tahun = $data['tahun_terbit'];

        if (!ctype_digit($tahun) || (int) $tahun < 1900 || (int) $tahun > (int) date('Y')) {
            throw new InvalidArgumentException('Field tahun_terbit harus berupa tahun (1900 sampai tahun ini)');
        }
    }

    public function index(): void
    {
        header('Content-Type: application/json');

        if (isset($_GET['id'])) {
            $id = $this->validId($_GET['id']);
            if ($id === null) {
                $this->fail(400, 'ID harus berupa angka positif');
                return;
            }

            $data = $this->model->find($id);
            if (!$data) {
                $this->fail(404, 'Data tidak ditemukan');
                return;
            }

            echo json_encode(['success' => true, 'data' => $data]);
            return;
        }

        echo json_encode(['success' => true, 'data' => $this->model->getAll()]);
    }

    public function store(): void
    {
        header('Content-Type: application/json');

        $body = $this->readJson();
        if ($body === null) {
            $this->fail(400, 'Body request harus berupa JSON yang valid');
            return;
        }

        try {
            $data = $this->pickFields($body, self::RULES);
            $this->validateTahun($data);
            $id = $this->model->create($data);
        } catch (InvalidArgumentException $e) {
            $this->fail(400, $e->getMessage());
            return;
        }

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data'    => ['id' => (int) $id],
        ]);
    }

    public function update($id): void
    {
        header('Content-Type: application/json');

        $id = $this->validId($id);
        if ($id === null) {
            $this->fail(400, 'ID wajib diisi dan harus berupa angka positif');
            return;
        }

        $body = $this->readJson();
        if ($body === null) {
            $this->fail(400, 'Body request harus berupa JSON yang valid');
            return;
        }

        if (!$this->model->find($id)) {
            $this->fail(404, 'Data tidak ditemukan');
            return;
        }

        try {
            $data = $this->pickFields($body, self::RULES);
            $this->validateTahun($data);
            $this->model->update($id, $data);
        } catch (InvalidArgumentException $e) {
            $this->fail(400, $e->getMessage());
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data'    => $this->model->find($id),
        ]);
    }

    public function destroy($id): void
    {
        header('Content-Type: application/json');

        $id = $this->validId($id);
        if ($id === null) {
            $this->fail(400, 'ID wajib diisi dan harus berupa angka positif');
            return;
        }

        if (!$this->model->find($id)) {
            $this->fail(404, 'Data tidak ditemukan');
            return;
        }

        $this->model->delete($id);
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
