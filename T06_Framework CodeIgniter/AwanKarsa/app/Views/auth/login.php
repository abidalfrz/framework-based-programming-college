<?= $this->extend('layouts/toko') ?>

<?= $this->section('content') ?>
<?php $errors = session('errors') ?? []; ?>
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="surface p-4 p-md-5">
            <h1 class="h3 fw-bold mb-1">Masuk</h1>
            <p class="text-secondary mb-4">Belum punya akun? <a href="<?= site_url('daftar') ?>" class="link-toko">Daftar gratis</a></p>
            <form action="<?= site_url('login') ?>" method="post" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" autocomplete="email" class="form-control form-control-lg <?= isset($errors['email']) ? 'is-invalid' : '' ?>" autofocus>
                    <div class="invalid-feedback"><?= esc($errors['email'] ?? '') ?></div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" class="form-control form-control-lg <?= isset($errors['password']) ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback"><?= esc($errors['password'] ?? '') ?></div>
                </div>
                <button type="submit" class="btn btn-toko btn-lg w-100">Masuk</button>
            </form>
            <p class="small text-secondary mt-4 mb-0">Akun demo: <code>budi@mail.test / pembeli123</code> &middot; admin: <code>admin@awankarsa.test / admin123</code></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
