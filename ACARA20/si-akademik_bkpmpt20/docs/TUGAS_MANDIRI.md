# Tugas Praktik Mandiri - Acara 20

## Tugas 1. Mengapa JWT stateless tidak punya logout di sisi server?

**Pembahasan.** Pada sesi PHP biasa, server menyimpan data login (di file/database sesi), sehingga logout cukup menghapus sesi itu dan sesi tersebut langsung tidak berlaku. JWT bekerja berbeda: seluruh informasi yang dibutuhkan (`id`, `role`, `exp`) sudah ada di dalam token dan dijamin oleh tanda tangan HMAC. Pada setiap request, server hanya memeriksa dua hal: tanda tangan cocok dan `exp` belum lewat. Server tidak menyimpan daftar token yang pernah dikeluarkan, jadi tidak ada data di sisi server yang bisa dihapus untuk "mencabut" sebuah token. Selama signature benar dan waktunya belum habis, token tetap diterima, siapa pun yang membawanya.

Akibatnya, "logout" pada JWT murni hanya berarti **client membuang token** (misalnya dari memori atau penyimpanan aplikasi). Jika token itu sempat disalin atau dicuri, token tetap bisa dipakai sampai `exp`. Inilah harga dari sifat stateless: server ringan dan mudah di-scale karena tidak perlu lookup sesi, tetapi kehilangan kemampuan pencabutan seketika.

**Mekanisme tambahan yang dapat dipakai:**

| Mekanisme | Cara kerja | Konsekuensi |
|---|---|---|
| Blacklist token | Saat logout, simpan `jti` (ID unik token) atau hash token di tabel, sampai `exp`. `validateToken()` menolak token yang ada di daftar. | Efektif, tetapi tiap request butuh pengecekan database sehingga sebagian sifat stateless hilang. |
| Masa berlaku pendek + refresh token | Access token berumur beberapa menit; refresh token disimpan di database dan bisa dicabut. | Jendela penyalahgunaan kecil; logout = mencabut refresh token. Implementasi lebih rumit. |
| Versi token per pengguna | Kolom `token_version` di tabel `users`, ikut masuk payload. Logout/ganti password menaikkan versi, token versi lama ditolak. | Mencabut semua token satu pengguna sekaligus; perlu satu query per request. |
| Mengganti `SECRET` | Semua token yang ada langsung tidak valid. | Terlalu kasar: semua pengguna ikut logout. |

**Kesimpulan untuk laporan.** JWT stateless tidak memiliki proses logout di sisi server karena server tidak menyimpan state token; keabsahan token ditentukan hanya oleh signature dan waktu kedaluwarsa. Logout sungguhan memerlukan mekanisme tambahan seperti blacklist token, refresh token, atau versi token, yang masing-masing menukar sebagian sifat stateless dengan kemampuan mencabut akses. Pada project ini yang dipakai adalah pendekatan paling sederhana: masa berlaku diperpendek (Tugas 2) sehingga token yang bocor hanya berlaku singkat, sedangkan logout dilakukan client dengan membuang token.

## Tugas 2. Masa berlaku token menjadi 5 menit

Perubahan di `app/core/Auth.php`:
```php
const TOKEN_TTL = 300;                          // 5 menit (sebelumnya 3600 = 1 jam)
...
$payload['exp'] = time() + self::TOKEN_TTL;     // di dalam generateToken()
```
`login()` juga mengirim `expires_in: 300` agar client tahu kapan harus login ulang.

**Perilaku yang diharapkan saat diuji:**
1. Request ke endpoint terlindungi sebelum menit ke-5 -> diterima.
2. Request dengan token yang sama setelah lewat 5 menit -> `401` "Token tidak valid atau kedaluwarsa", walaupun tanda tangannya masih benar, karena pemeriksaan `exp < time()` gagal.
3. Login ulang menghasilkan token baru (`exp` baru) dan akses pulih.

Isi hasil pengamatanmu sendiri di laporan (jam login, jam percobaan, status code, screenshot Postman), karena bagian ini harus dari pengujian langsung di XAMPP.

**Catatan.** Pesan error untuk token rusak dan token kedaluwarsa sengaja sama. Bila ingin membedakan, `validateToken()` dapat mengembalikan alasan kegagalan, tetapi untuk keamanan pesan yang seragam lebih aman.
