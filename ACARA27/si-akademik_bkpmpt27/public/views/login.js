// Acara 25 - Langkah 3: login, simpan token dan role di localStorage, lalu ke dashboard
const username = document.querySelector('#username');
const password = document.querySelector('#password');

// Sudah punya token? langsung ke dashboard
if (localStorage.getItem('token')) {
    window.location.href = 'dashboard.html';
}

document.querySelector('#form-login').addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const res = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: username.value, password: password.value }),
        });
        const hasil = await res.json();

        if (hasil.success) {
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            localStorage.setItem('username', username.value);
            window.location.href = 'dashboard.html';
        } else {
            alert(hasil.message);
        }
    } catch (error) {
        console.error('Gagal login:', error);
        alert('Tidak dapat terhubung ke server. Periksa BASE_URL dan Apache.');
    }
});
