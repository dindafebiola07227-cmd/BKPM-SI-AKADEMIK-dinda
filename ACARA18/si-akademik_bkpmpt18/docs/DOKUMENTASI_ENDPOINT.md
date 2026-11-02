# Dokumentasi Endpoint - Acara 18 (JSON Encoding & HTTP Status Code)

**Base URL:** `http://localhost/BKPM/ACARA18/si-akademik_bkpmpt18/public`
(sesuaikan dengan lokasi folder `public/` di komputermu)

Id dikirim lewat query (`?id=1`) atau path (`/mahasiswa/1`). Contoh di bawah dibuat dari kode program;
nilai `id` dan `timestamp` pada komputermu akan berbeda, jadi gunakan hasil asli Postman untuk screenshot laporan.

## Format response seragam (response envelope)

Semua response, baik dari Router maupun Controller, dibentuk oleh `Response::json()`:

| Field | Tipe | Keterangan |
|---|---|---|
| `success` | boolean | `true` jika request berhasil |
| `message` | string | pesan untuk client |
| `data` | object / array / null | hasil; `null` bila tidak ada |
| `errors` | object | **hanya ada saat validasi gagal**: daftar pesan kesalahan per field |
| `timestamp` | string | waktu response, format ISO 8601 (`date('c')`) |

Mode JSON: pada `APP_ENV = development` JSON dirapikan (pretty print); pada `production` JSON ringkas.
Pengaturan ada di `app/config/app.php`.

Contoh sukses (`GET /mahasiswa?id=1`, status 200):
```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": {
        "id": 1,
        "nim": "220100001",
        "nama": "Andi Saputra",
        "prodi": "Teknik Informatika",
        "created_at": "2026-10-31 15:00:00"
    },
    "timestamp": "2026-10-31T15:00:00+07:00"
}
```

Contoh gagal validasi (`POST /mahasiswa` dengan NIM 7 digit, status 422):
```json
{
    "success": false,
    "message": "Format NIM tidak valid, harus 9 digit",
    "data": null,
    "errors": {
        "nim": [
            "NIM harus terdiri dari 9 digit"
        ]
    },
    "timestamp": "2026-10-31T15:00:00+07:00"
}
```

## Resource `mahasiswa`

| Method | Alamat | Fungsi | Status sukses |
|---|---|---|---|
| GET | `/mahasiswa` | Semua mahasiswa (terbaru dulu) | 200 |
| GET | `/mahasiswa?id=1` | Satu mahasiswa | 200 |
| POST | `/mahasiswa` | Tambah mahasiswa | 201 |
| PUT | `/mahasiswa?id=1` | Ubah mahasiswa | 200 |
| DELETE | `/mahasiswa?id=1` | Hapus mahasiswa | 200 |

Body POST/PUT (semua field wajib; **NIM harus 9 digit**):
```json
{ "nim": "220100001", "nama": "Andi Saputra", "prodi": "Teknik Informatika" }
```

## Resource `buku`

| Method | Alamat | Fungsi | Status sukses |
|---|---|---|---|
| GET | `/buku` | Semua buku | 200 |
| GET | `/buku?id=1` | Satu buku | 200 |
| POST | `/buku` | Tambah buku | 201 |
| PUT | `/buku?id=1` | Ubah buku | 200 |
| DELETE | `/buku?id=1` | Hapus buku | 200 |

Body POST/PUT:
```json
{ "judul": "Pemrograman Web dengan PHP", "penulis": "Budi Raharjo", "tahun_terbit": 2020 }
```

## Tabel kode status yang dipakai

| Kode | Arti | Kapan terjadi | Contoh `message` |
|---|---|---|---|
| 200 | OK | GET/PUT/DELETE berhasil | Data berhasil diambil / diperbarui / dihapus |
| 201 | Created | POST berhasil | Data berhasil ditambahkan |
| 400 | Bad Request | body bukan JSON valid, field wajib kosong, ID bukan angka positif | Field nim, nama, dan prodi wajib diisi |
| 404 | Not Found | ID tidak ada di database, atau endpoint tidak dikenal | Data tidak ditemukan / Endpoint tidak ditemukan |
| 405 | Method Not Allowed | method selain GET, POST, PUT, DELETE | Method tidak diizinkan |
| 409 | Conflict | NIM sudah terdaftar | NIM sudah terdaftar |
| 422 | Unprocessable Entity | format valid tetapi gagal validasi: NIM bukan 9 digit, field terlalu panjang, tahun terbit tidak valid | Format NIM tidak valid, harus 9 digit |
| 500 | Internal Server Error | error tak terduga (mis. koneksi database gagal) | Terjadi kesalahan pada server |

## Diagram

- Alur request: `diagram_alur_response.png`
- Diagram kelas: `diagram_kelas.png`
