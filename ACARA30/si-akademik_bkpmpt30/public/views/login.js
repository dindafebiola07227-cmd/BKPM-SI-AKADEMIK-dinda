// Acara 25 - Langkah 3: login, simpan token dan role di localStorage, lalu ke dashboard
// Acara 29: loading state pada tombol, pesan galat tanpa alert(), retry otomatis (AuthController)
const username = document.querySelector('#username');
const password = document.querySelector('#password');
const btnMasuk = document.querySelector('#btn-masuk');
let sedangKirim = false;

// Sudah punya token? langsung ke dashboard
if (localStorage.getItem('token')) {
    window.location.href = 'dashboard.html';
}

document.querySelector('#form-login').addEventListener('submit', async (e) => {
    e.preventDefault();
    if (sedangKirim) { return; }

    sedangKirim = true;
    let berhasil = false;
    setLoadingTombol(btnMasuk, true, 'Memproses...');
    try {
        // auth: false -> tanpa header Authorization, dan 401 berarti "username/password salah" (bukan sesi berakhir)
        const { res, hasil } = await panggilApi('/login', {
            method: 'POST',
            body: JSON.stringify({ username: username.value, password: password.value }),
        }, { auth: false, onRetry: infoRetry });

        if (res.ok && hasil.success) {
            berhasil = true;
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            localStorage.setItem('username', username.value);
            window.location.href = 'dashboard.html';
            return;
        }
        tampilNotif(pesanGagal(hasil, res), 'galat');
    } catch (error) {
        tampilGalat(error);
    } finally {
        if (!berhasil) {
            setLoadingTombol(btnMasuk, false);
            sedangKirim = false;
        }
    }
});
