<?php

ob_start();

?>

<!-- Isi halaman -->
<div class="container">

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1 class="fw-bold text-primary mb-0">
                    📖 Daftar Mata Kuliah
                </h1>

                <a
                    href="<?= BASE_URL ?>/matakuliah/create"
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
                                                href="<?= BASE_URL ?>/matakuliah/<?= (int) $mk['id'] ?>"
                                                class="btn btn-info btn-sm"
                                            >
                                                Detail
                                            </a>


                                            <!-- Edit -->
                                            <a
                                                href="<?= BASE_URL ?>/matakuliah/<?= (int) $mk['id'] ?>/edit"
                                                class="btn btn-warning btn-sm"
                                            >
                                                Edit
                                            </a>


                                            <!-- Hapus -->
                                            <form
                                                action="<?= BASE_URL ?>/matakuliah/<?= (int) $mk['id'] ?>/delete"
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

