<?php

class Mahasiswa
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT m.*, p.nama AS prodi
             FROM mahasiswa m
             LEFT JOIN prodi p ON p.id = m.prodi_id
             ORDER BY m.id DESC'
        )->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare(
            'SELECT m.*, p.nama AS prodi
             FROM mahasiswa m
             LEFT JOIN prodi p ON p.id = m.prodi_id
             WHERE m.id = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Validasi: apakah NIM sudah dipakai? ($exceptId dipakai saat update agar data sendiri tidak dihitung)
    public function existsNim(string $nim, ?int $exceptId = null): bool
    {
        $sql    = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';
        $params = [':nim' => $nim];

        if ($exceptId !== null) {
            $sql .= ' AND id <> :id';
            $params[':id'] = $exceptId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): string
    {
        if ($this->existsNim($data['nim'])) {
            throw new DomainException('NIM sudah terdaftar');
        }

        $prodiId = $this->resolveProdiId($data['prodi']);

        // Acara 20 (Langkah 5): kegagalan database ditangkap di lapisan Model
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, foto)
                 VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :foto)'
            );
            // Acara 26 (Langkah 3): foto berisi nama berkas hasil upload, atau NULL bila tidak ada
            $stmt->execute([
                ':nim' => $data['nim'],
                ':nama' => $data['nama'],
                ':email' => $data['nim'] . '@example.com',
                ':prodi_id' => $prodiId,
                ':angkatan' => (int) date('Y'),
                ':foto' => $data['foto'] ?? null,
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->handleDbError($e);
        }
    }

    public function update(int $id, array $data): void
    {
        if ($this->existsNim($data['nim'], $id)) {
            throw new DomainException('NIM sudah terdaftar');
        }

        $prodiId = $this->resolveProdiId($data['prodi']);

        try {
            // Kolom foto hanya diubah bila ada foto baru; tanpa foto baru, foto lama dipertahankan
            $setFoto = !empty($data['foto']) ? ', foto = :foto' : '';
            $stmt = $this->db->prepare(
                "UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi_id = :prodi_id{$setFoto} WHERE id = :id"
            );
            $params = [
                ':nim' => $data['nim'],
                ':nama' => $data['nama'],
                ':prodi_id' => $prodiId,
                ':id' => $id,
            ];
            if ($setFoto !== '') {
                $params[':foto'] = $data['foto'];
            }
            $stmt->execute($params);
        } catch (PDOException $e) {
            $this->handleDbError($e);
        }
    }

    private function resolveProdiId(string $prodi): int
    {
        $nilai = trim($prodi);
        $stmt = ctype_digit($nilai)
            ? $this->db->prepare('SELECT id FROM prodi WHERE id = :nilai')
            : $this->db->prepare('SELECT id FROM prodi WHERE nama = :nilai OR kode = :nilai LIMIT 1');
        $stmt->execute([':nilai' => $nilai]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            throw new InvalidArgumentException('Program studi tidak ditemukan');
        }
        return (int) $id;
    }

    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            $this->handleDbError($e);
        }
    }

    // Penanganan error database yang dipakai bersama oleh create(), update(), delete()
    private function handleDbError(PDOException $e): void
    {
        // SQLSTATE 23000 = pelanggaran constraint (mis. NIM kembar yang lolos dari pemeriksaan existsNim
        // karena dua request masuk bersamaan). Dilempar sebagai DomainException agar Controller membalas 409.
        if ($e->getCode() === '23000') {
            throw new DomainException('NIM sudah terdaftar');
        }

        // Detail asli hanya masuk log server; client cukup menerima pesan umum (tidak membocorkan struktur database)
        error_log('[Mahasiswa] ' . $e->getMessage());
        Response::json(500, false, 'Terjadi kesalahan pada server');
    }
}
