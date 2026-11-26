# BKPM Acara 30 — Deployment Produksi (HTTPS, Hardening PHP, Serah Terima Proyek)

Penutup proyek MVC Sistem Informasi Akademik (Acara 17 s.d. 30). Melanjutkan Acara 29 dan menyempurnakan deployment Acara 28.

Perubahan dibanding Acara 29:
- **HTTPS redirect** pada `.htaccess` root dan `public/.htaccess` (localhost dikecualikan).
- **Penguatan PHP produksi**: `display_errors=Off`, `log_errors=On` (`docs/php_production.ini`, cadangan di `app/config/app.php`).
- **`app/.htaccess`** penolak akses (lapisan pertahanan tambahan).
- **Pemisahan kredensial**: `Secrets.php` membaca `config.local.php` / `si-akademik_config.php` (tidak ikut Git); `Database.php` dan `Auth.php` memakainya.
- **`public/views/config.js`**: `BASE_URL` otomatis dari alamat halaman (lokal maupun HTTPS produksi).
- **Dokumen**: `docs/PANDUAN_ACARA30.md`, `docs/CHECKLIST_AKHIR.md`, `docs/DISASTER_RECOVERY.md`; `database/simulasi_dr.bat`.
- Base URL `docs/API_DOCUMENTATION.md`, `docs/openapi.yaml`, dan Postman diperbarui (ganti `namadomain.com` dengan domain Anda).
