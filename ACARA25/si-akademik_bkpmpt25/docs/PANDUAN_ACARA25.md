# Panduan Acara 25 — Authentication, Authorization dan Dashboard Admin

## Perubahan dibanding Acara 24
| Berkas | Perubahan |
|---|---|
| `app/core/Auth.php` | Method baru `requireAdmin()`: token sah + `role = admin`, selain itu 403 |
| `app/controllers/MahasiswaController.php` | `store()`, `update()`, `destroy()` memakai `Auth::requireAdmin()` |
| `public/views/login.html`, `login.js` | Form login, token dan role disimpan di `localStorage` |
| `public/views/dashboard.html`, `dashboard.js` | Halaman dilindungi, total mahasiswa, tabel + Tambah/Ubah/Hapus, Logout |
| `public/views/config.js`, `style.css` | `BASE_URL` dan gaya bersama |
| `database/jadikan_admin.sql` | Menjadikan akun `admin_uji` sebagai admin |
| `postman/...collection.json` | Folder baru "RBAC (Acara 25)"; folder Mahasiswa memakai `{{adminToken}}` |

## Menyiapkan akun admin (sekali saja)
Akun hasil `/register` selalu berrole `user`. Untuk admin:
1. Postman: jalankan **RBAC (Acara 25) > 1. POST register - akun admin_uji** (username `admin_uji`, password `AdminUji123`).
2. phpMyAdmin: pilih `db_workshop` > tab SQL > jalankan `database/jadikan_admin.sql`.

## Alamat halaman
- Login: `http://localhost/BKPM/ACARA25/si-akademik_bkpmpt25/public/views/login.html`
- Dashboard: `.../public/views/dashboard.html` (tanpa login otomatis kembali ke login)
Jika memakai `jalankan.bat`, ubah `BASE_URL` di `public/views/config.js` menjadi `http://localhost:8080`.

## Catatan
- Token berlaku 300 detik (Acara 20). Jika kedaluwarsa, dashboard menampilkan pesan lalu kembali ke login.
- `SEMBUNYIKAN_TOMBOL_UNTUK_USER` di `dashboard.js`: `true` = tombol disembunyikan untuk role user (tugas mandiri 2). Ubah ke `false` sementara untuk membuktikan respons 403 dari tombol (Langkah 6 poin 1), lalu kembalikan ke `true`.
- Endpoint GET `/mahasiswa` tetap publik.


## Catatan revisi (agar konsisten dengan Acara 23 dan 24)
- `MahasiswaController`: NIM wajib 9 digit **angka** (`ctype_digit`) dan NIM duplikat dibalas **422** (perbaikan Acara 23, TC03/TC07/TC17/TC18 yang sempat hilang pada berkas ini).
- `public/index.php`: ditambah pelayan berkas statis khusus `php -S`, sehingga `views/login.html` dan `views/dashboard.html` juga terbuka lewat `jalankan.bat` (sebelumnya hanya lewat XAMPP). Penanganan preflight `OPTIONS` (204) dipertahankan.
- Collection Postman: tes "NIM duplikat" kini mengharapkan 422 dan teks pesan format NIM disesuaikan ("9 digit angka").
- `docs/API_DOCUMENTATION.md` dan `docs/openapi.yaml`: ditambah respons **403** (role bukan admin) pada POST/PUT/DELETE `/mahasiswa`, dan NIM duplikat menjadi 422.
- Hasil uji: Newman 47 request / 246 assertion lulus semua; alur browser (login, RBAC, dashboard, logout) lulus.

## Catatan penting: Acara 25 mengubah hak akses Acara 23
Setelah `requireAdmin()` dipasang, akun biasa (role `user`) tidak dapat lagi menjalankan POST/PUT/DELETE `/mahasiswa`. Collection `Acara23_TestCase` memakai akun role `user`, sehingga TC01, TC03, TC12, TC21, dan test case serupa akan menjawab 403. Itu perilaku yang benar untuk RBAC dan bukan kerusakan.
