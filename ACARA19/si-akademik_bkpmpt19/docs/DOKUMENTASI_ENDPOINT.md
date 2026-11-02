# Dokumentasi Endpoint - Acara 19 (Postman dan API Authentication)

**Base URL (Environment Local):** `http://localhost/BKPM/ACARA19/si-akademik_bkpmpt19/public`
**Base URL (Environment Staging):** `http://127.0.0.1/BKPM/ACARA19/si-akademik_bkpmpt19/public`
Pada Postman, alamat ditulis `{{base_url}}/...` dan nilainya diambil dari environment yang dipilih.

Semua response memakai format seragam dari `Response::json()`:
`success`, `message`, `data`, `errors` (hanya saat validasi gagal), `timestamp`.
Contoh nilai di bawah dibuat dari kode program; gunakan hasil asli Postman untuk screenshot laporan.

## POST /register

Mendaftarkan pengguna baru. Hanya method POST yang diterima. Password disimpan sebagai hash
(`password_hash()` dengan `PASSWORD_DEFAULT`), hash tidak pernah dikembalikan ke client, dan `role`
selalu bernilai `user` (default tabel) karena tidak dapat diisi dari request.

Request body:
```json
{ "username": "andi", "email": "andi@gmail.com", "password": "RahasiaUji123" }
```

Response sukses `201 Created`:
```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": { "id": 1 },
    "timestamp": "2026-11-01T09:00:00+07:00"
}
```

### Response gagal

| Kondisi | Status | `message` | `errors` |
|---|---|---|---|
| Body bukan JSON valid | 400 | Body request harus berupa JSON yang valid | - |
| Username/email/password kosong atau tidak dikirim | 400 | Username, email, dan password wajib diisi | per field yang kosong |
| Format email tidak valid (Tugas Mandiri 1) | 422 | Format email tidak valid | `email` |
| Username lebih dari 50 karakter / email lebih dari 100 karakter | 422 | pesan batas panjang | `username` / `email` |
| Username sudah terdaftar | 409 | Username sudah terdaftar | `username` |
| Email sudah terdaftar | 409 | Email sudah terdaftar | `email` |
| Method selain POST (mis. GET) | 405 | Method tidak diizinkan | - |

Contoh `422` (email tidak valid):
```json
{
    "success": false,
    "message": "Format email tidak valid",
    "data": null,
    "errors": { "email": [ "Format email tidak valid" ] },
    "timestamp": "2026-11-01T09:00:00+07:00"
}
```

## Resource `mahasiswa` (dari Acara 17-18)

| Method | Alamat | Fungsi | Status sukses |
|---|---|---|---|
| GET | `{{base_url}}/mahasiswa` | Semua mahasiswa | 200 |
| GET | `{{base_url}}/mahasiswa?id=1` | Satu mahasiswa | 200 |
| POST | `{{base_url}}/mahasiswa` | Tambah mahasiswa (NIM 9 digit) | 201 |
| PUT | `{{base_url}}/mahasiswa?id=1` | Ubah mahasiswa | 200 |
| DELETE | `{{base_url}}/mahasiswa?id=1` | Hapus mahasiswa | 200 |

## Environment Postman

| Environment | Variabel | Nilai |
|---|---|---|
| Local | `base_url` | `http://localhost/BKPM/ACARA19/si-akademik_bkpmpt19/public` |
| Staging (Tugas Mandiri 2) | `base_url` | `http://127.0.0.1/BKPM/ACARA19/si-akademik_bkpmpt19/public` |

Staging disimulasikan dengan alamat `127.0.0.1` yang tetap mengarah ke server lokal yang sama,
sehingga seluruh request tetap dapat diuji tanpa server tambahan.

## Contoh response tersimpan
Semua request pada collection memuat contoh response asli (saved example) hasil pengujian, termasuk response sukses dan gagal.

## Diagram
- Alur registrasi: `diagram_alur_register.png`
- Diagram kelas: `diagram_kelas.png`
