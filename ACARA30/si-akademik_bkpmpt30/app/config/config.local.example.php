<?php

// Salin berkas ini menjadi config.local.php (atau si-akademik_config.php di folder induk proyek),
// lalu isi dengan nilai asli server. JANGAN di-commit ke Git.
return [
    'db_host'    => 'localhost',
    'db_name'    => 'namacpanel_db_workshopku',   // di cPanel biasanya berawalan nama akun
    'db_user'    => 'namacpanel_dbuser',
    'db_pass'    => 'ISI_PASSWORD_DATABASE',
    'jwt_secret' => 'ISI_KUNCI_ACAK_MINIMAL_32_KARAKTER',   // contoh pembuatan: bin2hex(random_bytes(32))
];
