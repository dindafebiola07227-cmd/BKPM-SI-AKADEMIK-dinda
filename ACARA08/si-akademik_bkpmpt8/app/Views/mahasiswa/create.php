<?php require __DIR__ . '/../partials/header.php'; ?>

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Mahasiswa</h4>
        </div>

        <div class="card-body">

            <form
                action="/si-akademik_bkpmpt8/public/mahasiswa"
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
                        <option value="aktif">Aktif</option>
                        <option value="cuti">Cuti</option>
                        <option value="lulus">Lulus</option>
                    </select>
                </div>


                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Simpan
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