# Panduan Pengujian Acara 24 (Integrasi API dengan Frontend)

## A. Persiapan
1. Letakkan folder di `C:\xampp\htdocs\BKPM\ACARA24\si-akademik_bkpmpt24\`.
2. Nyalakan **Apache** dan **MySQL** di XAMPP. Database `db_workshop` dipakai apa adanya (tidak perlu import ulang).
3. Cek API: buka `http://localhost/BKPM/ACARA24/si-akademik_bkpmpt24/public/`. Harus tampil JSON "API ... (Acara 24) berjalan".

## B. Langkah 5 modul: pengujian tampilan view
1. Buka `http://localhost/BKPM/ACARA24/si-akademik_bkpmpt24/public/views/index.html` (berkas statis, tidak lewat Router).
2. Bandingkan isi tabel dengan `GET /mahasiswa` (buka `.../public/mahasiswa` di tab lain atau Postman). Jumlah dan isi baris harus sama.
3. Tekan **F12**, buka tab **Network**, muat ulang (F5), klik request `mahasiswa`, lihat **Response Headers**: harus ada `Access-Control-Allow-Origin: *`.
4. Buka tab **Console**: tidak boleh ada error berwarna merah (khususnya error CORS).

## C. Tugas Praktik Mandiri (untuk screenshot)
**1. Indikator "Memuat data..."** Di tab **Console** (F12), tempel lalu Enter:
```js
var asli = window.fetch; window.fetch = (...a) => new Promise(r => setTimeout(() => r(asli(...a)), 6000)); muatDataMahasiswa();
```
Selama 6 detik tabel menampilkan "Memuat data...", lalu berisi data. Segarkan halaman (F5) untuk mengembalikan ke normal.

**2. "Belum ada data".** Di tab **Console**, tempel lalu Enter (tidak mengubah database):
```js
window.fetch = async () => new Response(JSON.stringify({success: true, message: 'ok', data: []})); muatDataMahasiswa();
```
Tabel menampilkan "Belum ada data". Segarkan halaman (F5) untuk kembali normal.

## D. Jika bermasalah
| Gejala | Penyebab | Solusi |
|---|---|---|
| Tabel tetap "Memuat data..." atau pesan gagal | Apache/MySQL mati atau alamat salah | Cek A.2 dan A.3 |
| Halaman 404 | Folder tidak di `...\ACARA24\si-akademik_bkpmpt24\` | Cek letak folder |
| Error CORS di Console | `index.php` belum berisi header CORS | Pakai `public/index.php` dari proyek ini |
| Tabel kosong padahal data ada | Berkas lama tersimpan di cache | Tekan Ctrl + F5 |
