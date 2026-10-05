<?php

$routes = [

    'GET' => [

        '/' => ['HomeController', 'index'],

        '/login' => ['AuthController', 'loginForm'],

        '/mahasiswa' => ['MahasiswaController', 'index'],

        '/mahasiswa/create' => ['MahasiswaController', 'create'],

        '/dashboard' => ['HomeController', 'dashboard'],

        '/logout' => ['AuthController', 'logout'],
    ],

    'POST' => [

        '/login' => ['AuthController', 'login'],

        '/mahasiswa' => ['MahasiswaController', 'store'],
    ],

];