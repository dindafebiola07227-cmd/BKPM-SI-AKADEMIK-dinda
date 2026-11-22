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
        return $this->db->query('SELECT * FROM mahasiswa ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
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

        // Acara 20 (Langkah 5): kegagalan database ditangkap di lapisan Model
        try {
            // Acara 26 (Langkah 3): kolom foto ikut disimpan (null jika tidak ada berkas diunggah)
            $stmt = $this->db->prepare(
                'INSERT INTO mahasiswa (nim, nama, prodi, foto) VALUES (:nim, :nama, :prodi, :foto)'
            );
            $stmt->execute([
                ':nim' => $data['nim'], ':nama' => $data['nama'],
                ':prodi' => $data['prodi'], ':foto' => $data['foto'] ?? null,
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

        try {
            // Acara 26: kolom foto hanya diubah bila ada foto baru; tanpa foto baru, foto lama dipertahankan
            $sql    = 'UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi = :prodi';
            $params = [':nim' => $data['nim'], ':nama' => $data['nama'], ':prodi' => $data['prodi'], ':id' => $id];

            if (!empty($data['foto'])) {
                $sql .= ', foto = :foto';
                $params[':foto'] = $data['foto'];
            }

            $stmt = $this->db->prepare($sql . ' WHERE id = :id');
            $stmt->execute($params);
        } catch (PDOException $e) {
            $this->handleDbError($e);
        }
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
