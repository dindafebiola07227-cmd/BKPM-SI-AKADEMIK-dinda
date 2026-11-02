<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/dashboard">🎓 SI Akademik</a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/dashboard">🏠 Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/mahasiswa">📚 Mahasiswa</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/prodi">🎓 Prodi</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/matakuliah">📖 Mata Kuliah</a></li>
            </ul>

            <a class="btn btn-light btn-sm" href="<?= BASE_URL ?>/logout">🚪 Logout</a>
        </div>

    </div>
</nav>
