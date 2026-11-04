<?php
/**
 * Menampilkan flash message (success / error) dan daftar error validasi.
 * Dipakai bersama oleh layout publik (Bootstrap 5) dan admin (Bootstrap 4).
 */
$errors = session('errors');
?>
<?php if (session('success')) : ?>
    <div class="alert alert-success alert-flash" role="status"><?= esc(session('success')) ?></div>
<?php endif ?>

<?php if (session('error')) : ?>
    <div class="alert alert-danger alert-flash" role="alert"><?= esc(session('error')) ?></div>
<?php endif ?>

<?php if (is_array($errors) && $errors !== []) : ?>
    <div class="alert alert-danger" role="alert">
        <strong>Data belum dapat disimpan:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach ($errors as $e) : ?>
                <li><?= esc($e) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
