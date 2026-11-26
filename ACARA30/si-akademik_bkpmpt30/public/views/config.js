// Acara 30: alamat dasar API ditentukan otomatis dari alamat halaman yang sedang dibuka,
// sehingga sama-sama berfungsi di XAMPP (http://localhost/.../public), php -S 8080,
// dan hosting produksi (https://domain-anda) tanpa mengubah berkas ini.
// Karena memakai origin halaman, request dari halaman HTTPS tidak pernah turun ke HTTP (mixed content).
const BASE_URL = window.location.origin
    + window.location.pathname.replace(/\/views\/[^\/]*$/, '').replace(/\/+$/, '');
