# Panduan Acara 30 — Deployment Produksi

Menyempurnakan deployment Acara 28 dan menutup proyek MVC Sistem Informasi Akademik.

## Perubahan dibanding Acara 29
| Berkas | Perubahan |
|---|---|
| `.htaccess` (root), `public/.htaccess` | Aturan redirect HTTP → HTTPS (301), di atas aturan rewrite ke `index.php`; `localhost`/`127.0.0.1` dikecualikan |
| `app/.htaccess` | `Require all denied` (+ cadangan sintaks Apache 2.2) |
| `app/config/app.php` | `APP_ENV` otomatis (`production` di domain hosting); cadangan `display_errors=0`, `log_errors=1` |
| `app/config/Secrets.php` (baru), `config.local.example.php` (baru) | Pembaca kredensial dari berkas terpisah di luar Git |
| `app/config/Database.php`, `app/core/Auth.php` | Host/DB/user/password dan kunci JWT dibaca dari `Secrets` (bawaan XAMPP bila berkas tidak ada) |
| `public/views/config.js` | `BASE_URL` otomatis dari alamat halaman |
| `docs/API_DOCUMENTATION.md`, `docs/openapi.yaml`, `postman/` | Base URL produksi `https://namadomain.com` (ganti dengan domain Anda) |
| `database/simulasi_dr.bat`, `docs/DISASTER_RECOVERY.md` | Simulasi disaster recovery (Tugas Mandiri 3) |
| `docs/CHECKLIST_AKHIR.md` | Checklist serah terima |

## Langkah di cPanel (dikerjakan sendiri di hosting)
1. **SSL**: cPanel → *SSL/TLS Status* → centang domain → **Run AutoSSL**. Buka `https://domain-anda` dan pastikan ikon gembok muncul.
2. **Force HTTPS**: cPanel → *Domains* → aktifkan **Force HTTPS Redirect**, atau cukup andalkan aturan di `.htaccess` proyek ini.
3. **PHP**: cPanel → *MultiPHP INI Editor* → pilih domain → isi seperti `docs/php_production.ini`.
4. **Kredensial**: salin `app/config/config.local.example.php` menjadi `app/config/config.local.php` (atau `si-akademik_config.php` di folder induk proyek, di luar `public_html`), isi nama database, user, password, dan `jwt_secret` acak.
   Contoh membuat kunci acak: `php -r "echo bin2hex(random_bytes(32));"`.
5. **Uji**: buka `http://domain-anda` (harus pindah ke HTTPS), buka `https://domain-anda/app/config/Database.php` (harus **403**), picu error sengaja (harus tidak membocorkan path/query).

## Catatan
- Aturan redirect memeriksa `HTTPS off` **dan** `X-Forwarded-Proto` agar tidak terjadi redirect berulang di belakang proxy/CDN.
- `jwt_secret` di bawah 16 karakter diabaikan dan dicatat ke log pada mode produksi.
- Upload foto: pastikan folder `public/uploads/` dapat ditulis (permission 755/775) dan `.htaccess` di dalamnya tetap ada.
