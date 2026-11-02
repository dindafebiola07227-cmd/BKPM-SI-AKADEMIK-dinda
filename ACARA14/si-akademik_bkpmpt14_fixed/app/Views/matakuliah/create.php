<?php

ob_start();

?>

<!-- Isi halaman -->
<div class="container">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-primary text-white">

            <h2 class="mb-0">
                📖 Tambah Mata Kuliah
            </h2>

        </div>


        <div class="card-body">

            <form
                action="<?= BASE_URL ?>/matakuliah"
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
                        placeholder="Contoh: TI103"
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
                        placeholder="Contoh: Pemrograman Web"
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
                            <option value="<?= (int) $p['id'] ?>">
                                <?= htmlspecialchars($p['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>


                <!-- Tombol -->
                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        💾 Simpan
                    </button>

                    <a
                        href="<?= BASE_URL ?>/matakuliah"
                        class="btn btn-secondary"
                    >
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


<?php

$content = ob_get_clean();

require __DIR__ . '/../layouts/main.php';

?>

