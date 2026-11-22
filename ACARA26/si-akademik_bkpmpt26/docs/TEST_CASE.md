
# LAPORAN PENGUJIAN API - ACARA 23

## 1. Ruang Lingkup Pengujian

Pengujian dilakukan terhadap seluruh Controller yang telah dibangun pada Acara 17 sampai Acara 20, yaitu AuthController dan MahasiswaController.

Endpoint yang diuji meliputi:

| No | Endpoint | Method | Controller |
|---|---|---|---|
| 1 | /register | POST | AuthController |
| 2 | /login | POST | AuthController |
| 3 | /mahasiswa | GET | MahasiswaController |
| 4 | /mahasiswa | POST | MahasiswaController |
| 5 | /mahasiswa?id={id} | PUT | MahasiswaController |
| 6 | /mahasiswa?id={id} | DELETE | MahasiswaController |

## 2. Test Case dengan Equivalence Partitioning

### A. Pengujian Register dan Login

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| TC 01 | NIM valid dan lengkap | nim=220100123 | 201 Created | Belum diuji | - |
| TC 02 | NIM kosong | nim="" | 400/422 | Belum diuji | - |
| TC 03 | NIM duplikat | NIM milik mahasiswa lain | 422 Unprocessable | Belum diuji | - |
| TC 04 | Login dengan kredensial benar | Username dan password benar | 200 OK, token diterima | Belum diuji | - |
| TC 05 | Login dengan password salah | Password salah | 401 Unauthorized | Belum diuji | - |
| TC 06 | Tambah mahasiswa tanpa token | Tanpa Authorization | 401 Unauthorized | Belum diuji | - |

### B. Pengujian MahasiswaController::update

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| TC 07 | Update data valid | ID valid dan seluruh field terisi | 200 OK | Belum diuji | - |
| TC 08 | Update menggunakan ID tidak valid | id=abc | 400 Bad Request | Belum diuji | - |
| TC 09 | Update ID tidak ditemukan | id=999999 | 404 Not Found | Belum diuji | - |
| TC 10 | Update dengan field NIM kosong | nim="" | 400 Bad Request | Belum diuji | - |
| TC 11 | Update menggunakan NIM milik mahasiswa lain | NIM duplikat | 409 Conflict | Belum diuji | - |

### C. Pengujian MahasiswaController::destroy

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| TC 12 | Delete menggunakan ID tidak valid | id=abc | 400 Bad Request | Belum diuji | - |
| TC 13 | Delete ID tidak ditemukan | id=999999 | 404 Not Found | Belum diuji | - |
| TC 14 | Delete menggunakan ID valid | ID mahasiswa tersedia | 200 OK | Belum diuji | - |

## 3. Hasil Eksekusi Pengujian

Pengujian dilakukan menggunakan aplikasi Postman. Hasil aktual dicatat berdasarkan kode status HTTP yang diterima dari server.

Keterangan:
- Pass: Hasil aktual sesuai dengan hasil yang diharapkan.
- Fail: Hasil aktual tidak sesuai dengan hasil yang diharapkan.

## 4. Kesimpulan Pengujian

Kesimpulan dibuat setelah seluruh test case dieksekusi. Apabila ditemukan pengujian berstatus Fail, dilakukan perbaikan pada Model atau Controller yang relevan dan dilanjutkan dengan pengujian ulang (retest).
