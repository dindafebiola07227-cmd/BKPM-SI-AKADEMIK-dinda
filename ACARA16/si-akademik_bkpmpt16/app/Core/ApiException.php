<?php

/**
 * Exception khusus API.
 * Membawa HTTP status code + pesan + (opsional) detail error per field,
 * sehingga Controller cukup melempar exception dan Response yang menjawab.
 */
class ApiException extends Exception
{
    private int $statusCode;
    private array $errors;

    public function __construct(string $message, int $statusCode = 400, array $errors = [])
    {
        parent::__construct($message);

        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
