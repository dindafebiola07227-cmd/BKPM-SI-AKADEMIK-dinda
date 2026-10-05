<?php

ob_start();

?>

<div class="container">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-primary text-white">

            <h2 class="mb-0">
                📖 Detail Mata Kuliah
            </h2>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Kode Mata Kuliah
                </div>

                <div class="col-md-8">
                    <?= htmlspecialchars($matakuliah['kode']) ?>
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Nama Mata Kuliah
                </div>

                <div class="col-md-8">
                    <?= htmlspecialchars($matakuliah['nama']) ?>
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    SKS
                </div>

                <div class="col-md-8">
                    <?= htmlspecialchars($matakuliah['sks']) ?>
                </div>

            </div>


            <div class="row mb-3">

                <div class="col-md-4 fw-bold">
                    Program Studi
                </div>

                <div class="col-md-8">
                    <?= htmlspecialchars($matakuliah['nama_prodi'] ?? '-') ?>
                </div>

            </div>


            <div class="mt-4">

                <a
                    href="/si-akademik_bkpmpt8/public/matakuliah"
                    class="btn btn-secondary"
                >
                    ← Kembali
                </a>

                <a
                    href="/si-akademik_bkpmpt8/public/matakuliah/<?= $matakuliah['id'] ?>/edit"
                    class="btn btn-warning"
                >
                    ✏️ Edit
                </a>

            </div>

        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

require __DIR__ . '/../layouts/main.php';

?>