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

            <meta
                name="viewport"
                content="width=device-width, initial-scale=1"
            >

            <title>SI Akademik</title>

            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet"
            >
        </head>

        <body class="bg-light">

            <nav class="navbar navbar-dark bg-primary shadow-sm">

                <div class="container">

                    <span class="navbar-brand fw-bold">
                        🎓 SI Akademik
                    </span>

                    <a
                        href="' . '/SI-AKADEMIK_BKPMPT6/public/login' . '"
                        class="btn btn-light btn-sm"
                    >
                        🔐 Login
                    </a>

                </div>

            </nav>

            <div class="container py-5">

                <div class="card border-0 shadow">

                    <div class="card-body text-center p-5">

                        <div class="display-4 mb-3">
                            🎓
                        </div>

                        <h1 class="display-5 fw-bold text-primary">
                            Selamat Datang
                        </h1>

                        <p class="lead text-secondary">
                            Sistem Informasi Akademik
                        </p>

                        <hr class="my-4">

                        <p class="text-muted">
                            Silakan login untuk mengakses
                            halaman akademik.
                        </p>

                        <a
                            href="' . '/SI-AKADEMIK_BKPMPT6/public/login' . '"
                            class="btn btn-primary px-4"
                        >
                            🔐 Login
                        </a>

                    </div>

                </div>

            </div>

        </body>

        </html>
        ';
    }

    public function dashboard()
    {
        $username = $_SESSION['user'] ?? 'Admin';

        echo '
        <!DOCTYPE html>
        <html lang="id">

        <head>

            <meta charset="UTF-8">

            <meta
                name="viewport"
                content="width=device-width, initial-scale=1"
            >

            <title>Dashboard - SI Akademik</title>

            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet"
            >

        </head>

        <body class="bg-light">

            <nav class="navbar navbar-dark bg-primary shadow-sm">

                <div class="container">

                    <a
                        href="/SI-AKADEMIK_BKPMPT6/public/dashboard"
                        class="navbar-brand fw-bold text-white text-decoration-none"
                    >
                        🎓 SI Akademik
                    </a>

                    <a
                        href="/SI-AKADEMIK_BKPMPT6/public/logout"
                        class="btn btn-light btn-sm"
                    >
                        🚪 Logout
                    </a>

                </div>

            </nav>

            <div class="container py-5">

                <div class="card border-0 shadow">

                    <div class="card-body p-5 text-center">

                        <div class="display-5 mb-3">
                            👋
                        </div>

                        <h1 class="fw-bold text-primary">
                            Selamat datang, ' . htmlspecialchars($username) . '
                        </h1>

                        <p class="text-muted">
                            Anda berhasil login ke Sistem Informasi Akademik.
                        </p>

                        <hr class="my-4">

                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                            <a
                                href="/SI-AKADEMIK_BKPMPT6/public/mahasiswa"
                                class="btn btn-primary"
                            >
                                📚 Data Mahasiswa
                            </a>

                            <a
                                href="/SI-AKADEMIK_BKPMPT6/public/mahasiswa/create"
                                class="btn btn-outline-primary"
                            >
                                ➕ Tambah Mahasiswa
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </body>

        </html>
        ';
    }
}