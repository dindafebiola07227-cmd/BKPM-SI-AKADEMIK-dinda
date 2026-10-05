<?php

$nama = "Dinda Febiola";
$nim = "E41250218";
$waktuServer = date("Y-m-d H:i:s");
$versiPHP = phpversion();
$sistemOperasi = PHP_OS;

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Informasi Server - SI Akademik</title>

</head>

<body>

    <h1>Informasi Server SI Akademik</h1>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>Informasi</th>
            <th>Hasil</th>
        </tr>

        <tr>
            <td>Nama</td>
            <td><?= htmlspecialchars($nama) ?></td>
        </tr>

        <tr>
            <td>NIM</td>
            <td><?= htmlspecialchars($nim) ?></td>
        </tr>

        <tr>
            <td>Waktu Server</td>
            <td><?= htmlspecialchars($waktuServer) ?></td>
        </tr>

        <tr>
            <td>Versi PHP</td>
            <td><?= htmlspecialchars($versiPHP) ?></td>
        </tr>

        <tr>
            <td>Sistem Operasi Server</td>
            <td><?= htmlspecialchars($sistemOperasi) ?></td>
        </tr>

    </table>

</body>

</html>