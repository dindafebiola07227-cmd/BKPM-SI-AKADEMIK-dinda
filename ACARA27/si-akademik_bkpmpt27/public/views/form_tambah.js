// Acara 27 - Langkah 1: form tambah data (FormData + header Authorization)
wajibLogin();

const form = document.querySelector('#form-mhs');
const btnSimpan = document.querySelector('#btn-simpan');

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Tugas mandiri 2: validasi sisi View sebelum dikirim ke Controller
    if (!validasiFormMahasiswa(form)) {
        tampilNotif('Periksa kembali isian yang ditandai.', 'galat');
        return;
    }

    btnSimpan.disabled = true;
    try {
        const { res, hasil } = await panggilApi('/mahasiswa', { method: 'POST', body: susunFormData(form) });
        if (res.ok && hasil.success) {
            // Tugas mandiri 1: notifikasi berhasil ditampilkan di dashboard setelah kembali
            notifBerikutnya('Data mahasiswa berhasil ditambahkan.');
            window.location.href = 'dashboard.html';
            return;
        }
        tampilNotif(pesanGagal(hasil), 'galat');
    } catch (error) {
        if (error.message !== 'Tidak terautentikasi') {
            tampilNotif('Tidak dapat terhubung ke server.', 'galat');
        }
    }
    btnSimpan.disabled = false;
});
