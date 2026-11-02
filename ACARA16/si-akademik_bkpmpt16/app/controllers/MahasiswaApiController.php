<?php

/**
 * Controller RESTful API Mahasiswa.
 * Tugasnya: membaca request (method, id, body), memanggil Service,
 * lalu mengirim response JSON dengan HTTP status code yang sesuai.
 */
class MahasiswaApiController
{
    private MahasiswaApiService $service;
    private Request $request;

    public function __construct(MahasiswaApiService $service, Request $request)
    {
        $this->service = $service;
        $this->request = $request;
    }

    public function handle(?string $rawId): void
    {
        try {
            $this->dispatch($rawId);
        } catch (ApiException $e) {
            Response::error($e->getMessage(), $e->getStatusCode(), $e->getErrors());
        } catch (PDOException $e) {
            error_log('[API Mahasiswa] ' . $e->getMessage());
            Response::error('Terjadi kesalahan pada server/database', 500);
        } catch (Throwable $e) {
            error_log('[API Mahasiswa] ' . $e->getMessage());
            Response::error('Terjadi kesalahan pada server', 500);
        }
    }

    private function dispatch(?string $rawId): void
    {
        $method = $this->request->method();
        $id = $this->parseId($rawId);

        switch ($method) {
            case 'GET':
                $id === null ? $this->index() : $this->show($id);
                return;

            case 'POST':
                if ($id !== null) {
                    throw new ApiException('POST tidak boleh menyertakan ID pada URL', 405);
                }
                $this->store();
                return;

            case 'PUT':
                $this->update($this->requireId($id));
                return;

            case 'DELETE':
                $this->destroy($this->requireId($id));
                return;
        }

        header('Allow: GET, POST, PUT, DELETE');
        throw new ApiException('Method tidak diizinkan', 405);
    }

    /** GET /api/mahasiswa */
    private function index(): void
    {
        $data = $this->service->getAll();

        Response::success('Data mahasiswa berhasil diambil', $data, 200);
    }

    /** GET /api/mahasiswa/{id} */
    private function show(int $id): void
    {
        Response::success('Data mahasiswa berhasil diambil', $this->service->getById($id), 200);
    }

    /** POST /api/mahasiswa */
    private function store(): void
    {
        $data = $this->service->create($this->request->json());

        Response::success('Data mahasiswa berhasil ditambahkan', $data, 201);
    }

    /** PUT /api/mahasiswa/{id} */
    private function update(int $id): void
    {
        $data = $this->service->update($id, $this->request->json());

        Response::success('Data mahasiswa berhasil diubah', $data, 200);
    }

    /** DELETE /api/mahasiswa/{id} */
    private function destroy(int $id): void
    {
        $this->service->delete($id);

        Response::success('Data mahasiswa berhasil dihapus', null, 200);
    }

    private function parseId(?string $rawId): ?int
    {
        if ($rawId === null || $rawId === '') {
            return null;
        }

        $id = filter_var($rawId, FILTER_VALIDATE_INT);

        if ($id === false || $id < 1) {
            throw new ApiException('ID harus berupa angka bulat positif', 400);
        }

        return $id;
    }

    private function requireId(?int $id): int
    {
        if ($id === null) {
            throw new ApiException('ID mahasiswa wajib disertakan pada URL, contoh: /api/mahasiswa/1', 400);
        }

        return $id;
    }
}
