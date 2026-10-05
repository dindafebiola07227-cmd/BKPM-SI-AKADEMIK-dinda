<?php

namespace App\Controllers;

class MahasiswaController
{
    public function index()
    {
        echo '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>Daftar Mahasiswa</title>

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

                    <a href="./"
                       class="btn btn-light btn-sm">
                        Beranda
                    </a>
                </div>
            </nav>

            <div class="container py-5">

                <div class="card border-0 shadow">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between
                                    align-items-center mb-4">

                            <div>
                                <h1 class="h2 fw-bold text-primary mb-1">
                                    📚 Daftar Mahasiswa
                                </h1>

                                <p class="text-muted mb-0">
                                    Data mahasiswa Sistem Informasi Akademik
                                </p>
                            </div>

                            <a href="create"
                               class="btn btn-primary">
                                ➕ Tambah Mahasiswa
                            </a>

                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered
                                          table-hover align-middle">

                                <thead class="table-primary">

                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Program Studi</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>
                                        <td>1</td>
                                        <td>2401001</td>
                                        <td>Dinda Febiola Rachmawati</td>
                                        <td>Teknik Informatika</td>
                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>2401002</td>
                                        <td>Linda Ratnadela Anggraini</td>
                                        <td>Sistem Informasi</td>
                                    </tr>

                                    <tr>
                                        <td>3</td>
                                        <td>2401003</td>
                                        <td>Sheryn Febrylia Hendrita</td>
                                        <td>Teknik Komputer</td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </body>
        </html>
        ';
    }

    public function create()
    {
        echo '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>Tambah Mahasiswa</title>

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

                    <a href="../"
                       class="btn btn-light btn-sm">
                        Beranda
                    </a>

                </div>
            </nav>

            <div class="container py-5">

                <div class="row justify-content-center">

                    <div class="col-md-7">

                        <div class="card border-0 shadow">

                            <div class="card-body p-4">

                                <h1 class="h2 fw-bold text-primary">
                                    ➕ Tambah Mahasiswa
                                </h1>

                                <p class="text-muted">
                                    Halaman form mahasiswa.
                                </p>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label">
                                        NIM
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           placeholder="Masukkan NIM">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Nama
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           placeholder="Masukkan nama">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Program Studi
                                    </label>

                                    <select class="form-select">

                                        <option selected>
                                            Pilih Program Studi
                                        </option>

                                        <option>
                                            Teknik Informatika
                                        </option>

                                        <option>
                                            Sistem Informasi
                                        </option>

                                        <option>
                                            Teknik Komputer
                                        </option>

                                    </select>
                                </div>

                                <button type="button"
                                        class="btn btn-primary">
                                    Simpan
                                </button>

                                <a href="../mahasiswa"
                                   class="btn btn-secondary">
                                    Kembali
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </body>
        </html>
        ';
    }

    public function show($id)
    {
        echo '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, initial-scale=1">

            <title>Detail Mahasiswa</title>

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

                <div class="row justify-content-center">

                    <div class="col-md-6">

                        <div class="card border-0 shadow">

                            <div class="card-body text-center p-5">

                                <div class="display-4 mb-3">
                                    👨‍🎓
                                </div>

                                <h1 class="h2 fw-bold text-primary">
                                    Detail Mahasiswa
                                </h1>

                                <p class="text-muted">
                                    Informasi berdasarkan parameter URL.
                                </p>

                                <div class="alert alert-primary mt-4">
                                    <strong>
                                        ID Mahasiswa:
                                    </strong>
                                    ' . htmlspecialchars($id) . '
                                </div>

                                <a href="../mahasiswa"
                                   class="btn btn-primary">
                                    ← Kembali ke Daftar
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </body>
        </html>
        ';
    }
}