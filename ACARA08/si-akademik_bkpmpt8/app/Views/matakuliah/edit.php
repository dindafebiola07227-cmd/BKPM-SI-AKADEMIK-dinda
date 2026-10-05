<?php

ob_start();

?>

<div class="container">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-warning">

            <h2 class="mb-0">
                ✏️ Edit Mata Kuliah
            </h2>

        </div>

        <div class="card-body">

            <form
                action="/si-akademik_bkpmpt8/public/matakuliah/<?= (int) $matakuliah['id'] ?>/update"
                method="POST"
            >

                <!-- Kode -->
                <div class="mb-3">

                    <label
                        for="kode"
                        class="form-label fw-bold"
                    >
                        Kode Mata Kuliah
                    </label>

                    <input
                        type="text"
                        name="kode"
                        id="kode"
                        class="form-control"
                        value="<?= htmlspecialchars($matakuliah['kode']) ?>"
                        required
                    >

                </div>


                <!-- Nama -->
                <div class="mb-3">

                    <label
                        for="nama"
                        class="form-label fw-bold"
                    >
                        Nama Mata Kuliah
                    </label>

                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($matakuliah['nama']) ?>"
                        required
                    >

                </div>


                <!-- SKS -->
                <div class="mb-3">

                    <label
                        for="sks"
                        class="form-label fw-bold"
                    >
                        SKS
                    </label>

                    <input
                        type="number"
                        name="sks"
                        id="sks"
                        class="form-control"
                        min="1"
                        value="<?= (int) $matakuliah['sks'] ?>"
                        required
                    >

                </div>


                <!-- Program Studi -->
                <div class="mb-3">

                    <label
                        for="prodi_id"
                        class="form-label fw-bold"
                    >
                        Program Studi
                    </label>

                    <select
                        name="prodi_id"
                        id="prodi_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Pilih Program Studi --
                        </option>

                        <?php foreach ($prodi as $p): ?>

                            <option
                                value="<?= (int) $p['id'] ?>"
                                <?= ((int) $p['id'] === (int) $matakuliah['prodi_id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($p['nama']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- Tombol -->
                <div class="mt-4">

                    <a
                        href="/si-akademik_bkpmpt8/public/matakuliah"
                        class="btn btn-secondary"
                    >
                        ← Kembali
                    </a>

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        💾 Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

require __DIR__ . '/../layouts/main.php';

?>