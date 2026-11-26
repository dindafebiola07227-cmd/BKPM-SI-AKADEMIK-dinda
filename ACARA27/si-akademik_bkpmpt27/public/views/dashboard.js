// Acara 25: dashboard, Logout, tombol disembunyikan untuk role user
// Acara 26: kolom foto
// Acara 27: Tambah/Ubah membuka form terpisah, Hapus langsung dari tabel, notifikasi visual
// (fungsi bersama ada di api.js)

// Untuk menguji 403 dari tombol (Acara 25): ubah menjadi false sementara agar tombol tetap tampil untuk role user.
const SEMBUNYIKAN_TOMBOL_UNTUK_USER = true;

const isAdmin = ambilRole() === 'admin';
const tampilkanAksi = isAdmin || !SEMBUNYIKAN_TOMBOL_UNTUK_USER;
const tbody = document.querySelector('#tabel-mahasiswa tbody');

function tampilkanPesan(teks, kelas = '') {
    const kolom = tampilkanAksi ? 5 : 4;
    tbody.innerHTML = `<tr><td colspan="${kolom}" class="pesan ${kelas}">${esc(teks)}</td></tr>`;
}

async function muatDataMahasiswa() {
    tampilkanPesan('Memuat data...');
    try {
        const res = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await res.json();
        if (!res.ok || !hasil.success) {
            throw new Error(hasil.message || `HTTP ${res.status}`);
        }

        const daftar = Array.isArray(hasil.data) ? hasil.data : [];
        document.querySelector('#total-mhs').textContent = daftar.length;

        if (daftar.length === 0) {
            tampilkanPesan('Belum ada data');
            return;
        }

        tbody.innerHTML = daftar.map((m) => {
            const foto = m.foto
                ? `<img class="foto" src="${BASE_URL}/uploads/${encodeURIComponent(m.foto)}" alt="Foto ${esc(m.nama)}">`
                : '-';
            // Langkah 2 dan 3: Ubah membuka form_edit.html?id=..., Hapus memanggil hapusData(id)
            const aksi = tampilkanAksi
                ? `<td class="aksi"><a class="tombol kecil" href="form_edit.html?id=${encodeURIComponent(m.id)}">Ubah</a>` +
                  `<button type="button" class="merah" data-aksi="hapus" data-id="${esc(m.id)}" data-nama="${esc(m.nama)}">Hapus</button></td>`
                : '';
            return `<tr><td>${foto}</td><td>${esc(m.nim)}</td><td>${esc(m.nama)}</td><td>${esc(m.prodi)}</td>${aksi}</tr>`;
        }).join('');
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan('Gagal memuat data. Periksa server API.', 'error');
    }
}

// Langkah 3: hapus data; tabel dimuat ulang melalui fetch tanpa reload halaman penuh
async function hapusData(id, nama) {
    if (!confirm(`Hapus data ${nama || 'ini'}?`)) { return; }
    try {
        const { res, hasil } = await panggilApi(`/mahasiswa?id=${encodeURIComponent(id)}`, { method: 'DELETE' });
        if (res.ok && hasil.success) {
            tampilNotif('Data mahasiswa berhasil dihapus.');   // Tugas mandiri 1
            muatDataMahasiswa();
        } else {
            tampilNotif(pesanGagal(hasil), 'galat');
        }
    } catch (error) {
        if (error.message !== 'Tidak terautentikasi') {
            tampilNotif('Tidak dapat terhubung ke server.', 'galat');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('#info-user').textContent = localStorage.getItem('username') || '-';
    document.querySelector('#info-role').textContent = ambilRole() || '-';
    document.querySelector('#btn-logout').addEventListener('click', logout);

    // Tombol Tambah dan kolom Aksi disembunyikan untuk role user (proteksi utama tetap di backend)
    if (!tampilkanAksi) {
        document.querySelector('#btn-tambah').hidden = true;
        document.querySelector('.kolom-aksi').hidden = true;
    }

    tbody.addEventListener('click', (e) => {
        const tombol = e.target.closest('button[data-aksi="hapus"]');
        if (tombol) { hapusData(tombol.dataset.id, tombol.dataset.nama); }
    });

    tampilNotifTertunda();   // notifikasi dari form_tambah / form_edit
    muatDataMahasiswa();
});
