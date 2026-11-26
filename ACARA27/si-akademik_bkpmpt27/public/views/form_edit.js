// Acara 27 - Langkah 2: form edit data
wajibLogin();

const form = document.querySelector('#form-mhs');
const btnSimpan = document.querySelector('#btn-simpan');
const id = new URLSearchParams(window.location.search).get('id');

// Mengisi form dengan data yang akan diubah (ditangani MahasiswaController::index())
async function muatDataUntukEdit(id) {
    try {
        const res = await fetch(`${BASE_URL}/mahasiswa?id=${encodeURIComponent(id)}`);
        const hasil = await res.json();
        if (!res.ok || !hasil.success) {
            notifBerikutnya(pesanGagal(hasil), 'galat');
            window.location.href = 'dashboard.html';
            return;
        }
        form.querySelector('[name=nim]').value = hasil.data.nim;
        form.querySelector('[name=nama]').value = hasil.data.nama;
        form.querySelector('[name=prodi]').value = hasil.data.prodi;
        if (hasil.data.foto) {
            document.querySelector('#foto-lama').innerHTML =
                `<img class="foto" src="${BASE_URL}/uploads/${encodeURIComponent(hasil.data.foto)}" alt="Foto saat ini">`;
        }
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilNotif('Gagal memuat data mahasiswa.', 'galat');
    }
}

async function simpanPerubahan(id) {
    if (!validasiFormMahasiswa(form)) {
        tampilNotif('Periksa kembali isian yang ditandai.', 'galat');
        return;
    }

    const formData = susunFormData(form);
    // Berbeda dengan contoh modul (method: 'PUT'): PHP tidak mengisi $_POST/$_FILES pada request PUT
    // bermultipart, sehingga dikirim sebagai POST + _method=PUT dan diteruskan Router ke update($id).
    formData.append('_method', 'PUT');

    btnSimpan.disabled = true;
    try {
        const { res, hasil } = await panggilApi(`/mahasiswa?id=${encodeURIComponent(id)}`, { method: 'POST', body: formData });
        if (res.ok && hasil.success) {
            notifBerikutnya('Data mahasiswa berhasil diperbarui.');
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
}

if (!/^[1-9][0-9]*$/.test(id || '')) {
    notifBerikutnya('ID mahasiswa tidak valid.', 'galat');
    window.location.href = 'dashboard.html';
} else {
    muatDataUntukEdit(id);
    form.addEventListener('submit', (e) => { e.preventDefault(); simpanPerubahan(id); });
}
