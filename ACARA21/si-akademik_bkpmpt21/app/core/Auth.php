<?php

/**
 * Acara 20 - Langkah 1: autentikasi token (JWT HS256) secara native, tanpa Composer.
 * Token = header.payload.signature (masing-masing Base64URL), signature = HMAC-SHA256.
 */
class Auth
{
    const SECRET = 'kunci_rahasia_workshop_2026';

    // Tugas Praktik Mandiri 2: masa berlaku token diperpendek dari 3600 detik (1 jam) menjadi 300 detik (5 menit)
    const TOKEN_TTL = 300;

    // Base64 biasa -> Base64URL: '+' jadi '-', '/' jadi '_', dan padding '=' dibuang
    private static function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // Kebalikan base64url(): kembalikan '-' '_' dan padding, lalu decode (strict agar karakter asing ditolak)
    private static function base64urlDecode(string $data)
    {
        $padded = str_pad(strtr($data, '-_', '+/'), (int) (ceil(strlen($data) / 4) * 4), '=');
        return base64_decode($padded, true);
    }

    public static function generateToken(array $payload): string
    {
        $header = self::base64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));

        $payload['exp'] = time() + self::TOKEN_TTL;
        $payloadEnc     = self::base64url(json_encode($payload));

        $signature = self::base64url(hash_hmac('sha256', "$header.$payloadEnc", self::SECRET, true));

        return "$header.$payloadEnc.$signature";
    }

    // Mengembalikan isi payload (array) jika token sah dan belum kedaluwarsa, selain itu false
    public static function validateToken(string $token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        // 1) Cek tanda tangan; hash_equals() membandingkan dengan waktu konstan (aman dari timing attack)
        $valid = self::base64url(hash_hmac('sha256', "$header.$payload", self::SECRET, true));
        if (!hash_equals($valid, $signature)) {
            return false;
        }

        // 2) Cek isi payload dan masa berlaku
        $json = self::base64urlDecode($payload);
        $data = $json === false ? null : json_decode($json, true);
        if (!is_array($data) || !isset($data['exp']) || $data['exp'] < time()) {
            return false;
        }

        return $data;
    }

    // Membaca header Authorization (tanpa membedakan huruf besar/kecil nama header)
    private static function readAuthorizationHeader(): string
    {
        if (function_exists('getallheaders')) {
            foreach (getallheaders() as $name => $value) {
                if (strcasecmp($name, 'Authorization') === 0) {
                    return (string) $value;
                }
            }
        }

        // Cadangan bila getallheaders() tidak tersedia / header diteruskan lewat .htaccess
        return (string) ($_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
    }

    /**
     * Middleware autentikasi sederhana. Panggil di baris pertama method yang dilindungi.
     * Token tidak ada / tidak valid / kedaluwarsa -> langsung dibalas 401 dan proses dihentikan.
     * Jika sah, mengembalikan payload (id, role, exp).
     */
    public static function requireAuth(): array
    {
        $authHeader = self::readAuthorizationHeader();

        if (!preg_match('/^Bearer\s+(\S+)$/i', trim($authHeader), $m)) {
            header('WWW-Authenticate: Bearer');
            Response::json(401, false, 'Token tidak ditemukan');
        }

        $payload = self::validateToken($m[1]);
        if (!$payload) {
            header('WWW-Authenticate: Bearer error="invalid_token"');
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }

        return $payload;
    }
}
