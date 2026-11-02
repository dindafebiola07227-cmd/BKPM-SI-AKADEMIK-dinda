<?php

// Mode aplikasi: 'development' => JSON dirapikan (pretty print), 'production' => JSON ringkas.
// Bisa diganti lewat environment variable APP_ENV tanpa mengubah kode.
define('APP_ENV', getenv('APP_ENV') ?: 'development');

// Zona waktu server agar timestamp response tampil dalam WIB (+07:00)
date_default_timezone_set('Asia/Jakarta');
