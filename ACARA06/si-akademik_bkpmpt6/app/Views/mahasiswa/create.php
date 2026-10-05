<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tambah Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

    <div class="container mt-4">

        <h1 class="mb-4">Tambah Mahasiswa</h1>

        <form action="/mahasiswa" method="POST">

            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>

                <input type="text"
                       class="form-control"
                       id="nim"
                       name="nim"
                       placeholder="Masukkan NIM">
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>

                <input type="text"
                       class="form-control"
                       id="nama"
                       name="nama"
                       placeholder="Masukkan nama mahasiswa">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>

                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       placeholder="Masukkan email">
            </div>

            <div class="mb-3">
                <label for="angkatan" class="form-label">Angkatan</label>

                <input type="number"
                       class="form-control"
                       id="angkatan"
                       name="angkatan"
                       placeholder="Masukkan tahun angkatan">
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan
            </button>

            <a href="/mahasiswa" class="btn btn-secondary">
                Kembali
            </a>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>