<?php

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Acara 30: kredensial dibaca dari berkas konfigurasi terpisah (lihat Secrets.php);
            // nilai bawaan di bawah hanya untuk XAMPP lokal.
            $host    = Secrets::get('db_host', 'localhost');
            $db      = Secrets::get('db_name', 'db_workshopku');
            $charset = 'utf8mb4';
            $dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$connection = new PDO($dsn, Secrets::get('db_user', 'root'), Secrets::get('db_pass', ''), $options);
            } catch (PDOException $e) {
                Response::json(500, false, 'Koneksi database gagal');
            }
        }

        return self::$connection;
    }
}
