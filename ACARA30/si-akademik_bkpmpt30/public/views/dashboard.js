// Acara 25: dashboard, Logout, tombol disembunyikan untuk role user
// Acara 26: kolom foto
// Acara 27: Tambah/Ubah membuka form terpisah, Hapus langsung dari tabel, notifikasi visual
// Acara 29: indikator loading, pesan galat + tombol "Coba lagi", escaping output, retry otomatis
// (fungsi bersama ada di api.js)

// Untuk menguji 403 dari tombol (Acara 25): ubah menjadi false sementara agar tombol tetap tampil untuk role user.
const SEMBUNYIKAN_TOMBOL_UNTUK_USER = true;

const isAdmin = ambilRole() === 'admin';
const tampilkanAksi = isAdmin || !SEMBUNYIKAN_TOMBOL_UNTUK_USER;
const tbody = document.querySelector('#tabel-mahasiswa tbody');

let sedangMemuat = false;   // mencegah dua permintaan muat data berjalan bersamaan
let sedangHapus = false;    // mencegah hapus ganda saat permintaan sebelumnya belum selesai

// Satu baris pesan pada tabel. Teks selalu di-escape; spinner/tombol ulang adalah markup tetap (bukan data).
function tampilkanPesan(teks, kelas = '', { spinner = false, tombolUlang = false } = {}) {
    const kolom = tampilkanAksi ? 5 : 4;
    const ikon = spinner ? '<span class="spinner" aria-hidden="true"></span>' : '';
    const ulang = tombolUlang ? ' <button type="button" class="abu kecil" data-aksi="muat-ulang">Coba lagi</button>' : '';
    tbody.innerHTML = `<tr><td colspan="${kolom}" class="pesan ${kelas}" role="status">${ikon}${escapeHTML(teks)}${ulang}</td></tr>`;
}

// Acara 29 - Langkah 2: seluruh data dinamis dari API melewati escapeHTML() sebelum masuk innerHTML
function renderTabel(daftar) {
    tbody.innerHTML = daftar.map((m) => {
        const foto = m.foto
            ? `<img class="foto" src="${escapeHTML(BASE_URL + '/uploads/' + encodeURIComponent(m.foto))}" alt="Foto ${escapeHTML(m.nama)}">`
            : '-';
        // Ubah membuka form_edit.html?id=..., Hapus memanggil hapusData(id)
        const aksi = tampilkanAksi
            ? `<td class="aksi"><a class="tombol kecil" href="form_edit.html?id=${encodeURIComponent(m.id)}">Ubah</a>` +
              `<button type="button" class="merah" data-aksi="hapus" data-id="${escapeHTML(m.id)}" data-nama="${escapeHTML(m.nama)}">Hapus</button></td>`
            : '';
        return `<tr><td>${foto}</td><td>${escapeHTML(m.nim)}</td><td>${escapeHTML(m.nama)}</td><td>${escapeHTML(m.prodi)}</td>${aksi}</tr>`;
    }).join('');
}

// Acara 29 - Langkah 1: loading state + pesan galat yang jelas (tidak gagal senyap)
async function muatDataMahasiswa() {
    if (sedangMemuat) { return; }
    sedangMemuat = true;
    tampilkanPesan('Memuat data...', '', { spinner: true });
    try {
        // Tugas mandiri 2: koneksi putus sementara diulang otomatis satu kali
        const res = await fetchDenganRetry(`${BASE_URL}/mahasiswa`, {}, {
            onRetry: () => tampilkanPesan('Koneksi bermasalah, mencoba ulang...', '', { spinner: true }),
        });
        const hasil = await bacaJson(res);
        if (!res.ok || !hasil || !hasil.success) {
            const rinci = hasil && hasil.message ? `${hasil.message} (HTTP ${res.status})` : `Server merespons dengan status ${res.status}`;
            throw new Error(rinci);
        }

        const daftar = Array.isArray(hasil.data) ? hasil.data : [];
        document.querySelector('#total-mhs').textContent = daftar.length;

        if (daftar.length === 0) {
            tampilkanPesan('Belum ada data');
            return;
        }
        renderTabel(daftar);
    } catch (error) {
        console.error('Gagal memuat data:', error);
        document.querySelector('#total-mhs').textContent = '-';
        const alasan = (error && error.message) || 'Terjadi kesalahan';
        tampilkanPesan(`Gagal memuat data: ${alasan}`, 'error', { tombolUlang: true });
    } finally {
        sedangMemuat = false;
    }
}

// Acara 29 - Langkah 3: pola try-catch-finally; tombol Hapus dinonaktifkan selama permintaan berlangsung
async function hapusData(id, nama, tombol) {
    if (sedangHapus) { return; }
    if (!confirm(`Hapus data ${nama || 'ini'}?`)) { return; }

    sedangHapus = true;
    let berhasil = false;
    setLoadingTombol(tombol, true, 'Menghapus...');
    try {
        const { res, hasil } = await panggilApi(`/mahasiswa?id=${encodeURIComponent(id)}`, { method: 'DELETE' }, { onRetry: infoRetry });
        if (res.ok && hasil.success) {
            berhasil = true;
            tampilNotif('Data mahasiswa berhasil dihapus.');
        } else {
            tampilNotif(pesanGagal(hasil, res), 'galat');
        }
    } catch (error) {
        tampilGalat(error);
    } finally {
        setLoadingTombol(tombol, false);
        sedangHapus = false;
    }
    if (berhasil) { await muatDataMahasiswa(); }   // tabel dimuat ulang tanpa reload halaman penuh
}

document.addEventListener('DOMContentLoaded', () => {
    // textContent otomatis aman dari XSS (tidak menafsirkan HTML)
    document.querySelector('#info-user').textContent = localStorage.getItem('username') || '-';
    document.querySelector('#info-role').textContent = ambilRole() || '-';
    document.querySelector('#btn-logout').addEventListener('click', logout);

    // Tombol Tambah dan kolom Aksi disembunyikan untuk role user (proteksi utama tetap di backend)
    if (!tampilkanAksi) {
        document.querySelector('#btn-tambah').hidden = true;
        document.querySelector('.kolom-aksi').hidden = true;
    }

    tbody.addEventListener('click', (e) => {
        const hapus = e.target.closest('button[data-aksi="hapus"]');
        if (hapus) { hapusData(hapus.dataset.id, hapus.dataset.nama, hapus); return; }
        if (e.target.closest('button[data-aksi="muat-ulang"]')) { muatDataMahasiswa(); }
    });

    tampilNotifTertunda();   // notifikasi dari form_tambah / form_edit
    muatDataMahasiswa();
});
