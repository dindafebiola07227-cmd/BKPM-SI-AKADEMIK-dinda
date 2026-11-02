# Acara 18 - JSON Encoding dan HTTP Status Code (si-akademik_bkpmpt18)

Lanjutan Acara 17: database tetap `db_workshop` (tidak perlu import ulang bila Acara 17 sudah jalan).

## Menjalankan
1. Letakkan folder ini di `C:\xampp\htdocs\BKPM\ACARA18\`.
2. Nyalakan Apache dan MySQL. Database `db_workshop` sudah ada dari Acara 17 (bila belum, import `db_workshop.sql`).
3. Buka `http://localhost/BKPM/ACARA18/si-akademik_bkpmpt18/public/mahasiswa`.
4. Postman: import `docs/Acara18_Response_JSON_StatusCode.postman_collection.json`,
   ubah variabel `baseUrl`, lalu Run collection.

## Yang baru dibanding Acara 17
```
app/core/Response.php            class Response::json() - response envelope seragam (+ field errors)
app/core/ValidationException.php exception validasi (kode HTTP + errors per field)
app/config/app.php               konstanta APP_ENV (pretty print hanya saat development)
app/core/Router.php              404 dan 405 kini lewat Response::json()
app/controllers/*                memakai Response::json(); NIM harus 9 digit (422)
```
Model tidak diubah.

## Git (untuk log aktivitas di laporan)
```
git add .
git commit -m "Acara 18: class Response, validasi 422, errors per field, APP_ENV"
git log --oneline
```
