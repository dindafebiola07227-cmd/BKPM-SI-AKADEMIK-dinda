<?php

ob_start();

?>

<div class="container py-4">

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1 class="fw-bold text-primary mb-0">
                    📚 Daftar Mahasiswa
                </h1>

                <a
                    href="<?= BASE_URL ?>/mahasiswa/create"
                    class="btn btn-primary"
                >
                    + Tambah Mahasiswa
                </a>

            </div>


            <form
                method="GET"
                action="<?= BASE_URL ?>/mahasiswa"
                class="mb-3"
            >

                <div class="input-group">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari NIM atau nama mahasiswa..."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    >

                    <button
                        type="submit"
                        class="btn btn-outline-primary"
                    >
                        Cari
                    </button>

                </div>

            </form>


            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($mahasiswa)): ?>

                            <?php foreach ($mahasiswa as $index => $mhs): ?>

                                <tr>

                                    <td>
                                        <?= $index + 1 ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['nim']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['nama']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['email']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['nama_prodi'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['angkatan']) ?>
                                    </td>

                                    <td>

                                        <?php if ($mhs['status'] === 'aktif'): ?>

                                            <span class="badge bg-success">
                                                Aktif
                                            </span>

                                        <?php elseif ($mhs['status'] === 'cuti'): ?>

                                            <span class="badge bg-warning text-dark">
                                                Cuti
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                Lulus
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <div class="d-flex gap-1">

                                            <a
                                                href="<?= BASE_URL ?>/mahasiswa/<?= $mhs['id'] ?>"
                                                class="btn btn-sm btn-info text-white"
                                            >
                                                Detail
                                            </a>

                                            <a
                                                href="<?= BASE_URL ?>/mahasiswa/<?= $mhs['id'] ?>/edit"
                                                class="btn btn-sm btn-warning"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="<?= BASE_URL ?>/mahasiswa/<?= $mhs['id'] ?>/delete"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?');"
                                            >

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-danger"
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
                                    colspan="8"
                                    class="text-center text-muted py-4"
                                >
                                    Belum ada data mahasiswa.
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

