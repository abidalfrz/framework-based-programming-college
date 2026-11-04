<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $mk !== null;
$errors = session('errors') ?? [];
$nilai  = static fn (string $f) => old($f, $mk[$f] ?? '');
$field  = static fn (string $f): string => isset($errors[$f]) ? 'is-invalid' : '';
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent px-0 mb-2">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/matakuliah') ?>">Mata Kuliah</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit' : 'Tambah' ?></li>
    </ol>
</nav>
<h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>

<div class="row">
    <div class="col-lg-8">
        <form action="<?= $isEdit ? site_url('admin/matakuliah/' . $mk['id']) : site_url('admin/matakuliah') ?>" method="post" class="card shadow mb-4" novalidate>
            <?= csrf_field() ?>
            <?php if ($isEdit) : ?><input type="hidden" name="_method" value="PUT"><?php endif ?>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="kode" class="font-weight-bold">Kode</label>
                        <input type="text" id="kode" name="kode" value="<?= esc($nilai('kode')) ?>" maxlength="10" class="form-control text-uppercase <?= $field('kode') ?>">
                        <div class="invalid-feedback"><?= esc($errors['kode'] ?? '') ?></div>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="nama" class="font-weight-bold">Nama mata kuliah</label>
                        <input type="text" id="nama" name="nama" value="<?= esc($nilai('nama')) ?>" class="form-control <?= $field('nama') ?>">
                        <div class="invalid-feedback"><?= esc($errors['nama'] ?? '') ?></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="sks" class="font-weight-bold">SKS</label>
                        <input type="number" id="sks" name="sks" value="<?= esc($nilai('sks')) ?>" min="1" max="6" class="form-control <?= $field('sks') ?>">
                        <div class="invalid-feedback"><?= esc($errors['sks'] ?? '') ?></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="semester" class="font-weight-bold">Semester</label>
                        <input type="number" id="semester" name="semester" value="<?= esc($nilai('semester')) ?>" min="1" max="8" class="form-control <?= $field('semester') ?>">
                        <small class="form-text text-muted">Ganjil dibuka di semester ganjil.</small>
                        <div class="invalid-feedback"><?= esc($errors['semester'] ?? '') ?></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="kuota" class="font-weight-bold">Kuota</label>
                        <input type="number" id="kuota" name="kuota" value="<?= esc($nilai('kuota') ?: 40) ?>" min="1" max="200" class="form-control <?= $field('kuota') ?>">
                        <div class="invalid-feedback"><?= esc($errors['kuota'] ?? '') ?></div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label for="dosen_id" class="font-weight-bold">Dosen pengampu</label>
                    <select id="dosen_id" name="dosen_id" class="form-control <?= $field('dosen_id') ?>">
                        <option value="">— Pilih dosen —</option>
                        <?php foreach ($dosen as $id => $nama) : ?>
                            <option value="<?= $id ?>" <?= (string) $nilai('dosen_id') === (string) $id ? 'selected' : '' ?>><?= esc($nama) ?></option>
                        <?php endforeach ?>
                    </select>
                    <div class="invalid-feedback"><?= esc($errors['dosen_id'] ?? '') ?></div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?></button>
                <a href="<?= site_url('admin/matakuliah') ?>" class="btn btn-light ml-2">Batal</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
