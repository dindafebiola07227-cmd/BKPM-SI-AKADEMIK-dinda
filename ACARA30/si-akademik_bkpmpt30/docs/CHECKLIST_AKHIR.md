# Checklist Akhir dan Serah Terima Proyek (Acara 30, Langkah 5)

| No | Butir | Cara memeriksa | Status |
|---|---|---|---|
| 1 | Sertifikat SSL aktif | Gembok pada `https://domain-anda` | ☐ |
| 2 | HTTP dialihkan ke HTTPS | `http://domain-anda/mahasiswa` → 301 ke `https://` | ☐ |
| 3 | `display_errors` = Off | `phpinfo()` sementara, atau picu error dan pastikan tidak tampil | ☐ |
| 4 | Error tercatat di log | Berkas `php_errors.log` bertambah | ☐ |
| 5 | `app/` tidak dapat diakses | `https://domain-anda/app/config/Database.php` → 403 | ☐ |
| 6 | `app/config/config.local.php` tidak ikut Git | `git status` tidak menampilkannya | ☐ |
| 7 | Test case Acara 23 (TC 01–TC 14) lulus di produksi | Postman environment **Production** | ☐ |
| 8 | Base URL dokumentasi memakai `https://` | `API_DOCUMENTATION.md`, `openapi.yaml` | ☐ |
| 9 | Alur login → dashboard → tambah/ubah/hapus di View | Browser lewat HTTPS | ☐ |
| 10 | Backup/restore teruji | `docs/DISASTER_RECOVERY.md` | ☐ |
