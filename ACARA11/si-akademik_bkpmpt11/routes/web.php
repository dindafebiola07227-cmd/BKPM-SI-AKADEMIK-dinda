<?php

$routes = [

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */

    'GET' => [

        // Beranda
        '/' => ['HomeController', 'index'],

        // Login
        '/login' => ['AuthController', 'loginForm'],

        // Dashboard
        '/dashboard' => ['HomeController', 'dashboard'],


        /*
        |--------------------------------------------------------------------------
        | Mahasiswa
        |--------------------------------------------------------------------------
        */

        // Daftar mahasiswa
        '/mahasiswa' => ['MahasiswaController', 'index'],

        // Form tambah mahasiswa
        '/mahasiswa/create' => ['MahasiswaController', 'create'],

        // Detail mahasiswa
        '/mahasiswa/{id}' => ['MahasiswaController', 'show'],

        // Form edit mahasiswa
        '/mahasiswa/{id}/edit' => ['MahasiswaController', 'edit'],


        /*
        |--------------------------------------------------------------------------
        | Prodi
        |--------------------------------------------------------------------------
        */

        // Daftar prodi
        '/prodi' => ['ProdiController', 'index'],

        // Form tambah prodi
        '/prodi/create' => ['ProdiController', 'create'],

        // Detail prodi
        '/prodi/{id}' => ['ProdiController', 'show'],

        // Form edit prodi
        '/prodi/{id}/edit' => ['ProdiController', 'edit'],


        /*
        |--------------------------------------------------------------------------
        | Mata Kuliah
        |--------------------------------------------------------------------------
        */

        // Daftar mata kuliah
        '/matakuliah' => ['MatakuliahController', 'index'],

        // Form tambah mata kuliah
        '/matakuliah/create' => ['MatakuliahController', 'create'],

        // Detail mata kuliah
        '/matakuliah/{id}' => ['MatakuliahController', 'show'],

        // Form edit mata kuliah
        '/matakuliah/{id}/edit' => ['MatakuliahController', 'edit'],


        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        '/logout' => ['AuthController', 'logout'],
    ],


    /*
    |--------------------------------------------------------------------------
    | POST
    |--------------------------------------------------------------------------
    */

    'POST' => [

        // Proses login
        '/login' => ['AuthController', 'login'],


        /*
        |--------------------------------------------------------------------------
        | Mahasiswa
        |--------------------------------------------------------------------------
        */

        // Simpan mahasiswa baru
        '/mahasiswa' => ['MahasiswaController', 'store'],

        // Update mahasiswa
        '/mahasiswa/{id}/update' => ['MahasiswaController', 'update'],

        // Hapus mahasiswa
        '/mahasiswa/{id}/delete' => ['MahasiswaController', 'delete'],


        /*
        |--------------------------------------------------------------------------
        | Prodi
        |--------------------------------------------------------------------------
        */

        // Simpan prodi baru
        '/prodi' => ['ProdiController', 'store'],

        // Update prodi
        '/prodi/{id}/update' => ['ProdiController', 'update'],

        // Hapus prodi
        '/prodi/{id}/delete' => ['ProdiController', 'delete'],


        /*
        |--------------------------------------------------------------------------
        | Mata Kuliah
        |--------------------------------------------------------------------------
        */

        // Simpan mata kuliah baru
        '/matakuliah' => ['MatakuliahController', 'store'],

        // Update mata kuliah
        '/matakuliah/{id}/update' => ['MatakuliahController', 'update'],

        // Hapus mata kuliah
        '/matakuliah/{id}/delete' => ['MatakuliahController', 'delete'],
    ],

];