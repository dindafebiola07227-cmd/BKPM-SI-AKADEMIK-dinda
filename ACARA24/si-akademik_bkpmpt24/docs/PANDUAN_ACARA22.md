# Panduan penyelesaian Acara 22

## Acuan BKPM
Sesuai BKPM Workshop SI Web Server Acara 22 halaman 144–145:
1. Membuat `docs/openapi.yaml` dengan OpenAPI 3.0.
2. Mendefinisikan `POST /login`.
3. Mendefinisikan `GET /mahasiswa` dengan query `id` opsional dan `POST /mahasiswa`.
4. Memvisualisasikan YAML melalui https://editor.swagger.io/.
5. Tugas mandiri: melengkapi PUT dan DELETE `/mahasiswa`, menambahkan `/register`, dan menambahkan `components.securitySchemes` bertipe `http`/`bearer`.
6. Menyimpan tangkapan layar hasil visualisasi pada folder `docs/`.

## Validasi file
- Pastikan `openapi.yaml` berada di folder `docs`.
- Pastikan server URL sesuai folder Acara 22.
- Pastikan Swagger Editor menerima YAML tanpa error.
- Pastikan Swagger menampilkan Register, Login, dan GET/POST/PUT/DELETE Mahasiswa.
- Pastikan ikon/opsi Authorize tersedia karena `bearerAuth` telah didefinisikan.
- Operasi POST, PUT, dan DELETE mahasiswa meminta Bearer Token; GET mahasiswa dan autentikasi tidak.

## Mengambil screenshot bukti (harus dari tampilan asli)
1. Buka https://editor.swagger.io/ pada browser.
2. Buka `docs/openapi.yaml` di VS Code, salin seluruh isinya.
3. Tempelkan ke panel kiri Swagger Editor.
4. Pastikan panel kanan menampilkan dokumentasi tanpa error.
5. Ambil screenshot dengan `Win + Shift + S`.
6. Simpan gambar ke folder `docs` dengan nama `swagger_acara22.png`.

Screenshot tidak disertakan sebagai gambar buatan karena harus membuktikan hasil yang benar-benar tampil pada browser milik pengguna.

## Format laporan menurut BKPM halaman 145
- Kertas A4, format akhir PDF.
- Struktur: Cover, Pendahuluan, Hasil Praktik, Kesimpulan, Daftar Pustaka.
- Identitas: NIM, Nama, Golongan, Tugas Minggu Ke-X.
- Nama file: `ACARA-22_GOL_NIM_NAMA.pdf`.
- Pengumpulan: https://slearn-jti.com/.

Masukkan screenshot asli Swagger ke bagian Hasil Praktik sebelum membuat PDF laporan.
