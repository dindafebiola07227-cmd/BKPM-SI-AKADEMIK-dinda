# Panduan Pengujian Acara 21 (Dokumentasi API)

## A. Persiapan
1. Letakkan folder di `C:\xampp\htdocs\BKPM\ACARA21\si-akademik_bkpmpt21\` (atau jalankan `jalankan.bat` untuk memakai `php -S`).
2. XAMPP: nyalakan **Apache** dan **MySQL**. Database `db_workshop` dipakai apa adanya.
3. Cek di browser: `http://localhost/BKPM/ACARA21/si-akademik_bkpmpt21/public` harus tampil JSON "API ... berjalan".

## B. Import ke Postman
Import semua file di folder `postman/`, lalu pilih environment **Local** (atau **Local (php -S 8080)** bila memakai `jalankan.bat`).

## C. Mengambil contoh response (Langkah 5)
Kirim tiap request, lalu **Save Response** -> **Save as example**.

| Request | Contoh sukses | Contoh gagal |
|---|---|---|
| POST /register | 201 | 409 (username sama) |
| POST /login | 200 | 401 (password salah) |
| GET /mahasiswa | 200 | 404 (`?id=999999`) |
| POST /mahasiswa (Bearer token) | 201 | 401 (tanpa token) |
| PUT /mahasiswa?id= (Bearer token) | 200 | 404 (`?id=999999`) |
| DELETE /mahasiswa?id= (Bearer token) | 200 | 401 (tanpa token) |

Untuk request yang memakai token: tab **Authorization** -> **Bearer Token** -> tempel `data.token` dari login (berlaku 5 menit).

## D. Menerbitkan dokumentasi
Pada Collection klik **View Documentation**, publikasikan, lalu simpan tautannya sebagai lampiran laporan.

## E. Git (log aktivitas)
```
git add .
git commit -m "Acara 21: dokumentasi API (API_DOCUMENTATION.md, Postman)"
git log --oneline
```

## F. Screenshot untuk laporan
Struktur folder (ada `docs/API_DOCUMENTATION.md`), Peta Router, dokumentasi `/login`, dokumentasi `/mahasiswa`, kolom Description, tab Examples, halaman View Documentation, dan tabel ringkasan endpoint.
