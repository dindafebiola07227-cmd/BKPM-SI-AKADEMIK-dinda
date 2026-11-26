// Acara 27 - Langkah 2: form edit data
// Acara 29: loading state saat memuat data, try-catch-finally saat menyimpan, retry otomatis
wajibLogin();

const form = document.querySelector('#form-mhs');
const btnSimpan = document.querySelector('#btn-simpan');
const areaStatus = document.querySelector('#status-muat');
const id = new URLSearchParams(window.location.search).get('id');
let sedangKirim = false;

function aturFormAktif(aktif) {
    form.querySelectorAll('input').forEach((el) => { el.disabled = !aktif; });
    btnSimpan.disabled = !aktif;
}

// Area status di atas form: pesan memuat / gagal memuat (dengan tombol coba lagi)
function tampilStatus(teks, { spinner = false, galat = false, tombolUlang = false } = {}) {
    if (!teks) { areaStatus.innerHTML = ''; areaStatus.className = 'status-muat'; return; }
    const ikon = spinner ? '<span class="spinner" aria-hidden="true"></span>' : '';
    const ulang = tombolUlang ? ' <button type="button" class="abu kecil" id="btn-muat-ulang">Coba lagi</button>' : '';
    areaStatus.className = `status-muat${galat ? ' error' : ''}`;
    areaStatus.innerHTML = `${ikon}${escapeHTML(teks)}${ulang}`;
}

// Mengisi form dengan data yang akan diubah (ditangani MahasiswaController::index())
async function muatDataUntukEdit(id) {
    aturFormAktif(false);
    tampilStatus('Memuat data mahasiswa...', { spinner: true });
    try {
        const res = await fetchDenganRetry(`${BASE_URL}/mahasiswa?id=${encodeURIComponent(id)}`, {}, {
            onRetry: () => tampilStatus('Koneksi bermasalah, mencoba ulang...', { spinner: true }),
        });
        const hasil = await bacaJson(res);

        if (res.ok && hasil && hasil.success) {
            form.querySelector('[name=nim]').value = hasil.data.nim;
            form.querySelector('[name=nama]').value = hasil.data.nama;
            form.querySelector('[name=prodi]').value = hasil.data.prodi;
            if (hasil.data.foto) {
                const url = `${BASE_URL}/uploads/${encodeURIComponent(hasil.data.foto)}`;
                document.querySelector('#foto-lama').innerHTML =
                    `<img class="foto" src="${escapeHTML(url)}" alt="Foto saat ini">`;
            }
            tampilStatus('');
            aturFormAktif(true);
            return;
        }
        if (res.status >= 400 && res.status < 500 && hasil) {   // ID tidak ditemukan / tidak valid: kembali ke dashboard
            notifBerikutnya(pesanGagal(hasil, res), 'galat');
            window.location.href = 'dashboard.html';
            return;
        }
        throw new Error(hasil && hasil.message ? `${hasil.message} (HTTP ${res.status})` : `Server merespons dengan status ${res.status}`);
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilStatus(`Gagal memuat data: ${(error && error.message) || 'Terjadi kesalahan'}`, { galat: true, tombolUlang: true });
    }
}

// Acara 29 - Langkah 3: try-catch-finally
async function simpanPerubahan(id) {
    if (sedangKirim) { return; }
    if (!validasiFormMahasiswa(form)) {
        tampilNotif('Periksa kembali isian yang ditandai.', 'galat');
        return;
    }

    const formData = susunFormData(form);
    // Berbeda dengan contoh modul (method: 'PUT'): PHP tidak mengisi $_POST/$_FILES pada request PUT
    // bermultipart, sehingga dikirim sebagai POST + _method=PUT dan diteruskan Router ke update($id).
    formData.append('_method', 'PUT');

    sedangKirim = true;
    let berhasil = false;
    setLoadingTombol(btnSimpan, true, 'Menyimpan...');
    try {
        const { res, hasil } = await panggilApi(`/mahasiswa?id=${encodeURIComponent(id)}`, { method: 'POST', body: formData }, { onRetry: infoRetry });
        if (res.ok && hasil.success) {
            berhasil = true;
            notifBerikutnya('Data mahasiswa berhasil diperbarui.');
            window.location.href = 'dashboard.html';
            return;
        }
        tampilNotif(pesanGagal(hasil, res), 'galat');
        tampilkanGalatServer(form, hasil);
    } catch (error) {
        tampilGalat(error);
    } finally {
        if (!berhasil) {
            setLoadingTombol(btnSimpan, false);
            sedangKirim = false;
        }
    }
}

if (!/^[1-9][0-9]*$/.test(id || '')) {
    notifBerikutnya('ID mahasiswa tidak valid.', 'galat');
    window.location.href = 'dashboard.html';
} else {
    muatDataUntukEdit(id);
    areaStatus.addEventListener('click', (e) => { if (e.target.id === 'btn-muat-ulang') { muatDataUntukEdit(id); } });
    form.addEventListener('submit', (e) => { e.preventDefault(); simpanPerubahan(id); });
}
