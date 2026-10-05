<?php require __DIR__ . '/../partials/header.php'; ?>

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                Detail Mahasiswa
            </h4>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    NIM
                </div>

                <div class="col-md-9">
                    <?= htmlspecialchars($mahasiswa['nim'] ?? '-') ?>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Nama
                </div>

                <div class="col-md-9">
                    <?= htmlspecialchars($mahasiswa['nama'] ?? '-') ?>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Email
                </div>

                <div class="col-md-9">
                    <?= htmlspecialchars($mahasiswa['email'] ?? '-') ?>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Program Studi
                </div>

                <div class="col-md-9">
                    <?= htmlspecialchars($mahasiswa['nama_prodi'] ?? '-') ?>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Angkatan
                </div>

                <div class="col-md-9">
                    <?= htmlspecialchars($mahasiswa['angkatan'] ?? '-') ?>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Status
                </div>

                <div class="col-md-9">

                    <?php if (($mahasiswa['status'] ?? '') === 'aktif'): ?>

                        <span class="badge bg-success">
                            Aktif
                        </span>

                    <?php elseif (($mahasiswa['status'] ?? '') === 'cuti'): ?>

                        <span class="badge bg-warning text-dark">
                            Cuti
                        </span>

                    <?php elseif (($mahasiswa['status'] ?? '') === 'lulus'): ?>

                        <span class="badge bg-primary">
                            Lulus
                        </span>

                    <?php else: ?>

                        <span class="badge bg-secondary">
                            <?= htmlspecialchars($mahasiswa['status'] ?? '-') ?>
                        </span>

                    <?php endif; ?>

                </div>
            </div>


            <hr>


            <div class="d-flex gap-2">

                <a
                    href="/si-akademik_bkpmpt8/public/mahasiswa/<?= $mahasiswa['id'] ?>/edit"
                    class="btn btn-warning"
                >
                    Edit
                </a>

                <a
                    href="/si-akademik_bkpmpt8/public/mahasiswa"
                    class="btn btn-secondary"
                >
                    Kembali
                </a>

            </div>

        </div>

    </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>