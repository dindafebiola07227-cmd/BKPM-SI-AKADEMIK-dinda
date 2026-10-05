<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        echo '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>SI Akademik</title>

            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet"
            >
        </head>

        <body class="bg-light">

            <nav class="navbar navbar-dark bg-primary shadow-sm">
                <div class="container">
                    <span class="navbar-brand mb-0 h1">
                        🎓 SI Akademik
                    </span>
                </div>
            </nav>

            <div class="container py-5">

                <div class="card border-0 shadow">

                    <div class="card-body text-center p-5">

                        <h1 class="display-5 fw-bold text-primary">
                            Selamat Datang
                        </h1>

                        <p class="lead text-secondary">
                            Sistem Informasi Akademik
                        </p>

                        <hr class="my-4">

                        <p class="text-muted">
                            Selamat datang di halaman utama
                            Sistem Informasi Akademik.
                        </p>

                        <a href="mahasiswa"
                           class="btn btn-primary px-4">
                            📚 Daftar Mahasiswa
                        </a>

                        <a href="mahasiswa/create"
                           class="btn btn-outline-primary px-4 ms-2">
                            ➕ Tambah Mahasiswa
                        </a>

                    </div>

                </div>

            </div>

        </body>
        </html>
        ';
    }
}