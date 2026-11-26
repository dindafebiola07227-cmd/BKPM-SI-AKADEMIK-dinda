# Panduan Acara 29 — Integrasi Backend-Frontend (Kualitas View)

## Perubahan dibanding Acara 28
| Berkas | Perubahan |
|---|---|
| `public/views/api.js` | `escapeHTML()`, `fetchDenganRetry()` (timeout + retry), `bacaJson()`, `panggilApi()` baru, `setLoadingTombol()`, `tampilkanGalatServer()`, `pesanGagal()` diperbarui |
| `public/views/dashboard.js` | `renderTabel()`, loading + galat + tombol "Coba lagi", Hapus dengan try-catch-finally |
| `public/views/form_tambah.js`, `form_edit.js` | try-catch-finally, tombol nonaktif selama kirim, galat per-field dari server; form_edit: loading saat memuat data |
| `public/views/login.js`, `login.html` | loading pada tombol Masuk, galat tanpa `alert()`, retry |
| `public/views/form_edit.html`, `style.css` | area status memuat, spinner, gaya notifikasi `info` |
| `public/views/config.js` | `BASE_URL` ke `ACARA29/si-akademik_bkpmpt29` |
| Backend (`app/`) | Tidak ada perubahan |

## Catatan desain
- **`escapeHTML` memakai `replace`, bukan `innerText`.** Versi `innerText` + `innerHTML` tidak meng-escape tanda kutip. Data juga disisipkan ke atribut (`data-nama="..."`, `alt="..."`), sehingga nama berisi `"` dapat menyuntikkan atribut baru (mis. `onmouseover`). Versi `replace` menangani `& < > " '`.
- **Aturan retry** (`fetchDenganRetry`):
  - kegagalan jaringan (`fetch` melempar `TypeError`) diulang 1x untuk semua method;
  - waktu habis (15 detik) dan respons 502/503/504 hanya diulang untuk `GET`, karena POST/PUT/DELETE mungkin sudah diproses server;
  - respons HTTP lain (400, 401, 403, 404, 409, 422, 500) bukan masalah jaringan dan tidak diulang.
  Pengaturan ada di `KONFIG_FETCH` (`api.js`).
- **Tombol sukses tetap nonaktif** setelah berhasil menyimpan sampai halaman berpindah (mencegah klik ganda); pada galat, tombol diaktifkan kembali di blok `finally`.

## Menjalankan
1. Letakkan di `C:\xampp\htdocs\BKPM\ACARA29\si-akademik_bkpmpt29`; Apache dan MySQL aktif; database `db_workshopku` (sama seperti Acara 26–28).
2. Buka `http://localhost/BKPM/ACARA29/si-akademik_bkpmpt29/public/views/login.html`.
3. Bila folder Anda berbeda, ubah `BASE_URL` di `public/views/config.js`.

## Pengujian (Langkah 4)
Alur lengkap: login → dashboard → tambah (dengan foto) → ubah → hapus; amati loading dan notifikasi tiap tahap.

Simulasi galat lewat DevTools (F12 → Network):
| Skenario | Cara | Hasil yang diharapkan |
|---|---|---|
| Loading lambat | throttling **Slow 3G** | "Memuat data..." + spinner; tombol "Menyimpan..." |
| Jaringan putus | centang **Offline**, muat ulang dashboard | "Mencoba ulang", lalu "Gagal memuat data..." + **Coba lagi** |
| Gangguan sesaat | Offline lalu cepat hilangkan centang saat retry | data tampil tanpa galat |
| Server error | matikan MySQL, muat dashboard | pesan galat + status HTTP 500 |
| XSS | tambah mahasiswa bernama `<b>Coba</b>` | tampil sebagai teks `<b>Coba</b>` |
| XSS atribut | nama `" style="outline:4px solid orange" onmouseover="alert(1)` | tampil sebagai teks; tombol Hapus tidak berubah |
