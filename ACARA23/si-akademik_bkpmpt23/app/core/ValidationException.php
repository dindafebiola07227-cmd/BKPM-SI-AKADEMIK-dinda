<?php

/**
 * Dilempar saat data request tidak lolos validasi.
 * Membawa kode HTTP (400 atau 422) dan daftar pesan error per field.
 */
class ValidationException extends InvalidArgumentException
{
    private array $errors;

    public function __construct(string $message, array $errors = [], int $httpCode = 422)
    {
        parent::__construct($message, $httpCode);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
