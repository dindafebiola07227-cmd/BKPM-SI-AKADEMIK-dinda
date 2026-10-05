<?php

require_once __DIR__ . '/../../Models/Mahasiswa.php';

use App\Models\Mahasiswa;

$mahasiswa = [
    new Mahasiswa("2401001", "Budi Santoso", "Teknik Informatika"),
    new Mahasiswa("2401002", "Siti Aminah", "Sistem Informasi"),
    new Mahasiswa("2401003", "Andi Pratama", "Teknik Komputer")
];

ob_start();
?>

<h1 class="mb-4">Daftar Mahasiswa</h1>

<table class="table table-bordered table-striped">

    <thead class="table-dark">
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Angkatan</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($mahasiswa as $mhs): ?>

            <tr>
                <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                <td><?= htmlspecialchars($mhs->getNama()) ?></td>
                <td><?= htmlspecialchars($mhs->getProdi()) ?></td>
                <td><?= htmlspecialchars($mhs->getAngkatan()) ?></td>
            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layouts/main.php';
?>