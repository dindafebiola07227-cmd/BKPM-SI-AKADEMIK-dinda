// Acara 24 - Langkah 3: menampilkan data mahasiswa dari API memakai Fetch API (async/await).
// Dibuka lewat http://localhost/... : alamat dasar diturunkan otomatis dari lokasi halaman ini
// (sama dengan BASE_URL pada modul, mis. http://localhost/BKPM/ACARA24/si-akademik_bkpmpt24/public).
// Dibuka lewat file:// : dipakai alamat tetap di bawah. Ubah bila nama folder proyekmu berbeda.
const BASE_URL = location.protocol === 'file:'
    ? 'http://localhost/BKPM/ACARA24/si-akademik_bkpmpt24/public'
    : location.origin + location.pathname.split('/views/')[0];

// Mencegah data yang mengandung karakter HTML (mis. <script>) dieksekusi saat ditampilkan.
function escapeHtml(teks) {
    return String(teks ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

// Menampilkan satu baris pesan pada tabel (Memuat data..., Belum ada data, atau pesan gagal).
function tampilkanPesan(tbody, teks) {
    tbody.innerHTML = `<tr><td colspan="3" class="pesan">${escapeHtml(teks)}</td></tr>`;
}

async function muatDataMahasiswa() {
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    // Tugas Mandiri 1: indikator "Memuat data..." tampil sebelum data berhasil diambil
    tampilkanPesan(tbody, 'Memuat data...');

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();

        if (!response.ok || !hasil.success) {
            throw new Error(hasil.message || `HTTP ${response.status}`);
        }

        const data = Array.isArray(hasil.data) ? hasil.data : [];

        // Tugas Mandiri 2: kondisi data kosong
        if (data.length === 0) {
            tampilkanPesan(tbody, 'Belum ada data');
            return;
        }

        tbody.innerHTML = '';
        data.forEach((mhs) => {
            tbody.innerHTML +=
                `<tr><td>${escapeHtml(mhs.nim)}</td><td>${escapeHtml(mhs.nama)}</td><td>${escapeHtml(mhs.prodi)}</td></tr>`;
        });
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan(tbody, 'Gagal memuat data. Periksa server dan Console browser.');
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
