<?php

/**
 * Membungkus request HTTP: method dan body JSON.
 */
class Request
{
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Membaca body request sebagai JSON object.
     *
     * @throws ApiException 400 jika body kosong / bukan JSON / bukan object.
     */
    public function json(): array
    {
        $raw = file_get_contents('php://input');

        if ($raw === false || trim($raw) === '') {
            throw new ApiException('Body request kosong. Kirim data dalam format JSON', 400);
        }

        try {
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new ApiException('Format JSON tidak valid', 400);
        }

        if (!is_array($data) || array_is_list($data)) {
            throw new ApiException('Body request harus berupa object JSON', 400);
        }

        return $data;
    }
}
