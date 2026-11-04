<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $dosen !== null;
$errors = session('errors') ?? [];
$nilai  = static fn (string $f) => old($f, $dosen[$f] ?? '');
$field  = static fn (string $f): string => isset($errors[$f]) ? 'is-invalid' : '';
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent px-0 mb-2">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dosen') ?>">Data Dosen</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit' : 'Tambah' ?></li>
    </ol>
</nav>
<h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>

<div class="row">
    <div class="col-lg-7">
        <form action="<?= $isEdit ? site_url('admin/dosen/' . $dosen['id']) : site_url('admin/dosen') ?>" method="post" class="card shadow mb-4" novalidate>
            <?= csrf_field() ?>
            <?php if ($isEdit) : ?><input type="hidden" name="_method" value="PUT"><?php endif ?>
            <div class="card-body">
                <div class="form-group">
                    <label for="nidn" class="font-weight-bold">NIDN</label>
                    <input type="text" id="nidn" name="nidn" value="<?= esc($nilai('nidn')) ?>" inputmode="numeric" maxlength="10" class="form-control <?= $field('nidn') ?>">
                    <small class="form-text text-muted">10 digit. Dipakai sebagai username dan password awal.</small>
                    <div class="invalid-feedback"><?= esc($errors['nidn'] ?? '') ?></div>
                </div>
                <div class="form-group">
                    <label for="nama" class="font-weight-bold">Nama lengkap &amp; gelar</label>
                    <input type="text" id="nama" name="nama" value="<?= esc($nilai('nama')) ?>" class="form-control <?= $field('nama') ?>">
                    <div class="invalid-feedback"><?= esc($errors['nama'] ?? '') ?></div>
                </div>
                <div class="form-group mb-0">
                    <label for="email" class="font-weight-bold">Email</label>
                    <input type="email" id="email" name="email" value="<?= esc($nilai('email')) ?>" class="form-control <?= $field('email') ?>">
                    <div class="invalid-feedback"><?= esc($errors['email'] ?? '') ?></div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?></button>
                <a href="<?= site_url('admin/dosen') ?>" class="btn btn-light ml-2">Batal</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
