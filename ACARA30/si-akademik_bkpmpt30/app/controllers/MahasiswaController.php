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

    // Langkah 3: validasi format NIM (harus 9 digit) -> kode 422
    private function validateFormat(array $data): void
    {
        if (strlen($data['nim']) !== 9 || !ctype_digit($data['nim'])) {
            throw new ValidationException(
                'Format NIM tidak valid, harus 9 digit',
                ['nim' => ['NIM harus terdiri dari 9 digit']],
                422
            );
        }
    }

    // ------------------------------------------------------------------
    // Acara 26 - Langkah 4: upload foto
    // ------------------------------------------------------------------
    private const FOTO_MAKS_BYTE = 2 * 1024 * 1024; // 2 MB

    private function dirUpload(): string
    {
        return __DIR__ . '/../../public/uploads/';
    }

    // Memeriksa lalu menyimpan foto dari $_FILES['foto'].
    // Mengembalikan nama berkas baru, atau null bila client tidak mengirim foto.
    private function simpanFoto(): ?string
    {
        if (empty($_FILES['foto']) || ($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['foto'];

        // Berkas melebihi batas php.ini (upload_max_filesize) juga dianggap terlalu besar
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            throw new ValidationException('Ukuran berkas maksimal 2MB', ['foto' => ['Ukuran berkas maksimal 2MB']], 422);
        }
        if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            throw new ValidationException('Upload berkas gagal', ['foto' => ['Upload berkas gagal']], 422);
        }

        // Jangan percaya ekstensi/type dari client: periksa signature berkas yang sesungguhnya
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $tipe     = function_exists('exif_imagetype') ? @exif_imagetype($file['tmp_name']) : (@getimagesize($file['tmp_name'])[2] ?? false);

        if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'], true) || !in_array($tipe, [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            throw new ValidationException('Berkas harus berupa gambar JPG/PNG yang valid', ['foto' => ['Berkas harus berupa gambar JPG/PNG yang valid']], 422);
        }
        if ($file['size'] > self::FOTO_MAKS_BYTE) {
            throw new ValidationException('Ukuran berkas maksimal 2MB', ['foto' => ['Ukuran berkas maksimal 2MB']], 422);
        }

        // Nama unik agar berkas pengguna berbeda tidak saling menimpa; ekstensi mengikuti isi berkas
        $namaBaru = uniqid('foto_', true) . '.' . ($tipe === IMAGETYPE_PNG ? 'png' : 'jpg');

        if (!move_uploaded_file($file['tmp_name'], $this->dirUpload() . $namaBaru)) {
            error_log('[Mahasiswa] gagal memindahkan berkas upload ke public/uploads');
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }

        return $namaBaru;
    }

    // Menghapus berkas foto dari public/uploads (basename mencegah path traversal)
    private function hapusFoto(?string $nama): void
    {
        if ($nama === null || $nama === '') {
            return;
        }
        $path = $this->dirUpload() . basename($nama);
        if (is_file($path)) {
            unlink($path);
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

        $body = $this->readBody();
        if ($body === null) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }

        $foto = null;
        try {
            $data = $this->pickFields($body, self::RULES);
            $this->validateFormat($data);

            $foto = $this->simpanFoto();   // null bila tidak ada berkas
            $data['foto'] = $foto;
            $id = $this->model->create($data);
        } catch (ValidationException $e) {
            $this->hapusFoto($foto);
            Response::json($e->getCode(), false, $e->getMessage(), null, $e->getErrors());
        } catch (DomainException $e) {
            $this->hapusFoto($foto);       // data gagal disimpan -> jangan tinggalkan berkas yatim
            Response::json(409, false, $e->getMessage(), null, ['nim' => [$e->getMessage()]]);
        } catch (InvalidArgumentException $e) {
            $this->hapusFoto($foto);
            Response::json(422, false, $e->getMessage(), null, ['prodi' => [$e->getMessage()]]);
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

        $body = $this->readBody();
        if ($body === null) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }

        $lama = $this->model->find($id);
        if (!$lama) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $fotoBaru = null;
        try {
            $data = $this->pickFields($body, self::RULES);
            $this->validateFormat($data);

            $fotoBaru = $this->simpanFoto();
            if ($fotoBaru !== null) {
                $data['foto'] = $fotoBaru;
            }
            $this->model->update($id, $data);
        } catch (ValidationException $e) {
            $this->hapusFoto($fotoBaru);
            Response::json($e->getCode(), false, $e->getMessage(), null, $e->getErrors());
        } catch (DomainException $e) {
            $this->hapusFoto($fotoBaru);
            Response::json(409, false, $e->getMessage(), null, ['nim' => [$e->getMessage()]]);
        } catch (InvalidArgumentException $e) {
            $this->hapusFoto($fotoBaru);
            Response::json(422, false, $e->getMessage(), null, ['prodi' => [$e->getMessage()]]);
        }

        // Tugas mandiri 1: setelah data tersimpan dengan foto baru, foto lama dihapus dengan unlink()
        if ($fotoBaru !== null) {
            $this->hapusFoto($lama['foto'] ?? null);
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

        $lama = $this->model->find($id);
        if (!$lama) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->delete($id);
        $this->hapusFoto($lama['foto'] ?? null); // data dihapus -> fotonya ikut dibersihkan
        Response::json(200, true, 'Data berhasil dihapus');
    }
}
