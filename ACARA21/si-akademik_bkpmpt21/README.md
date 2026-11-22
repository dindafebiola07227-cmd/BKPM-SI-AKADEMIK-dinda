# Acara 21 - Dokumentasi API (si-akademik_bkpmpt21)

Lanjutan Acara 20 (kode program tidak diubah, hanya ditambah dokumentasi). Database tetap `db_workshop`.

## Yang baru dibanding Acara 20
```
Langkah 1  docs/API_DOCUMENTATION.md   BARU  dokumentasi seluruh endpoint
Langkah 2  docs/API_DOCUMENTATION.md   tabel Peta Router
Langkah 3  docs/API_DOCUMENTATION.md   endpoint autentikasi (register, login)
Langkah 4  docs/API_DOCUMENTATION.md   endpoint mahasiswa (+ keterangan Bearer token)
Langkah 5  postman/                    Description (Controller::method) + Examples pada tiap request
Mandiri 1  docs/API_DOCUMENTATION.md   bagian 1: tabel ringkasan seluruh endpoint + Controller
```
Menjalankan project: lihat `docs/PANDUAN_PENGUJIAN.md` (Base URL dan cara uji sama seperti Acara 20).

## Base URL (sesuai pengujian di Postman)
`http://localhost/BKPM/ACARA21/si-akademik_bkpmpt21/public` - contoh: `.../public/register`, `.../public/login`, `.../public/mahasiswa`.
Letakkan folder project di `C:\xampp\htdocs\BKPM\ACARA21\si-akademik_bkpmpt21\`, nyalakan Apache dan MySQL, import 4 file dari folder `postman/` lalu pilih environment **Local**.
