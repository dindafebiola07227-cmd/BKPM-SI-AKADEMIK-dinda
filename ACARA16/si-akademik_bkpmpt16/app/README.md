# Acara 16 - RESTful API Mahasiswa

## Cara menjalankan
1. Jalankan MySQL (XAMPP/Laragon), lalu import `database/si_akademik.sql` lewat phpMyAdmin.
2. Cek `config/database.php` (dbname = `si_akademik`, user `root`, password kosong).
3. Taruh folder project di `htdocs`/`www`, atau jalankan server bawaan PHP dari folder project:
   `php -S 127.0.0.1:8000 -t public public/index.php`
4. Import `docs/Acara16_RESTful_API_Mahasiswa.postman_collection.json` ke Postman,
   lalu ubah variable `baseUrl` (mis. `http://localhost/si-akademik/public` atau `http://127.0.0.1:8000`).

## Endpoint
| Method | URL | Fungsi | Status sukses |
|--------|-----|--------|---------------|
| GET | /api/mahasiswa | Semua mahasiswa | 200 |
| GET | /api/mahasiswa/{id} | Satu mahasiswa | 200 |
| POST | /api/mahasiswa | Tambah mahasiswa | 201 |
| PUT | /api/mahasiswa/{id} | Ubah mahasiswa | 200 |
| DELETE | /api/mahasiswa/{id} | Hapus mahasiswa | 200 |

Body POST/PUT (JSON): `nim`, `nama`, `email` wajib; `prodi_id` dan `angkatan` opsional.

```json
{ "nim": "2401010", "nama": "Dewi Lestari", "email": "dewi@gmail.com" }
```

## Status code error
| Kode | Kondisi |
|------|---------|
| 400 | ID bukan angka, JSON rusak/kosong, field tidak lengkap, NIM kosong, email tidak valid |
| 404 | ID mahasiswa tidak ditemukan |
| 405 | Method tidak diizinkan (mis. PATCH) |
| 409 | NIM sudah terdaftar |
| 500 | Error server / database |

## Struktur OOP
```
Request -> MahasiswaApiController -> MahasiswaApiService -> MahasiswaRepository -> MySQL
                    |                        |
               Response (JSON)          ApiException (error + status code)
```
- `app/Core/Request.php`, `Response.php`, `ApiException.php`
- `app/controllers/MahasiswaApiController.php`
- `app/services/MahasiswaApiService.php` (validasi + logika)
- `app/repositories/MahasiswaRepository.php` (query PDO prepared statement)
- `app/api/mahasiswa.php` (entry point), routing di `public/index.php`
