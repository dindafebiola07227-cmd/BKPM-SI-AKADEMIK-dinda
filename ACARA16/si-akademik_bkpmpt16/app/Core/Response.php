<?php

/**
 * Helper untuk mengirim response JSON beserta HTTP status code.
 */
class Response
{
    public static function json(array $body, int $status = 200, array $headers = []): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        foreach ($headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo json_encode(
            $body,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );
    }

    public static function success(string $message, mixed $data = null, int $status = 200): void
    {
        $body = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $body['data'] = $data;
        }

        self::json($body, $status);
    }

    public static function error(string $message, int $status, array $errors = [], array $headers = []): void
    {
        $body = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $body['errors'] = $errors;
        }

        self::json($body, $status, $headers);
    }
}
