# Dokumentasi Endpoint - Acara 17 (Skeleton MVC PHP Native)

**Base URL:** `http://localhost/BKPM/ACARA17/si-akademik_bkpmpt17/public`
(sesuaikan dengan lokasi folder `public/` di komputermu)

Semua response berformat JSON dengan field `success` (true/false) dan `message`/`data`.
Contoh di bawah dibuat berdasarkan kode program. Nilai `id` dan `created_at` akan berbeda pada
komputermu, jadi gunakan hasil asli dari Postman untuk screenshot laporan.

Id dapat dikirim lewat query (`?id=1`) atau path (`/mahasiswa/1`).

## Resource `mahasiswa`

| Method | Alamat | Fungsi | Status sukses |
|---|---|---|---|
| GET | `/mahasiswa` | Menampilkan semua mahasiswa (terbaru dulu) | 200 |
| GET | `/mahasiswa?id=1` | Menampilkan satu mahasiswa | 200 |
| POST | `/mahasiswa` | Menambah mahasiswa | 201 |
| PUT | `/mahasiswa?id=1` | Mengubah mahasiswa | 200 |
| DELETE | `/mahasiswa?id=1` | Menghapus mahasiswa | 200 |

### POST /mahasiswa
Request body (JSON):
```json
{ "nim": "2201001", "nama": "Andi Saputra", "prodi": "Teknik Informatika" }
```
Response `201 Created`:
```json
{ "success": true, "message": "Data berhasil ditambahkan", "data": { "id": 1 } }
```

### GET /mahasiswa
Response `200 OK`:
```json
{
  "success": true,
  "data": [
    { "id": 1, "nim": "2201001", "nama": "Andi Saputra", "prodi": "Teknik Informatika", "created_at": "2026-10-31 15:00:00" }
  ]
}
```

### GET /mahasiswa?id=1
Response `200 OK`:
```json
{
  "success": true,
  "data": { "id": 1, "nim": "2201001", "nama": "Andi Saputra", "prodi": "Teknik Informatika", "created_at": "2026-10-31 15:00:00" }
}
```

### PUT /mahasiswa?id=1
Request body (semua field wajib):
```json
{ "nim": "2201001", "nama": "Andi Saputra Pratama", "prodi": "Sistem Informasi" }
```
Response `200 OK`:
```json
{
  "success": true,
  "message": "Data berhasil diperbarui",
  "data": { "id": 1, "nim": "2201001", "nama": "Andi Saputra Pratama", "prodi": "Sistem Informasi", "created_at": "2026-10-31 15:00:00" }
}
```

### DELETE /mahasiswa?id=1
Response `200 OK`:
```json
{ "success": true, "message": "Data berhasil dihapus" }
```

## Resource `buku` (Tugas Mandiri 1)

| Method | Alamat | Fungsi | Status sukses |
|---|---|---|---|
| GET | `/buku` | Menampilkan semua buku | 200 |
| GET | `/buku?id=1` | Menampilkan satu buku | 200 |
| POST | `/buku` | Menambah buku | 201 |
| PUT | `/buku?id=1` | Mengubah buku | 200 |
| DELETE | `/buku?id=1` | Menghapus buku | 200 |

### POST /buku
Request body:
```json
{ "judul": "Pemrograman Web dengan PHP", "penulis": "Budi Raharjo", "tahun_terbit": 2020 }
```
Response `201 Created`:
```json
{ "success": true, "message": "Data berhasil ditambahkan", "data": { "id": 1 } }
```
`tahun_terbit` harus tahun 4 digit antara 1900 sampai tahun berjalan.

## Response error

| Kondisi | Status | Contoh response |
|---|---|---|
| Resource tidak dikenal (mis. `/produk`) | 404 | `{"success":false,"message":"Endpoint tidak ditemukan"}` |
| Method tidak didukung (mis. PATCH) | 405 | `{"success":false,"message":"Method tidak diizinkan"}` |
| ID tidak ada di database | 404 | `{"success":false,"message":"Data tidak ditemukan"}` |
| ID bukan angka positif / tidak dikirim saat PUT atau DELETE | 400 | `{"success":false,"message":"ID wajib diisi dan harus berupa angka positif"}` |
| Body bukan JSON valid | 400 | `{"success":false,"message":"Body request harus berupa JSON yang valid"}` |
| Field wajib kosong atau tidak ada | 400 | `{"success":false,"message":"Field nim, nama, prodi wajib diisi"}` |
| Field melebihi panjang maksimal | 400 | `{"success":false,"message":"Field nim maksimal 15 karakter"}` |
| NIM sudah terdaftar (POST/PUT) - Tugas Mandiri 2 | 409 | `{"success":false,"message":"NIM sudah terdaftar"}` |
| Error server tak terduga | 500 | `{"success":false,"message":"Terjadi kesalahan pada server"}` |

## Diagram

- Alur request: `diagram_alur_router_controller_model.png`
- Diagram kelas: `diagram_kelas.png`

Urutan eksekusi satu request, contoh `POST /mahasiswa`:
1. Client mengirim request ke Apache. `.htaccess` mengarahkannya ke `public/index.php` (Front Controller).
2. `index.php` memuat autoloader, lalu memanggil `Router::dispatch()`.
3. Router membaca URL dan method HTTP, memilih `MahasiswaController`, lalu memanggil `store()`.
4. Controller memvalidasi body JSON, lalu memanggil `Mahasiswa::create()`.
5. Model memeriksa NIM duplikat dan menjalankan `INSERT` dengan prepared statement PDO ke database `db_workshop`.
6. Controller membentuk response JSON (status 201) dan mengirimkannya ke client.
