<?php require __DIR__ . '/../partials/header.php'; ?>

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-info text-white">
            <h4 class="mb-0">
                📋 Detail Program Studi
            </h4>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <strong>Kode Program Studi</strong>
                <p class="mb-0">
                    <?= htmlspecialchars($prodi['kode']) ?>
                </p>
            </div>

            <div class="mb-3">
                <strong>Nama Program Studi</strong>
                <p class="mb-0">
                    <?= htmlspecialchars($prodi['nama']) ?>
                </p>
            </div>

            <div class="d-flex gap-2">

                <a
                    href="/si-akademik_bkpmpt9/public/prodi/<?= $prodi['id'] ?>/edit"
                    class="btn btn-warning"
                >
                    Edit
                </a>

                <a
                    href="/si-akademik_bkpmpt9/public/prodi"
                    class="btn btn-secondary"
                >
                    Kembali
                </a>

            </div>

        </div>

    </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>