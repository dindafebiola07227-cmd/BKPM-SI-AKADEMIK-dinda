// Acara 27 - Langkah 1: form tambah data (FormData + header Authorization)
// Acara 29 - Langkah 3: try-catch-finally, tombol submit dinonaktifkan selama pengiriman, umpan balik sukses/gagal
wajibLogin();

const form = document.querySelector('#form-mhs');
const btnSimpan = document.querySelector('#btn-simpan');
let sedangKirim = false;   // penjaga tambahan terhadap submit ganda (mis. Enter ditekan dua kali)

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (sedangKirim) { return; }

    // Validasi sisi View sebelum dikirim ke Controller
    if (!validasiFormMahasiswa(form)) {
        tampilNotif('Periksa kembali isian yang ditandai.', 'galat');
        return;
    }

    sedangKirim = true;
    let berhasil = false;
    setLoadingTombol(btnSimpan, true, 'Menyimpan...');
    try {
        const { res, hasil } = await panggilApi('/mahasiswa', { method: 'POST', body: susunFormData(form) }, { onRetry: infoRetry });
        if (res.ok && hasil.success) {
            berhasil = true;
            // Notifikasi berhasil ditampilkan di dashboard setelah kembali
            notifBerikutnya('Data mahasiswa berhasil ditambahkan.');
            window.location.href = 'dashboard.html';
            return;
        }
        tampilNotif(pesanGagal(hasil, res), 'galat');
        tampilkanGalatServer(form, hasil);   // galat per field dari Controller (409 NIM kembar, 422 validasi)
    } catch (error) {
        tampilGalat(error);
    } finally {
        // Bila berhasil, tombol tetap nonaktif sampai halaman berpindah (mencegah klik ganda)
        if (!berhasil) {
            setLoadingTombol(btnSimpan, false);
            sedangKirim = false;
        }
    }
});
