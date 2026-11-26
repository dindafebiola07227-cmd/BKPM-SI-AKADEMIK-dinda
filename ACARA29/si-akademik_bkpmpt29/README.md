# BKPM Acara 29 — Integrasi Backend-Frontend (Loading State, Error Feedback, Escaping XSS)

Melanjutkan Acara 28. Seluruh View (`login`, `dashboard`, `form_tambah`, `form_edit`) disempurnakan sebagai persiapan deployment produksi (Acara 30):

- indikator **loading** pada tabel, form, dan tombol;
- **umpan balik galat** yang jelas (jaringan putus, waktu habis, HTTP 4xx/5xx, respons bukan JSON);
- **escaping output** (`escapeHTML`) untuk mencegah XSS;
- **retry otomatis** satu kali untuk kegagalan jaringan sementara.

Panduan lengkap: `docs/PANDUAN_ACARA29.md`. Mulai dari `public/views/login.html`.
Backend (`app/`) tidak diubah; database tetap `db_workshopku`.
