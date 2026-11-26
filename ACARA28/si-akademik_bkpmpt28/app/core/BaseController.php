<?php

/**
 * Kelas induk (abstract) untuk semua Controller.
 * - Kontrak: setiap Controller wajib punya index, store, update, destroy,
 *   sehingga Router dapat memanggilnya dengan cara yang sama (polimorfisme).
 * - Berisi helper kecil yang dipakai bersama agar tidak ditulis berulang.
 * - Seluruh response dikirim lewat Response::json() (lihat app/core/Response.php).
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

    // Acara 26: membaca body berupa JSON ATAU multipart/form-data (upload file).
    // PHP hanya mengisi $_POST/$_FILES untuk request POST bermultipart, sehingga Router
    // mengubah POST + _method=PUT menjadi PUT agar update dengan foto dapat dikirim.
    protected function readBody(): ?array
    {
        $type = strtolower($_SERVER['CONTENT_TYPE'] ?? '');
        if (strpos($type, 'multipart/form-data') !== false || strpos($type, 'application/x-www-form-urlencoded') !== false) {
            return $_POST;
        }
        return $this->readJson();
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
    // - Ada field kosong/tidak ada   -> ValidationException 400
    // - Ada field terlalu panjang    -> ValidationException 422
    protected function pickFields(array $data, array $rules): array
    {
        $result  = [];
        $missing = [];
        $tooLong = [];

        foreach ($rules as $field => $maxLength) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                $missing[$field] = ["Field {$field} wajib diisi"];
                continue;
            }

            $value = trim((string) $data[$field]);
            if (mb_strlen($value) > $maxLength) {
                $tooLong[$field] = ["Field {$field} maksimal {$maxLength} karakter"];
                continue;
            }

            $result[$field] = $value;
        }

        if ($missing !== []) {
            $names = array_keys($rules);
            $last  = array_pop($names);
            $label = $names ? implode(', ', $names) . ', dan ' . $last : $last;
            throw new ValidationException("Field {$label} wajib diisi", $missing, 400);
        }

        if ($tooLong !== []) {
            throw new ValidationException('Data tidak valid', $tooLong, 422);
        }

        return $result;
    }
}
