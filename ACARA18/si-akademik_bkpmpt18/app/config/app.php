<?php

// Mode aplikasi: 'development' => JSON dirapikan (pretty print), 'production' => JSON ringkas.
// Bisa diganti lewat environment variable APP_ENV tanpa mengubah kode.
define('APP_ENV', getenv('APP_ENV') ?: 'development');
