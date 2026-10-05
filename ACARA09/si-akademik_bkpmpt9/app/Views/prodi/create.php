<?php require __DIR__ . '/../partials/header.php'; ?>

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                ➕ Tambah Program Studi
            </h4>
        </div>

        <div class="card-body">

            <form
                action="/si-akademik_bkpmpt9/public/prodi"
                method="POST"
            >

                <div class="mb-3">

                    <label
                        for="kode"
                        class="form-label"
                    >
                        Kode Program Studi
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="kode"
                        name="kode"
                        placeholder="Contoh: TI"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label
                        for="nama"
                        class="form-label"
                    >
                        Nama Program Studi
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nama"
                        name="nama"
                        placeholder="Contoh: Teknik Informatika"
                        required
                    >

                </div>


                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Simpan
                    </button>

                    <a
                        href="/si-akademik_bkpmpt9/public/prodi"
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