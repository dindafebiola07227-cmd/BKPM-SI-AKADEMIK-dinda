<?php require __DIR__ . '/../partials/header.php'; ?>

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1 class="fw-bold text-primary mb-0">
                    🎓 Data Program Studi
                </h1>

                <a
                    href="/si-akademik_bkpmpt9/public/prodi/create"
                    class="btn btn-primary"
                >
                    + Tambah Prodi
                </a>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Program Studi</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (!empty($prodi)): ?>

                            <?php foreach ($prodi as $index => $item): ?>

                                <tr>

                                    <td>
                                        <?= $index + 1 ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item['kode']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item['nama']) ?>
                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            <a
                                                href="/si-akademik_bkpmpt9/public/prodi/<?= $item['id'] ?>"
                                                class="btn btn-info btn-sm"
                                            >
                                                Detail
                                            </a>

                                            <a
                                                href="/si-akademik_bkpmpt9/public/prodi/<?= $item['id'] ?>/edit"
                                                class="btn btn-warning btn-sm"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="/si-akademik_bkpmpt9/public/prodi/<?= $item['id'] ?>/delete"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus prodi ini?');"
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
                                    colspan="4"
                                    class="text-center text-muted py-4"
                                >
                                    Belum ada data program studi.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>