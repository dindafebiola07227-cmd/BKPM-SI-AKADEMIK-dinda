<?php

/**
 * Kelas induk (abstract) untuk semua Controller.
 * - Kontrak: setiap Controller wajib punya index, store, update, destroy,
 *   sehingga Router dapat memanggilnya dengan cara yang sama (polimorfisme).
 * - Berisi helper kecil yang dipakai bersama agar tidak ditulis berulang.
 */
abstract class BaseController
{
    abstract public function index(): void;
    abstract public function store(): void;
    abstract public function update($id): void;
    abstract public function destroy($id): void;

    // Membaca body request berformat JSON. Mengembalikan null jika tidak valid.
    protected function readJson(): ?array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : null;
    }

    // ID valid = string angka bulat positif. Selain itu mengembalikan null.
    protected function validId($id): ?int
    {
        if (is_string($id) && ctype_digit($id) && (int) $id > 0) {
            return (int) $id;
        }
        return null;
    }

    // Mengambil field wajib dari body, membuang spasi di tepi, dan memeriksa panjang maksimal.
    // $rules berbentuk ['nama_field' => panjang_maksimal].
    protected function pickFields(array $data, array $rules): array
    {
        $result = [];

        foreach ($rules as $field => $maxLength) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                throw new InvalidArgumentException('Field ' . implode(', ', array_keys($rules)) . ' wajib diisi');
            }

            $value = trim((string) $data[$field]);
            if (mb_strlen($value) > $maxLength) {
                throw new InvalidArgumentException("Field {$field} maksimal {$maxLength} karakter");
            }

            $result[$field] = $value;
        }

        return $result;
    }

    // Response error sederhana (akan dirapikan menjadi class Response pada acara 18).
    protected function fail(int $status, string $message): void
    {
        http_response_code($status);
        echo json_encode(['success' => false, 'message' => $message]);
    }
}
