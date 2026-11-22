// Alamat dasar API (sesuaikan dengan lokasi folder proyek pada XAMPP)
const BASE_URL = 'http://localhost/BKPM/ACARA25/si-akademik_bkpmpt25/public';

// Mencegah karakter HTML pada data masuk sebagai markup (XSS)
function escapeHtml(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

async function muatDataMahasiswa() {
    const status = document.querySelector('#status');
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    // Tugas mandiri 1: indikator tampil sebelum data berhasil diambil
    status.className = '';
    status.textContent = 'Memuat data...';
    tbody.innerHTML = '';

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();

        if (!response.ok || !hasil.success) {
            throw new Error(hasil.message || `HTTP ${response.status}`);
        }

        // Tugas mandiri 2: tangani kondisi data kosong
        if (!Array.isArray(hasil.data) || hasil.data.length === 0) {
            status.textContent = 'Belum ada data';
            return;
        }

        hasil.data.forEach((mhs) => {
            tbody.innerHTML +=
                `<tr><td>${escapeHtml(mhs.nim)}</td><td>${escapeHtml(mhs.nama)}</td><td>${escapeHtml(mhs.prodi)}</td></tr>`;
        });
        status.textContent = '';
    } catch (error) {
        console.error('Gagal memuat data:', error);
        status.className = 'error';
        status.textContent = 'Gagal memuat data. Periksa server API dan konsol browser.';
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
