<?php

use App\Models\Mahasiswa;

ob_start();

/*
|--------------------------------------------------------------------------
| Object Mahasiswa
|--------------------------------------------------------------------------
*/

$mahasiswa = [
    new Mahasiswa('2401001', 'Budi Santoso', 'Teknik Informatika'),
    new Mahasiswa('2401002', 'Siti Aminah', 'Sistem Informasi'),
    new Mahasiswa('2301003', 'Andi Pratama', 'Teknik Komputer'),
];

?>

<div class="container py-4">

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h1 class="fw-bold text-primary mb-4">
                📚 Daftar Mahasiswa
            </h1>

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($mahasiswa as $index => $mhs): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mhs->getNim()) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mhs->getNama()) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mhs->getProdi()) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mhs->getAngkatan()) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layouts/main.php';

?>