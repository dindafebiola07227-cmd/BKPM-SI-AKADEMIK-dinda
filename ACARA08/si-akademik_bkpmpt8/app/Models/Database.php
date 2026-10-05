<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {

            $host = '127.0.0.1';
            $dbname = 'si_akademik8';
            $username = 'root';
            $password = '';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

            try {

                self::$instance = new PDO(
                    $dsn,
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );

            } catch (PDOException $e) {

                die("Koneksi database gagal: " . $e->getMessage());

            }
        }

        return self::$instance;
    }
}