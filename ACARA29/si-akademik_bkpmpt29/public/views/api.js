// Acara 27: fungsi bersama untuk seluruh halaman view (login, dashboard, form_tambah, form_edit)
// Acara 29: loading state, umpan balik galat, escaping output (XSS), dan retry otomatis.
// Dimuat setelah config.js (yang mendefinisikan BASE_URL).

function ambilToken() { return localStorage.getItem('token'); }
function ambilRole()  { return localStorage.getItem('role'); }

// Halaman terlindungi: tanpa token kembali ke login
function wajibLogin() {
    if (!ambilToken()) { window.location.href = 'login.html'; }
}

function logout() {
    ['token', 'role', 'username'].forEach((k) => localStorage.removeItem(k));
    window.location.href = 'login.html';
}

// ---------- Acara 29, Langkah 2: escaping output (pencegahan XSS) ----------
// Mengubah karakter khusus HTML menjadi entitas sehingga data dari API tampil sebagai TEKS,
// bukan dieksekusi sebagai tag. Tanda kutip ikut di-escape karena data juga disisipkan ke dalam
// atribut HTML (data-nama="...", alt="..."): tanpa itu, nilai berisi tanda kutip dapat keluar
// dari atribut dan menyuntikkan atribut baru (mis. onfocus=...).
// Catatan: versi modul memakai div.innerText + innerHTML; versi ini setara untuk < > &,
// tetapi tidak bergantung pada DOM dan juga menangani tanda kutip.
const PETA_ESCAPE = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
function escapeHTML(teks) {
    return String(teks ?? '').replace(/[&<>"']/g, (c) => PETA_ESCAPE[c]);
}
// Nama lama dari Acara 27 dipertahankan agar kode yang sudah memakai esc() tetap bekerja
function esc(teks) { return escapeHTML(teks); }

// ---------- Notifikasi visual (Tugas mandiri 1) ----------
let timerNotif = null;

function tampilNotif(pesan, tipe = 'sukses') {
    let el = document.querySelector('#notif');
    if (!el) {
        el = document.createElement('div');
        el.id = 'notif';
        el.setAttribute('role', 'alert');
        document.body.appendChild(el);
    }
    el.className = `notif ${tipe}`;
    el.textContent = pesan;
    el.hidden = false;
    clearTimeout(timerNotif);
    timerNotif = setTimeout(() => { el.hidden = true; }, 4500);
}

// Notifikasi yang harus tampil setelah pindah halaman (mis. setelah Simpan di form lalu kembali ke dashboard)
function notifBerikutnya(pesan, tipe = 'sukses') {
    sessionStorage.setItem('notif', JSON.stringify({ pesan, tipe }));
}
function tampilNotifTertunda() {
    const simpan = sessionStorage.getItem('notif');
    if (!simpan) { return; }
    sessionStorage.removeItem('notif');
    const { pesan, tipe } = JSON.parse(simpan);
    tampilNotif(pesan, tipe);
}

// Pesan kegagalan dari API (403 requireAdmin, 409 NIM kembar, 422 validasi) + detail per field
function pesanGagal(hasil, res) {
    const pesan = (hasil && hasil.message) || 'Terjadi kesalahan';
    // Detail per field ditambahkan hanya bila belum tercakup dalam pesan utama (hindari kalimat ganda)
    const rinci = hasil && hasil.errors ? [...new Set(Object.values(hasil.errors).flat())].filter((d) => !pesan.includes(d)) : [];
    // Kode HTTP hanya disebut untuk galat server (5xx): berguna untuk pelaporan, sedangkan galat 4xx sudah jelas dari pesannya
    const status = res && res.status >= 500 ? ` (HTTP ${res.status})` : '';
    return `${pesan}${rinci.length ? ' ' + rinci.join(' ') : ''}${status}`;
}

// ---------- Acara 29: galat, retry, dan pemanggilan API ----------
// retry: jumlah percobaan ulang otomatis (Tugas mandiri 2); jedaRetryMs: jeda sebelum mengulang;
// timeoutMs: permintaan yang tidak dijawab server dibatalkan agar loading tidak berputar selamanya.
const KONFIG_FETCH = { retry: 1, jedaRetryMs: 800, timeoutMs: 15000 };
const STATUS_SEMENTARA = [502, 503, 504];   // gangguan sementara di sisi server/proxy

class GalatJaringan extends Error {}   // gagal terhubung / waktu habis (setelah retry habis)
class GalatSesi extends Error {}       // 401: token tidak sah/kedaluwarsa (pengguna sudah diarahkan ke login)

const tidur = (ms) => new Promise((selesai) => setTimeout(selesai, ms));

// fetch + timeout + retry otomatis.
// - Kegagalan jaringan (fetch melempar TypeError) diulang sebanyak `retry` kali.
// - Waktu habis dan respons 502/503/504 hanya diulang untuk GET (aman/idempoten). Untuk POST/PUT/DELETE
//   server mungkin sudah memproses permintaan, sehingga mengulangnya berisiko menggandakan efek.
// - Respons HTTP lain (400, 401, 403, 404, 409, 422, 500) bukan masalah jaringan dan tidak diulang.
async function fetchDenganRetry(url, opsi = {}, pengaturan = {}) {
    const { retry, jedaRetryMs, timeoutMs } = { ...KONFIG_FETCH, ...pengaturan };
    const { onRetry } = pengaturan;
    const metode = (opsi.method || 'GET').toUpperCase();

    for (let percobaan = 0; ; percobaan++) {
        const kontrol = new AbortController();
        const timer = setTimeout(() => kontrol.abort(), timeoutMs);
        let bolehUlang = false;
        try {
            const res = await fetch(url, { ...opsi, signal: kontrol.signal });
            if (!(metode === 'GET' && STATUS_SEMENTARA.includes(res.status) && percobaan < retry)) {
                return res;
            }
            bolehUlang = true;
        } catch (error) {
            const habisWaktu = error.name === 'AbortError';
            if (percobaan >= retry || (habisWaktu && metode !== 'GET')) {
                throw new GalatJaringan(habisWaktu
                    ? 'Server tidak merespons (waktu habis). Coba lagi beberapa saat.'
                    : 'Tidak dapat terhubung ke server. Periksa koneksi internet dan pastikan Apache berjalan.');
            }
            bolehUlang = true;
        } finally {
            clearTimeout(timer);
        }
        if (bolehUlang) {
            if (onRetry) { onRetry(percobaan + 1); }
            await tidur(jedaRetryMs);
        }
    }
}

// Membaca JSON dengan aman. Jika server mengirim bukan JSON (halaman error Apache/PHP), kembalikan null
// agar pemanggil dapat menampilkan pesan yang jelas, bukan gagal senyap atau SyntaxError membingungkan.
async function bacaJson(res) {
    try { return await res.json(); } catch (error) { return null; }
}

// Header Authorization dikirim bila auth = true. Content-Type hanya diatur untuk JSON: untuk FormData,
// browser harus menambahkan boundary multipart sendiri (jangan diatur manual).
// Mengembalikan { res, hasil } dan selalu berisi hasil.success/hasil.message (juga untuk respons non-JSON).
async function panggilApi(path, opsi = {}, pengaturan = {}) {
    const { auth = true, ...pengaturanFetch } = pengaturan;
    const headers = { ...(opsi.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }) };
    if (auth) { headers['Authorization'] = `Bearer ${ambilToken()}`; }

    const res = await fetchDenganRetry(`${BASE_URL}${path}`, { ...opsi, headers }, pengaturanFetch);
    let hasil = await bacaJson(res);

    if (auth && res.status === 401) {   // token kedaluwarsa/tidak sah
        alert('Sesi berakhir, silakan login kembali.');
        logout();
        throw new GalatSesi('Tidak terautentikasi');
    }
    if (hasil === null) {
        hasil = { success: false, message: 'Server mengirim respons yang tidak dapat dibaca', data: null };
    }
    return { res, hasil };
}

// Pesan untuk pengguna dari sebuah galat; null bila tidak perlu ditampilkan (GalatSesi sudah ditangani)
function pesanDariGalat(error) {
    if (error instanceof GalatSesi) { return null; }
    if (error instanceof GalatJaringan) { return error.message; }
    return 'Terjadi kesalahan tak terduga pada aplikasi.';
}

function tampilGalat(error) {
    console.error(error);
    const pesan = pesanDariGalat(error);
    if (pesan) { tampilNotif(pesan, 'galat'); }
}

// Dipakai sebagai onRetry: memberi tahu pengguna bahwa aplikasi mencoba ulang secara otomatis
function infoRetry() { tampilNotif('Koneksi bermasalah, mencoba ulang...', 'info'); }

// ---------- Acara 29, Langkah 3: umpan balik tombol saat permintaan berlangsung ----------
function setLoadingTombol(tombol, aktif, teksLoading = 'Memproses...') {
    if (!tombol) { return; }
    if (aktif) {
        if (tombol.dataset.teksAsli === undefined) { tombol.dataset.teksAsli = tombol.textContent; }
        tombol.textContent = teksLoading;
        tombol.disabled = true;
        tombol.classList.add('memuat');
        tombol.setAttribute('aria-busy', 'true');
    } else {
        if (tombol.dataset.teksAsli !== undefined) {
            tombol.textContent = tombol.dataset.teksAsli;
            delete tombol.dataset.teksAsli;
        }
        tombol.disabled = false;
        tombol.classList.remove('memuat');
        tombol.removeAttribute('aria-busy');
    }
}

// Menampilkan galat validasi dari server (errors: { nim: ['...'] }) tepat di bawah field terkait
function tampilkanGalatServer(form, hasil) {
    if (!hasil || !hasil.errors) { return; }
    Object.entries(hasil.errors).forEach(([nama, daftar]) => {
        const input = form.querySelector(`[name="${nama}"]`);
        if (input) { setGalat(input, [].concat(daftar).join(' ')); }
    });
}

// ---------- Validasi sisi View (Tugas mandiri 2) ----------
// Lapisan tambahan di luar validasi Controller/Model; backend tetap penjaga utama.
function bersihkanGalat(form) {
    form.querySelectorAll('.galat').forEach((e) => { e.textContent = ''; });
    form.querySelectorAll('input.invalid').forEach((e) => e.classList.remove('invalid'));
}

function setGalat(input, pesan) {
    input.classList.add('invalid');
    const el = input.parentElement.querySelector('.galat');
    if (el) { el.textContent = pesan; }
}

// Mengembalikan true bila seluruh isian valid
function validasiFormMahasiswa(form) {
    bersihkanGalat(form);
    let valid = true;
    const nim = form.querySelector('[name=nim]');
    const nama = form.querySelector('[name=nama]');
    const prodi = form.querySelector('[name=prodi]');
    const foto = form.querySelector('[name=foto]');

    [[nim, 'NIM'], [nama, 'Nama'], [prodi, 'Program studi']].forEach(([input, label]) => {
        if (input.value.trim() === '') { setGalat(input, `${label} wajib diisi`); valid = false; }
    });
    // Aturan yang sama dengan MahasiswaController::validateFormat(): NIM tepat 9 karakter
    if (nim.value.trim() !== '' && !/^[0-9]{9}$/.test(nim.value.trim())) {
        setGalat(nim, 'NIM harus terdiri dari 9 digit angka'); valid = false;
    }
    if (foto && foto.files.length > 0) {
        const f = foto.files[0];
        if (!['image/jpeg', 'image/png'].includes(f.type)) { setGalat(foto, 'Foto harus berformat JPG atau PNG'); valid = false; }
        else if (f.size > 2 * 1024 * 1024) { setGalat(foto, 'Ukuran foto maksimal 2 MB'); valid = false; }
    }
    return valid;
}

// Menyusun FormData dari form (foto hanya dikirim bila dipilih)
function susunFormData(form) {
    const fd = new FormData();
    ['nim', 'nama', 'prodi'].forEach((n) => fd.append(n, form.querySelector(`[name=${n}]`).value.trim()));
    const foto = form.querySelector('[name=foto]');
    if (foto && foto.files.length > 0) { fd.append('foto', foto.files[0]); }
    return fd;
}
