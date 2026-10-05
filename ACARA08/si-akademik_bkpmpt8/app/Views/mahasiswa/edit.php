<?php require __DIR__ . '/../partials/header.php'; ?>

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-warning">
            <h4 class="mb-0">
                Edit Mahasiswa
            </h4>
        </div>

        <div class="card-body">

            <form
                action="/si-akademik_bkpmpt8/public/mahasiswa/<?= $mahasiswa['id'] ?>/update"
                method="POST"
            >

                <div class="mb-3">

                    <label for="nim" class="form-label">
                        NIM
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nim"
                        name="nim"
                        value="<?= htmlspecialchars($mahasiswa['nim'] ?? '') ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label for="nama" class="form-label">
                        Nama
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nama"
                        name="nama"
                        value="<?= htmlspecialchars($mahasiswa['nama'] ?? '') ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label for="email" class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($mahasiswa['email'] ?? '') ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label for="prodi_id" class="form-label">
                        Program Studi
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        id="prodi_id"
                        name="prodi_id"
                        value="<?= htmlspecialchars($mahasiswa['prodi_id'] ?? '') ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label for="angkatan" class="form-label">
                        Angkatan
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        id="angkatan"
                        name="angkatan"
                        value="<?= htmlspecialchars($mahasiswa['angkatan'] ?? '') ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label for="status" class="form-label">
                        Status
                    </label>

                    <select
                        class="form-select"
                        id="status"
                        name="status"
                    >

                        <option
                            value="aktif"
                            <?= ($mahasiswa['status'] ?? '') === 'aktif' ? 'selected' : '' ?>
                        >
                            Aktif
                        </option>

                        <option
                            value="cuti"
                            <?= ($mahasiswa['status'] ?? '') === 'cuti' ? 'selected' : '' ?>
                        >
                            Cuti
                        </option>

                        <option
                            value="lulus"
                            <?= ($mahasiswa['status'] ?? '') === 'lulus' ? 'selected' : '' ?>
                        >
                            Lulus
                        </option>

                    </select>

                </div>


                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Simpan Perubahan
                    </button>

                    <a
                        href="/si-akademik_bkpmpt8/public/mahasiswa"
                        class="btn btn-secondary"
                    >
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>