// Acara 25 - Langkah 4-6 + tugas mandiri (Logout dan sembunyikan tombol untuk role user)
// Acara 26 - Langkah 5: upload foto mahasiswa memakai FormData (multipart/form-data)

// Untuk pengujian Langkah 6 poin 1 (tombol mendapat 403 dari Auth::requireAdmin()):
// ubah menjadi false sementara agar tombol Tambah/Ubah/Hapus tetap tampil untuk role user.
const SEMBUNYIKAN_TOMBOL_UNTUK_USER = true;

const token = localStorage.getItem('token');
const role = localStorage.getItem('role');
const isAdmin = role === 'admin';
const tampilkanAksi = isAdmin || !SEMBUNYIKAN_TOMBOL_UNTUK_USER;

const tbody = document.querySelector('#tabel-mahasiswa tbody');
const form = document.querySelector('#form-mhs');
const btnTambah = document.querySelector('#btn-tambah');
const inputFoto = document.querySelector('#mhs-foto');
const MAKS_FOTO = 2 * 1024 * 1024; // 2 MB, sama dengan batas di MahasiswaController
let daftarMahasiswa = [];

function esc(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    localStorage.removeItem('username');
    window.location.href = 'login.html';
}

function tampilkanPesan(teks, kelas = '') {
    const kolom = tampilkanAksi ? 5 : 4;
    tbody.innerHTML = `<tr><td colspan="${kolom}" class="pesan ${kelas}">${esc(teks)}</td></tr>`;
}

// Memanggil API dengan header Authorization; 401 (token kedaluwarsa) -> kembali ke login
async function panggilApi(path, opsi = {}) {
    // Acara 26: untuk FormData, Content-Type TIDAK boleh diisi manual; browser menambahkan
    // "multipart/form-data; boundary=..." sendiri. Jika dipaksa JSON, PHP tidak bisa membaca berkasnya.
    const headers = { 'Authorization': 'Bearer ' + token };
    if (!(opsi.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
    }
    const res = await fetch(`${BASE_URL}${path}`, { ...opsi, headers });
    const hasil = await res.json();

    if (res.status === 401) {
        alert('Sesi berakhir, silakan login kembali.');
        logout();
        throw new Error('Tidak terautentikasi');
    }
    return { res, hasil };
}

async function muatDataMahasiswa() {
    tampilkanPesan('Memuat data...');
    try {
        const res = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await res.json();
        if (!res.ok || !hasil.success) {
            throw new Error(hasil.message || `HTTP ${res.status}`);
        }

        daftarMahasiswa = Array.isArray(hasil.data) ? hasil.data : [];
        // Langkah 5: ringkasan jumlah total mahasiswa dari MahasiswaController::index()
        document.querySelector('#total-mhs').textContent = daftarMahasiswa.length;

        if (daftarMahasiswa.length === 0) {
            tampilkanPesan('Belum ada data');
            return;
        }

        tbody.innerHTML = daftarMahasiswa.map((m) => {
            const aksi = tampilkanAksi
                ? `<td class="aksi"><button type="button" data-aksi="ubah" data-id="${esc(m.id)}">Ubah</button>` +
                  `<button type="button" class="merah" data-aksi="hapus" data-id="${esc(m.id)}">Hapus</button></td>`
                : '';
            const foto = m.foto
                ? `<img class="foto-mhs" src="${BASE_URL}/uploads/${encodeURIComponent(m.foto)}" alt="Foto ${esc(m.nama)}">`
                : '<span class="tanpa-foto">-</span>';
            return `<tr><td>${foto}</td><td>${esc(m.nim)}</td><td>${esc(m.nama)}</td><td>${esc(m.prodi)}</td>${aksi}</tr>`;
        }).join('');
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan('Gagal memuat data. Periksa server API.', 'error');
    }
}

function bukaForm(m = null) {
    document.querySelector('#mhs-id').value = m ? m.id : '';
    document.querySelector('#mhs-nim').value = m ? m.nim : '';
    document.querySelector('#mhs-nama').value = m ? m.nama : '';
    document.querySelector('#mhs-prodi').value = m ? m.prodi : '';
    inputFoto.value = ''; // input file tidak boleh diisi lewat skrip; cukup dikosongkan
    document.querySelector('#petunjuk-foto').textContent = m && m.foto
        ? 'Kosongkan jika tidak ingin mengganti foto saat ini.'
        : '';
    form.hidden = false;
    document.querySelector('#mhs-nim').focus();
}

// Pesan kesalahan dari API (403 dari requireAdmin, 409/422 dari validasi) ditampilkan apa adanya
function laporkanGagal(hasil) {
    const detail = hasil.errors ? Object.values(hasil.errors).flat().join('\n') : '';
    alert(detail || hasil.message);
}

async function simpanMahasiswa(e) {
    e.preventDefault();
    const id = document.querySelector('#mhs-id').value;

    // Pemeriksaan awal di browser (hanya kenyamanan; validasi sebenarnya tetap di server)
    const berkas = inputFoto.files[0];
    if (berkas && berkas.size > MAKS_FOTO) {
        alert('Ukuran berkas maksimal 2MB');
        return;
    }

    // Acara 26 - Langkah 5: request multipart tidak bisa dikirim sebagai JSON biasa, gunakan FormData
    const formData = new FormData();
    formData.append('nim', document.querySelector('#mhs-nim').value.trim());
    formData.append('nama', document.querySelector('#mhs-nama').value.trim());
    formData.append('prodi', document.querySelector('#mhs-prodi').value.trim());
    if (berkas) {
        formData.append('foto', berkas);
    }

    // Tambah  : POST /mahasiswa
    // Ubah    : POST /mahasiswa?id=..&_method=PUT (PHP tidak mengisi $_FILES untuk PUT multipart; lihat Router)
    const path = id ? `/mahasiswa?id=${encodeURIComponent(id)}&_method=PUT` : '/mahasiswa';

    try {
        const { res, hasil } = await panggilApi(path, { method: 'POST', body: formData });
        if (!res.ok || !hasil.success) {
            laporkanGagal(hasil);
            return;
        }
        form.hidden = true;
        muatDataMahasiswa();
    } catch (error) {
        if (error.message !== 'Tidak terautentikasi') { alert('Gagal menghubungi server.'); }
    }
}

async function hapusMahasiswa(id) {
    if (!confirm('Hapus data mahasiswa ini?')) { return; }
    try {
        const { res, hasil } = await panggilApi(`/mahasiswa?id=${encodeURIComponent(id)}`, { method: 'DELETE' });
        if (!res.ok || !hasil.success) {
            laporkanGagal(hasil);
            return;
        }
        muatDataMahasiswa();
    } catch (error) {
        if (error.message !== 'Tidak terautentikasi') { alert('Gagal menghubungi server.'); }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('#info-user').textContent = localStorage.getItem('username') || '-';
    document.querySelector('#info-role').textContent = role || '-';

    // Tugas mandiri 1: Logout
    document.querySelector('#btn-logout').addEventListener('click', logout);

    // Tugas mandiri 2: sembunyikan Tambah/Ubah/Hapus untuk role user (proteksi utama tetap di backend)
    if (!tampilkanAksi) {
        btnTambah.hidden = true;
        document.querySelector('.kolom-aksi').hidden = true;
    }

    btnTambah.addEventListener('click', () => bukaForm());
    document.querySelector('#btn-batal').addEventListener('click', () => { form.hidden = true; });
    form.addEventListener('submit', simpanMahasiswa);

    tbody.addEventListener('click', (e) => {
        const tombol = e.target.closest('button[data-aksi]');
        if (!tombol) { return; }
        const id = tombol.dataset.id;
        if (tombol.dataset.aksi === 'ubah') {
            bukaForm(daftarMahasiswa.find((m) => String(m.id) === id));
        } else {
            hapusMahasiswa(id);
        }
    });

    muatDataMahasiswa();
});
