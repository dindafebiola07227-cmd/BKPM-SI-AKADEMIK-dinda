// Acara 27: fungsi bersama untuk seluruh halaman view (dashboard, form_tambah, form_edit)
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

// Mencegah karakter HTML pada data masuk sebagai markup (XSS)
function esc(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

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
function pesanGagal(hasil) {
    const detail = hasil && hasil.errors ? ' ' + Object.values(hasil.errors).flat().join(' ') : '';
    return `${(hasil && hasil.message) || 'Terjadi kesalahan'}${detail}`;
}

// ---------- Pemanggilan API ----------
// Header Authorization selalu dikirim. Content-Type hanya diatur untuk JSON: untuk FormData,
// browser harus menambahkan boundary multipart sendiri (jangan diatur manual).
async function panggilApi(path, opsi = {}) {
    const res = await fetch(`${BASE_URL}${path}`, {
        ...opsi,
        headers: {
            ...(opsi.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }),
            'Authorization': `Bearer ${ambilToken()}`,
        },
    });
    const hasil = await res.json();

    if (res.status === 401) {          // token kedaluwarsa/tidak sah
        alert('Sesi berakhir, silakan login kembali.');
        logout();
        throw new Error('Tidak terautentikasi');
    }
    return { res, hasil };
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
