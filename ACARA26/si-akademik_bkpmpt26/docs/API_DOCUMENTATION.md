# API Documentation - Sistem Informasi Akademik (Acara 21)

Dokumentasi ini disusun berdasarkan seluruh Controller yang telah dibangun:
`AuthController` (register, login), `MahasiswaController` (index, store, update, destroy),
dan `BukuController` (index, store, update, destroy).

**Base URL:** `http://localhost/BKPM/ACARA21/si-akademik_bkpmpt21/public` (Apache/XAMPP) atau `http://localhost:8080` (`jalankan.bat`).
Pada Postman alamat ditulis `{{base_url}}/...`.

## 1. Ringkasan Seluruh Endpoint

| Method | Endpoint | Controller::method | Perlu Token | Keterangan |
|---|---|---|---|---|
| POST | `/register` | `AuthController::register()` | Tidak | Pendaftaran pengguna baru |
| POST | `/login` | `AuthController::login()` | Tidak | Autentikasi pengguna, menghasilkan token JWT |
| GET | `/mahasiswa` | `MahasiswaController::index()` | Tidak | Ambil semua data mahasiswa |
| GET | `/mahasiswa?id={id}` atau `/mahasiswa/{id}` | `MahasiswaController::index()` | Tidak | Ambil satu mahasiswa berdasarkan ID |
| POST | `/mahasiswa` | `MahasiswaController::store()` | **Ya** | Tambah mahasiswa |
| PUT | `/mahasiswa?id={id}` atau `/mahasiswa/{id}` | `MahasiswaController::update()` | **Ya** | Ubah data mahasiswa |
| DELETE | `/mahasiswa?id={id}` atau `/mahasiswa/{id}` | `MahasiswaController::destroy()` | **Ya** | Hapus mahasiswa |
| GET | `/buku`, `/buku?id={id}` | `BukuController::index()` | Tidak | Ambil data buku |
| POST | `/buku` | `BukuController::store()` | Tidak | Tambah buku |
| PUT | `/buku?id={id}` | `BukuController::update()` | Tidak | Ubah buku |
| DELETE | `/buku?id={id}` | `BukuController::destroy()` | Tidak | Hapus buku |

## 2. Peta Router

Router (`app/core/Router.php`) memetakan nama resource pada URL ke class Controller, lalu memanggil method
sesuai HTTP method (GET -> `index`, POST -> `store`, PUT -> `update`, DELETE -> `destroy`).

| Resource (URL) | Controller | Keterangan |
|---|---|---|
| `/mahasiswa` | `MahasiswaController` | CRUD data mahasiswa (index/store/update/destroy) |
| `/buku` | `BukuController` | CRUD data buku (index/store/update/destroy) |
| `/login` | `AuthController::login` | Autentikasi pengguna, menghasilkan token (hanya POST) |
| `/register` | `AuthController::register` | Pendaftaran pengguna baru (hanya POST) |

Alur permintaan: `Client -> public/index.php -> Router::dispatch() -> Controller -> Model -> Response::json()`.

## 3. Format Response Seragam

Semua response dikirim oleh `Response::json()`:

```json
{
    "success": true,
    "message": "Pesan untuk client",
    "data": {},
    "errors": { "field": ["pesan kesalahan"] },
    "timestamp": "2026-11-19T09:00:00+07:00"
}
```
`errors` hanya muncul pada kegagalan validasi.

## 4. Autentikasi

Endpoint terlindungi memerlukan header:
```
Authorization: Bearer <token>
```
Token diperoleh dari `POST /login`, berbentuk JWT (HS256) dan berlaku **300 detik (5 menit)**.
Pemeriksaan dilakukan oleh `Auth::requireAuth()` di baris pertama method yang dilindungi.

| Kondisi | Status | message |
|---|---|---|
| Header Authorization tidak ada / bukan skema Bearer | 401 | Token tidak ditemukan |
| Token rusak, tanda tangan salah, atau kedaluwarsa | 401 | Token tidak valid atau kedaluwarsa |
| Token sah tetapi role bukan admin (Acara 25, Auth::requireAdmin) | 403 | Akses ditolak, hanya admin yang diizinkan |

---

## 5. Endpoint Autentikasi

### POST /register
**Controller:** `AuthController::register()`

**Headers:** `Content-Type: application/json`

**Request Body:**
```json
{
    "username": "dinda_test",
    "email": "dinda_test@gmail.com",
    "password": "RahasiaUji123"
}
```

**Response 201 Created:**
```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": { "id": 1 },
    "timestamp": "2026-11-19T09:00:00+07:00"
}
```

| Status | message | Penyebab |
|---|---|---|
| 400 | Body request harus berupa JSON yang valid | Body bukan JSON |
| 400 | Username, email, dan password wajib diisi | Ada field kosong (rincian di `errors`) |
| 409 | Username sudah terdaftar / Email sudah terdaftar | Data sudah dipakai |
| 422 | Format email tidak valid | Email salah format / melebihi panjang maksimal |
| 405 | Method tidak diizinkan | Method selain POST |

### POST /login
**Controller:** `AuthController::login()`

**Headers:** `Content-Type: application/json`

**Request Body:**
```json
{
    "username": "dinda_test",
    "password": "RahasiaUji123"
}
```

**Response 200 OK:**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.<payload>.<signature>",
        "role": "user",
        "expires_in": 300
    },
    "timestamp": "2026-11-19T09:00:00+07:00"
}
```

| Status | message | Penyebab |
|---|---|---|
| 400 | Body request harus berupa JSON yang valid | Body bukan JSON |
| 400 | Username dan password wajib diisi | Ada field kosong |
| 401 | Username atau password salah | Username tidak ada atau password salah |
| 405 | Method tidak diizinkan | Method selain POST |

---

## 6. Endpoint Mahasiswa

Field: `nim` (maks. 15 karakter; kode saat ini hanya memeriksa panjang tepat 9 byte, belum memeriksa bahwa nilainya angka), `nama` (maks. 100), `prodi` (maks. 50).
Router menerima ID sebagai query `?id=1` maupun segmen path `/mahasiswa/1` untuk operasi GET, PUT, dan DELETE.

### GET /mahasiswa
**Controller:** `MahasiswaController::index()` - tanpa token.

**Response 200 OK:**
```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": [
        { "id": 1, "nim": "123456789", "nama": "Andi Saputra", "prodi": "Teknik Informatika", "created_at": "2026-11-19 09:00:00" }
    ],
    "timestamp": "2026-11-19T09:00:00+07:00"
}
```

### GET /mahasiswa?id={id}
**Controller:** `MahasiswaController::index()` - tanpa token.

| Status | message | Penyebab |
|---|---|---|
| 200 | Data berhasil diambil | Data ditemukan (`data` berisi satu objek) |
| 400 | ID harus berupa angka positif | `id` bukan angka positif |
| 404 | Data tidak ditemukan | ID tidak ada |

### POST /mahasiswa
**Controller:** `MahasiswaController::store()` - **wajib token** (`Auth::requireAuth()`).

**Headers:**
```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:**
```json
{
    "nim": "123456789",
    "nama": "Andi Saputra",
    "prodi": "Teknik Informatika"
}
```

**Response 201 Created:**
```json
{
    "success": true,
    "message": "Data berhasil ditambahkan",
    "data": { "id": 1 },
    "timestamp": "2026-11-19T09:00:00+07:00"
}
```

| Status | message | Penyebab |
|---|---|---|
| 400 | Body request harus berupa JSON yang valid | Body bukan JSON |
| 400 | Field nim, nama, dan prodi wajib diisi | Ada field kosong |
| 401 | Token tidak ditemukan / Token tidak valid atau kedaluwarsa | Autentikasi gagal |
| 403 | Akses ditolak, hanya admin yang diizinkan | Token sah tetapi role bukan admin |
| 422 | NIM sudah terdaftar | NIM kembar |
| 422 | Format NIM tidak valid, harus 9 digit angka / Data tidak valid | NIM bukan 9 digit angka atau field melebihi batas |

### PUT /mahasiswa?id={id}
**Controller:** `MahasiswaController::update()` - **wajib token**. Header dan body sama seperti `POST /mahasiswa`.

**Response 200 OK:** `message: "Data berhasil diperbarui"`, `data` berisi data terbaru.

| Status | message | Penyebab |
|---|---|---|
| 400 | ID wajib diisi dan harus berupa angka positif | `id` kosong / bukan angka |
| 400 | Body request harus berupa JSON yang valid / Field ... wajib diisi | Body tidak valid |
| 401 | Token tidak ditemukan / Token tidak valid atau kedaluwarsa | Autentikasi gagal |
| 403 | Akses ditolak, hanya admin yang diizinkan | Token sah tetapi role bukan admin |
| 404 | Data tidak ditemukan | ID tidak ada |
| 422 | NIM sudah terdaftar | NIM dipakai mahasiswa lain |
| 422 | Format NIM tidak valid, harus 9 digit angka / Data tidak valid | NIM bukan 9 digit angka atau field melebihi batas |

### DELETE /mahasiswa?id={id}
**Controller:** `MahasiswaController::destroy()` - **wajib token**.

**Headers:** `Authorization: Bearer <token>`

**Response 200 OK:**
```json
{
    "success": true,
    "message": "Data berhasil dihapus",
    "data": null,
    "timestamp": "2026-11-19T09:00:00+07:00"
}
```

| Status | message | Penyebab |
|---|---|---|
| 400 | ID wajib diisi dan harus berupa angka positif | `id` kosong / bukan angka |
| 401 | Token tidak ditemukan / Token tidak valid atau kedaluwarsa | Autentikasi gagal |
| 403 | Akses ditolak, hanya admin yang diizinkan | Token sah tetapi role bukan admin |
| 404 | Data tidak ditemukan | ID tidak ada |

---

## 7. Endpoint Buku

**Controller:** `BukuController` (index/store/update/destroy). Tidak memerlukan token.
Field: `judul` (maks. 150), `penulis` (maks. 100), `tahun_terbit` (4 digit, 1900 sampai tahun berjalan).

| Method | Endpoint | Method Controller | Status utama |
|---|---|---|---|
| GET | `/buku`, `/buku?id=1` | `index()` | 200, 400, 404 |
| POST | `/buku` | `store()` | 201, 400, 422 |
| PUT | `/buku?id=1` | `update()` | 200, 400, 404, 422 |
| DELETE | `/buku?id=1` | `destroy()` | 200, 400, 404 |

**Contoh body POST/PUT:**
```json
{ "judul": "Pemrograman Web", "penulis": "Budi Santoso", "tahun_terbit": "2024" }
```

## 8. Kode Status Umum

| Kode | Arti |
|---|---|
| 200 / 201 | Berhasil / data berhasil dibuat |
| 400 | Request tidak lengkap atau body bukan JSON |
| 401 | Token tidak ada, rusak, atau kedaluwarsa |
| 403 | Role pengguna bukan admin |
| 404 | Data atau endpoint tidak ditemukan |
| 405 | Method tidak diizinkan |
| 409 | Konflik data (duplikat) |
| 422 | Format data tidak valid |
| 500 | Kesalahan server |
