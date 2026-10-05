<?php

namespace App\Controllers;

class AuthController
{
    public function loginForm()
    {
        echo '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>Login - SI Akademik</title>

            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet"
            >
        </head>

        <body class="bg-light">

            <div class="container py-5">

                <div class="row justify-content-center">

                    <div class="col-md-5">

                        <div class="card border-0 shadow">

                            <div class="card-body p-4">

                                <div class="text-center mb-4">
                                    <div class="display-5">🎓</div>

                                    <h1 class="h3 fw-bold text-primary">
                                        Login SI Akademik
                                    </h1>

                                    <p class="text-muted">
                                        Silakan login untuk melanjutkan
                                    </p>
                                </div>

                                <form method="POST"
                                      action="/SI-AKADEMIK_BKPMPT7/public/login">

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Username
                                        </label>

                                        <input
                                            type="text"
                                            name="username"
                                            class="form-control"
                                            placeholder="Masukkan username"
                                            required
                                        >
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Password
                                        </label>

                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control"
                                            placeholder="Masukkan password"
                                            required
                                        >
                                    </div>

                                    <button
                                        type="submit"
                                        class="btn btn-primary w-100"
                                    >
                                        Login
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </body>
        </html>
        ';
    }

    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === 'admin123') {

            $_SESSION['user'] = $username;

            header(
                'Location: /SI-AKADEMIK_BKPMPT6/public/dashboard'
            );

            exit;
        }

        echo '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">

            <meta name="viewport"
                  content="width=device-width, initial-scale=1">

            <title>Login Gagal</title>

            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet"
            >
        </head>

        <body class="bg-light">

            <div class="container py-5">

                <div class="row justify-content-center">

                    <div class="col-md-5">

                        <div class="alert alert-danger shadow">

                            <h4 class="alert-heading">
                                Login Gagal
                            </h4>

                            <p>
                                Username atau password salah.
                            </p>

                            <hr>

                            <a
                                href="/SI-AKADEMIK_BKPMPT6/public/login"
                                class="btn btn-danger"
                            >
                                Kembali ke Login
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </body>
        </html>
        ';
    }

    public function logout()
    {
        unset($_SESSION['user']);

        header(
            'Location: /SI-AKADEMIK_BKPMPT6/public/login'
        );

        exit;
    }
}