<?php

require_once __DIR__ . '/../../Models/Mahasiswa.php';

use App\Models\Mahasiswa;

$mahasiswa = [
    new Mahasiswa("2401001", "Dinda Febiola R", "Teknik Informatika"),
    new Mahasiswa("2401002", "Linda Ratnadela A", "Sistem Informasi"),
    new Mahasiswa("2401003", "Sheryn Febrylia H", "Teknik Komputer")
];

ob_start();
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-primary shadow-sm mb-4">
    <div class="container">

        <a
            class="navbar-brand text-white fw-bold"
            href="/SI-AKADEMIK_BKPMPT6/public/dashboard"
        >
            🎓 SI Akademik
        </a>

        <div class="d-flex align-items-center gap-2">

            <a
                href="http://localhost//SI-AKADEMIK_BKPMPT6/public/dashboard"
                class="btn btn-light btn-sm"
            >
                🏠 Beranda
            </a>

            <a
                href="/SI-AKADEMIK_BKPMPT6/public/mahasiswa"
                class="btn btn-light btn-sm"
            >
                📚 Mahasiswa
            </a>

            <a
                href="/SI-AKADEMIK_BKPMPT6/public/mahasiswa/create"
                class="btn btn-light btn-sm"
            >
                🧑🏻‍🎓 Tambah Mahasiswa
            </a>

            <a
                href="/SI-AKADEMIK_BKPMPT6/public/logout"
                class="btn btn-warning btn-sm"
            >
                🚪 Logout
            </a>

        </div>

    </div>
</nav>

<!-- Isi halaman -->
<div class="container">

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h1 class="fw-bold text-primary mb-4">
                📚 Daftar Mahasiswa
            </h1>

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle">

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