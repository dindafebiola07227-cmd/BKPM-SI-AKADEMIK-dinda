<?php

$nama = $_GET['nama'] ?? '';

$username = '';
$hasilLogin = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'admin123') {
        $hasilLogin = 'Login berhasil.';
    } else {
        $hasilLogin = 'Username atau password salah.';
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>SI Akademik</title>

</head>

<body>

    <h1>Selamat Datang di SI Akademik</h1>

    <hr>

    <!-- GET -->

    <h2>Pencarian Mahasiswa</h2>

    <form method="GET">

        <label>
            Nama Mahasiswa:
        </label>

        <input
            type="text"
            name="nama"
            placeholder="Masukkan nama"
        >

        <button type="submit">
            Cari
        </button>

    </form>

    <?php if ($nama !== ''): ?>

        <p>
            Hasil pencarian:
            <strong>
                <?= htmlspecialchars($nama) ?>
            </strong>
        </p>

    <?php endif; ?>


    <hr>

    <!-- POST -->

    <h2>Login</h2>

    <form method="POST">

        <label>
            Username:
        </label>

        <input
            type="text"
            name="username"
            required
        >

        <br><br>

        <label>
            Password:
        </label>

        <input
            type="password"
            name="password"
            required
        >

        <br><br>

        <button type="submit">
            Login
        </button>

    </form>

    <?php if ($hasilLogin !== ''): ?>

        <p>
            <strong>
                <?= htmlspecialchars($hasilLogin) ?>
            </strong>
        </p>

    <?php endif; ?>

</body>

</html>