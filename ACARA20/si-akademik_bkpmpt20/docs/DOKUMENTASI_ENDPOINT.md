# Dokumentasi Endpoint - Acara 20 (API Authentication dan Error Handling)

**Base URL (Environment Local):** `http://localhost/BKPM/ACARA20/si-akademik_bkpmpt20`
**Base URL (Environment Staging):** `http://127.0.0.1/BKPM/ACARA20/si-akademik_bkpmpt20`
**Base URL (Environment Local (php -S 8080)):** `http://localhost:8080` (dari `jalankan.bat`)
Pada Postman, alamat ditulis `{{base_url}}/...`.

Semua response memakai format seragam dari `Response::json()`:
`success`, `message`, `data`, `errors` (hanya saat validasi gagal), `timestamp`.
Contoh nilai di bawah dibuat dari kode program; gunakan hasil asli Postman untuk screenshot laporan.

## Cek koneksi

`GET /` (alamat dasar), `/public`, `/index.php`, atau `/ping` membalas `200` dengan daftar endpoint. Berguna untuk memastikan Apache / `php -S` sudah berjalan.

## Ringkasan akses

| Method | Alamat | Perlu token? |
|---|---|---|
| POST | `/register` | Tidak |
| POST | `/login` | Tidak |
| GET | `/mahasiswa`, `/mahasiswa?id=1` | Tidak (publik) |
| POST | `/mahasiswa` | **Ya** |
| PUT | `/mahasiswa?id=1` | **Ya** |
| DELETE | `/mahasiswa?id=1` | **Ya** |

Token dikirim pada header: `Authorization: Bearer <token>`.
Pemeriksaan token dilakukan **paling awal** pada method terlindungi, sebelum validasi body.

## POST /login

Memverifikasi username dan password (`password_verify()` terhadap hash di tabel `users`), lalu mengirim token JWT (HS256) yang berlaku **300 detik (5 menit)**. Hanya method POST yang diterima.

Request body:
```json
{ "username": "andi", "password": "RahasiaUji123" }
```

Response sukses `200 OK`:
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwicm9sZSI6InVzZXIiLCJleHAiOjE3OTM1OTE4MDJ9.<signature>",
        "role": "user",
        "expires_in": 300
    },
    "timestamp": "2026-11-02T09:00:00+07:00"
}
```

| Kondisi | Status | `message` | `errors` |
|---|---|---|---|
| Body bukan JSON valid | 400 | Body request harus berupa JSON yang valid | - |
| Username/password kosong atau tidak dikirim | 400 | Username dan password wajib diisi | per field yang kosong |
| Username tidak terdaftar **atau** password salah | 401 | Username atau password salah | - |
| Method selain POST | 405 | Method tidak diizinkan | - |

Pesan 401 sengaja sama untuk dua kondisi agar penyerang tidak dapat menebak username yang terdaftar.

## Isi token

```
header.payload.signature
header    = {"alg":"HS256","typ":"JWT"}
payload   = {"id":1,"role":"user","exp":<waktu login + 300 detik>}
signature = Base64URL( HMAC-SHA256(header.payload, SECRET) )
```

## Endpoint terlindungi: POST, PUT, DELETE /mahasiswa

Contoh response `401 Unauthorized`:
```json
{
    "success": false,
    "message": "Token tidak ditemukan",
    "data": null,
    "timestamp": "2026-11-02T09:00:00+07:00"
}
```

| Kondisi | Status | `message` |
|---|---|---|
| Header Authorization tidak ada, atau bukan skema `Bearer` | 401 | Token tidak ditemukan |
| Token diubah/rusak, tanda tangan tidak cocok, bukan 3 bagian | 401 | Token tidak valid atau kedaluwarsa |
| Token sah tetapi sudah lewat 5 menit | 401 | Token tidak valid atau kedaluwarsa |
| Token sah | lanjut ke validasi seperti Acara 18 | 201 / 200 / 400 / 404 / 409 / 422 |

Header `WWW-Authenticate: Bearer` ikut dikirim pada response 401.

## Error Handling lapisan Model (Langkah 5)

`create()`, `update()`, dan `delete()` pada `Mahasiswa` dibungkus `try ... catch (PDOException)`:

| Kejadian | Hasil |
|---|---|
| NIM kembar terdeteksi `existsNim()` | 409 NIM sudah terdaftar (seperti sebelumnya) |
| NIM kembar lolos pemeriksaan, ditolak constraint UNIQUE (SQLSTATE 23000) | 409 NIM sudah terdaftar |
| Kesalahan database lain | 500 Terjadi kesalahan pada server (detail hanya ke `error_log`) |
| Koneksi database gagal (`Database.php`) | 500 Koneksi database gagal |
| Error tak terduga lain | 500 Terjadi kesalahan pada server (`set_exception_handler` di `public/index.php`) |

## POST /register (dari Acara 19, tidak berubah)

Lihat dokumentasi Acara 19: sukses 201, gagal 400/409/422/405.

## Environment Postman

| Environment | Variabel | Nilai |
|---|---|---|
| Local | `base_url` | `http://localhost/BKPM/ACARA20/si-akademik_bkpmpt20` |
| Local (php -S 8080) | `base_url` | `http://localhost:8080` |
| Staging | `base_url` | `http://127.0.0.1/BKPM/ACARA20/si-akademik_bkpmpt20` |

Variabel `token` dan `tokenRusak` diisi otomatis oleh request login (collection variable). `tokenKedaluwarsa` adalah token tetap (tanda tangan sah, `exp` sudah lewat) untuk uji 401 kedaluwarsa. Environment sengaja **tidak** memuat variabel `token`, karena variabel environment akan menimpa collection variable.

## Diagram
- Alur login, proteksi token, dan error handling: `diagram_alur_login.png`
- Diagram dari Acara 19: `diagram_alur_register.png`, `diagram_kelas.png` (kelas `Auth` bersifat statis dan dipanggil oleh `AuthController` serta `MahasiswaController`)
