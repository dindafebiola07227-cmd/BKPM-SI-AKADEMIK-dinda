<?php

class Response
{
    /**
     * Mengirim response JSON dengan format (envelope) yang seragam lalu menghentikan eksekusi.
     *
     * @param int    $httpCode kode status HTTP (200, 201, 400, 404, 405, 409, 422, 500, ...)
     * @param bool   $success  true jika request berhasil
     * @param string $message  pesan untuk client
     * @param mixed  $data     data hasil (null jika tidak ada)
     * @param array  $errors   (Tugas 1) daftar pesan kesalahan validasi per field,
     *                         contoh: ['nim' => ['Format NIM tidak valid']]
     */
    public static function json(int $httpCode, bool $success, string $message, $data = null, array $errors = []): void
    {
        http_response_code($httpCode);
        header('Content-Type: application/json');

        $payload = [
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ];

        // Field errors hanya muncul bila ada kesalahan validasi
        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        $payload['timestamp'] = date('c');

        // Tugas 2: pretty print hanya pada mode pengembangan
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $flags |= JSON_PRETTY_PRINT;
        }

        echo json_encode($payload, $flags);
        exit;
    }
}
