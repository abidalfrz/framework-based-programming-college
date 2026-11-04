<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $mahasiswa !== null;
$errors = session('errors') ?? [];
$nilai  = static fn (string $f) => old($f, $mahasiswa[$f] ?? '');
$field  = static fn (string $f): string => isset($errors[$f]) ? 'is-invalid' : '';
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent px-0 mb-2">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/mahasiswa') ?>">Data Mahasiswa</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit' : 'Tambah' ?></li>
    </ol>
</nav>
<h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>

<div class="row">
    <div class="col-lg-8">
        <form action="<?= $isEdit ? site_url('admin/mahasiswa/' . $mahasiswa['id']) : site_url('admin/mahasiswa') ?>" method="post" class="card shadow mb-4" novalidate>
            <?= csrf_field() ?>
            <?php if ($isEdit) : ?><input type="hidden" name="_method" value="PUT"><?php endif ?>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="nim" class="font-weight-bold">NIM</label>
                        <input type="text" id="nim" name="nim" value="<?= esc($nilai('nim')) ?>" inputmode="numeric" maxlength="10" class="form-control <?= $field('nim') ?>">
                        <small class="form-text text-muted">10 digit. Juga dipakai sebagai username login.</small>
                        <div class="invalid-feedback"><?= esc($errors['nim'] ?? '') ?></div>
                    </div>
                    <div class="form-group col-md-7">
                        <label for="nama" class="font-weight-bold">Nama lengkap</label>
                        <input type="text" id="nama" name="nama" value="<?= esc($nilai('nama')) ?>" class="form-control <?= $field('nama') ?>">
                        <div class="invalid-feedback"><?= esc($errors['nama'] ?? '') ?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="font-weight-bold">Email</label>
                    <input type="email" id="email" name="email" value="<?= esc($nilai('email')) ?>" class="form-control <?= $field('email') ?>">
                    <div class="invalid-feedback"><?= esc($errors['email'] ?? '') ?></div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="angkatan" class="font-weight-bold">Angkatan</label>
                        <input type="number" id="angkatan" name="angkatan" value="<?= esc($nilai('angkatan') ?: date('Y')) ?>" class="form-control <?= $field('angkatan') ?>">
                        <div class="invalid-feedback"><?= esc($errors['angkatan'] ?? '') ?></div>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="dosen_wali_id" class="font-weight-bold">Dosen wali</label>
                        <select id="dosen_wali_id" name="dosen_wali_id" class="form-control <?= $field('dosen_wali_id') ?>">
                            <option value="">— Pilih dosen —</option>
                            <?php foreach ($dosen as $id => $nama) : ?>
                                <option value="<?= $id ?>" <?= (string) $nilai('dosen_wali_id') === (string) $id ? 'selected' : '' ?>><?= esc($nama) ?></option>
                            <?php endforeach ?>
                        </select>
                        <div class="invalid-feedback"><?= esc($errors['dosen_wali_id'] ?? '') ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?></button>
                <a href="<?= site_url('admin/mahasiswa') ?>" class="btn btn-light ml-2">Batal</a>
            </div>
        </form>
    </div>
    <div class="col-lg-4">
        <div class="card border-left-primary shadow-sm mb-4">
            <div class="card-body small">
                <h2 class="h6 font-weight-bold">Akun login otomatis</h2>
                <p class="mb-0">Saat disimpan, sistem membuat akun dengan username dan password awal = NIM dalam satu transaksi database. Mahasiswa dapat mengganti password setelah login.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
