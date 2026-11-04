<?= $this->extend('layouts/toko') ?>

<?= $this->section('content') ?>
<?php
$errors = session('errors') ?? [];
$cls    = static fn (string $f): string => isset($errors[$f]) ? 'is-invalid' : '';
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="surface p-4 p-md-5">
            <h1 class="h3 fw-bold mb-1">Daftar akun pelanggan</h1>
            <p class="text-secondary mb-4">Sudah punya akun? <a href="<?= site_url('login') ?>" class="link-toko">Masuk</a></p>
            <form action="<?= site_url('daftar') ?>" method="post" novalidate>
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label fw-bold">Nama lengkap</label>
                        <input type="text" id="nama" name="nama" value="<?= esc(old('nama')) ?>" autocomplete="name" class="form-control <?= $cls('nama') ?>">
                        <div class="invalid-feedback"><?= esc($errors['nama'] ?? '') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" autocomplete="email" class="form-control <?= $cls('email') ?>">
                        <div class="invalid-feedback"><?= esc($errors['email'] ?? '') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="telepon" class="form-label fw-bold">No. HP</label>
                        <input type="tel" id="telepon" name="telepon" value="<?= esc(old('telepon')) ?>" autocomplete="tel" placeholder="08xxxxxxxxxx" class="form-control <?= $cls('telepon') ?>">
                        <div class="invalid-feedback"><?= esc($errors['telepon'] ?? '') ?></div>
                    </div>
                    <div class="col-12">
                        <label for="alamat" class="form-label fw-bold">Alamat pelanggan <span class="fw-normal text-secondary">(opsional)</span></label>
                        <textarea id="alamat" name="alamat" rows="3" autocomplete="street-address" class="form-control <?= $cls('alamat') ?>"><?= esc(old('alamat')) ?></textarea>
                        <div class="invalid-feedback"><?= esc($errors['alamat'] ?? '') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" id="password" name="password" autocomplete="new-password" class="form-control <?= $cls('password') ?>">
                        <div class="form-text">Minimal 8 karakter.</div>
                        <div class="invalid-feedback"><?= esc($errors['password'] ?? '') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="konfirmasi" class="form-label fw-bold">Ulangi password</label>
                        <input type="password" id="konfirmasi" name="konfirmasi" autocomplete="new-password" class="form-control <?= $cls('konfirmasi') ?>">
                        <div class="invalid-feedback"><?= esc($errors['konfirmasi'] ?? '') ?></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-toko btn-lg mt-4">Buat akun</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
