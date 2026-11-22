<?php

class MahasiswaController extends BaseController
{
    private Mahasiswa $model;

    // Aturan field: nama field => panjang maksimal (sesuai kolom pada tabel)
    private const RULES = ['nim' => 15, 'nama' => 100, 'prodi' => 50];

    // Acara 26: folder tujuan upload (di dalam public/ agar foto bisa dibuka langsung oleh browser)
    private const UPLOAD_DIR  = __DIR__ . '/../../public/uploads/';
    private const MAX_UKURAN  = 2 * 1024 * 1024; // 2 MB
    private const EKSTENSI_OK = ['jpg', 'jpeg', 'png'];

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    // Acara 26 (Langkah 5): request multipart/form-data (FormData) dibaca dari $_POST, selain itu dari JSON
    private function readInput(): ?array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'multipart/form-data') === 0) {
            return $_POST;
        }
        return $this->readJson();
    }

    /**
     * Acara 26 (Langkah 4): validasi dan simpan foto.
     * - Tidak ada berkas dikirim          -> null (foto bersifat opsional)
     * - Berkas tidak valid / terlalu besar -> respons 422 dan proses berhenti
     * - Berkas valid                       -> dipindah ke public/uploads/, mengembalikan nama barunya
     */
    private function handleUpload(): ?string
    {
        // Input file kosong tetap muncul di $_FILES dengan error UPLOAD_ERR_NO_FILE, jadi !empty($_FILES) saja tidak cukup
        if (!isset($_FILES['foto']) || ($_FILES['foto']['error'] ?? null) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['foto'];
        if (!is_array($file['error'])) {
            if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
                Response::json(422, false, 'Ukuran berkas maksimal 2MB');
            }
            if ($file['error'] !== UPLOAD_ERR_OK) {
                Response::json(422, false, 'Berkas gagal diunggah, silakan coba lagi');
            }
        } else {
            Response::json(422, false, 'Hanya satu berkas foto yang diperbolehkan');
        }

        // Validasi 1: ekstensi + isi (signature) berkas sebenarnya. exif_imagetype() membaca byte awal berkas,
        // sehingga skrip .php yang hanya "diganti nama" menjadi .jpg tetap ditolak.
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $tipeAsli = is_uploaded_file($file['tmp_name']) ? @exif_imagetype($file['tmp_name']) : false;

        if (!in_array($ekstensi, self::EKSTENSI_OK, true) || !in_array($tipeAsli, [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            Response::json(422, false, 'Berkas harus berupa gambar JPG/PNG yang valid');
        }

        // Validasi 2: ukuran maksimal 2 MB
        if ($file['size'] > self::MAX_UKURAN) {
            Response::json(422, false, 'Ukuran berkas maksimal 2MB');
        }

        // Nama unik agar foto mahasiswa berbeda tidak saling menimpa (nama asli dari client tidak dipakai)
        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0755, true);
        }
        $namaBaru = uniqid('foto_', true) . '.' . $ekstensi;

        if (!move_uploaded_file($file['tmp_name'], self::UPLOAD_DIR . $namaBaru)) {
            error_log('[Mahasiswa] move_uploaded_file gagal untuk ' . $namaBaru);
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }

        return $namaBaru;
    }

    // Acara 26 (Tugas Mandiri 1): hapus berkas dari disk. basename() mencegah path traversal bila nilai kolom diubah orang lain.
    private function hapusBerkasFoto(?string $nama): void
    {
        if ($nama === null || $nama === '') {
            return;
        }
        $path = self::UPLOAD_DIR . basename($nama);
        if (is_file($path)) {
            unlink($path);
        }
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

        $body = $this->readInput(); // Acara 26: bisa JSON (tanpa foto) atau multipart (dengan foto)
        if ($body === null) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }

        $namaFoto = null;
        try {
            $data = $this->pickFields($body, self::RULES);
            $this->validateFormat($data);

            // Acara 26 (Langkah 4): data teks sudah valid, baru berkas diproses
            $data['foto'] = $namaFoto = $this->handleUpload();
            $id = $this->model->create($data);
        } catch (ValidationException $e) {
            $this->hapusBerkasFoto($namaFoto);
            Response::json($e->getCode(), false, $e->getMessage(), null, $e->getErrors());
        } catch (DomainException $e) {
            $this->hapusBerkasFoto($namaFoto); // NIM duplikat: jangan tinggalkan berkas yatim di disk
            Response::json(422, false, $e->getMessage(), null, ['nim' => [$e->getMessage()]]); // Perbaikan Acara 23 (TC03/TC17): NIM duplikat = 422, bukan 409
        }

        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => (int) $id, 'foto' => $namaFoto]);
    }

    public function update($id): void
    {
        Auth::requireAdmin(); // Acara 25 (Langkah 2)

        $id = $this->validId($id);
        if ($id === null) {
            Response::json(400, false, 'ID wajib diisi dan harus berupa angka positif');
        }

        $body = $this->readInput();
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

            $data['foto'] = $fotoBaru = $this->handleUpload();
            $this->model->update($id, $data);
        } catch (ValidationException $e) {
            $this->hapusBerkasFoto($fotoBaru);
            Response::json($e->getCode(), false, $e->getMessage(), null, $e->getErrors());
        } catch (DomainException $e) {
            $this->hapusBerkasFoto($fotoBaru);
            Response::json(422, false, $e->getMessage(), null, ['nim' => [$e->getMessage()]]); // Perbaikan Acara 23 (TC03/TC17): NIM duplikat = 422, bukan 409
        }

        // Acara 26 (Tugas Mandiri 1): foto lama dihapus dengan unlink() HANYA setelah UPDATE berhasil
        // dan hanya bila memang ada foto baru; kalau update gagal, foto lama tetap aman.
        if ($fotoBaru !== null) {
            $this->hapusBerkasFoto($lama['foto'] ?? null);
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

        $data = $this->model->find($id);
        if (!$data) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->delete($id);
        $this->hapusBerkasFoto($data['foto'] ?? null); // Acara 26: berkas foto ikut dibersihkan agar disk tidak menumpuk
        Response::json(200, true, 'Data berhasil dihapus');
    }
}
