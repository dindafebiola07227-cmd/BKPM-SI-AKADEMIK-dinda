<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-3">

    <?php if (!empty($_SESSION['flash'])): ?>

        <div
            class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'] ?? 'info') ?> alert-dismissible fade show"
            role="alert"
        >

            <?= htmlspecialchars($_SESSION['flash']['message'] ?? '') ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

        <?php unset($_SESSION['flash']); ?>

    <?php endif; ?>


    <?= $content ?? '' ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

