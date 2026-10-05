<?php

ob_start();

?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-primary shadow-sm mb-4">

    <div class="container">

        <a
            class="navbar-brand text-white fw-bold"
            href="/si-akademik_bkpmpt8/public/dashboard"
        >
            🎓 SI Akademik
        </a>

        <div class="d-flex align-items-center gap-2">

            <a
                href="/si-akademik_bkpmpt8/public/dashboard"
                class="btn btn-light btn-sm"
            >
                🏠 Beranda
            </a>

            <a
                href="/si-akademik_bkpmpt8/public/mahasiswa"
                class="btn btn-light btn-sm"
            >
                📚 Mahasiswa
            </a>

            <a
                href="/si-akademik_bkpmpt8/public/prodi"
                class="btn btn-light btn-sm"
            >
                🎓 Prodi
            </a>

            <a
                href="/si-akademik_bkpmpt8/public/matakuliah"
                class="btn btn-light btn-sm"
            >
                📖 Mata Kuliah
            </a>

            <a
                href="/si-akademik_bkpmpt8/public/logout"
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

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1 class="fw-bold text-primary mb-0">
                    📖 Daftar Mata Kuliah
                </h1>

                <a
                    href="/si-akademik_bkpmpt8/public/matakuliah/create"
                    class="btn btn-primary"
                >
                    + Tambah Mata Kuliah
                </a>

            </div>


            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Program Studi</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($matakuliah)): ?>

                            <?php foreach ($matakuliah as $index => $mk): ?>

                                <tr>

                                    <td>
                                        <?= $index + 1 ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mk['kode'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mk['nama'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mk['sks'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mk['nama_prodi'] ?? '-') ?>
                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            <!-- Detail -->
                                            <a
                                                href="/si-akademik_bkpmpt8/public/matakuliah/<?= (int) $mk['id'] ?>"
                                                class="btn btn-info btn-sm"
                                            >
                                                Detail
                                            </a>


                                            <!-- Edit -->
                                            <a
                                                href="/si-akademik_bkpmpt8/public/matakuliah/<?= (int) $mk['id'] ?>/edit"
                                                class="btn btn-warning btn-sm"
                                            >
                                                Edit
                                            </a>


                                            <!-- Hapus -->
                                            <form
                                                action="/si-akademik_bkpmpt8/public/matakuliah/<?= (int) $mk['id'] ?>/delete"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');"
                                                class="d-inline"
                                            >

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                >
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4"
                                >
                                    Belum ada data mata kuliah.
                                </td>

                            </tr>

                        <?php endif; ?>

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