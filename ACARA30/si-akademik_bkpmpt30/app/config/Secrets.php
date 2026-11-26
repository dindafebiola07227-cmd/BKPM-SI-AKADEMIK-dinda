<?php

/**
 * Acara 30: pemisahan kredensial dari kode sumber.
 * Nilai rahasia (kata sandi database, kunci JWT) dibaca dari berkas konfigurasi terpisah
 * yang TIDAK ikut Git (lihat .gitignore). Urutan pencarian berkas:
 *   1) <folder induk proyek>/si-akademik_config.php  -> di luar public_html (paling aman di hosting)
 *   2) app/config/config.local.php                   -> alternatif sederhana, dilindungi app/.htaccess
 * Bila tidak ada berkas, dipakai nilai bawaan XAMPP (hanya untuk pengembangan).
 * Format berkas: lihat app/config/config.local.example.php
 */
class Secrets
{
    private static ?array $config = null;

    public static function get(string $key, $default = null)
    {
        if (self::$config === null) {
            self::$config = [];
            $kandidat = [
                dirname(__DIR__, 3) . '/si-akademik_config.php',
                __DIR__ . '/config.local.php',
            ];
            foreach ($kandidat as $berkas) {
                if (is_file($berkas)) {
                    $isi = require $berkas;
                    if (is_array($isi)) {
                        self::$config = $isi;
                        break;
                    }
                }
            }
        }

        return self::$config[$key] ?? $default;
    }
}
