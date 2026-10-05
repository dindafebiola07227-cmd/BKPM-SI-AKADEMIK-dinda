<?php

ob_start();

?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-primary shadow-sm mb-4">

    <div class="container">

        <a
            class="navbar-brand text-white fw-bold"
            href="/si-akademik_bkpmpt8/public/dashboard"
        >
            🎓 SI Akademik
        </a>

        <div class="d-flex align-items-center gap-2">

            <a
                href="/si-akademik_bkpmpt8/public/dashboard"
                class="btn btn-light btn-sm"
            >
                🏠 Beranda
            </a>

            <a
                href="/si-akademik_bkpmpt8/public/logout"
                class="btn btn-warning btn-sm"
            >
                🚪 Logout
            </a>

        </div>

    </div>

</nav>


<!-- Isi halaman -->
<div class="container">

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1 class="fw-bold text-primary mb-0">
                    📚 Daftar Mahasiswa
                </h1>

                <a
                    href="/si-akademik_bkpmpt8/public/mahasiswa/create"
                    class="btn btn-primary"
                >
                    + Tambah Mahasiswa
                </a>

            </div>


            <form
                method="GET"
                action="/si-akademik_bkpmpt8/public/mahasiswa"
                class="row g-2 mb-4"
            >

                <div class="col-md-10">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari berdasarkan NIM atau nama mahasiswa..."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    >

                </div>

                <div class="col-md-2 d-grid">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        🔍 Cari
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
                                        <?= htmlspecialchars($mhs['nim'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['nama'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['email'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['nama_prodi'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($mhs['angkatan'] ?? '') ?>
                                    </td>

                                    <td>

                                        <?php if (($mhs['status'] ?? '') === 'aktif'): ?>

                                            <span class="badge bg-success">
                                                Aktif
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($mhs['status'] ?? '-') ?>
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <div class="d-flex gap-1">

                                            <a
                                                href="/si-akademik_bkpmpt8/public/mahasiswa/<?= (int) $mhs['id'] ?>"
                                                class="btn btn-info btn-sm"
                                            >
                                                Detail
                                            </a>

                                            <a
                                                href="/si-akademik_bkpmpt8/public/mahasiswa/<?= (int) $mhs['id'] ?>/edit"
                                                class="btn btn-warning btn-sm"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="/si-akademik_bkpmpt8/public/mahasiswa/<?= (int) $mhs['id'] ?>/delete"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?');"
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