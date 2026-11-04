<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $kategori !== null;
$errors = session('errors') ?? [];
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent px-0 mb-2">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/kategori') ?>">Kategori</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit' : 'Tambah' ?></li>
    </ol>
</nav>
<h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>
<div class="row">
    <div class="col-lg-6">
        <form action="<?= $isEdit ? site_url('admin/kategori/' . $kategori['id']) : site_url('admin/kategori') ?>" method="post" class="card shadow" novalidate>
            <?= csrf_field() ?>
            <?php if ($isEdit) : ?><input type="hidden" name="_method" value="PUT"><?php endif ?>
            <div class="card-body">
                <div class="form-group">
                    <label for="nama" class="font-weight-bold">Nama kategori</label>
                    <input type="text" id="nama" name="nama" value="<?= esc(old('nama', $kategori['nama'] ?? '')) ?>" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>">
                    <small class="form-text text-muted">Slug URL dibuat otomatis dari nama.</small>
                    <div class="invalid-feedback"><?= esc($errors['nama'] ?? '') ?></div>
                </div>
                <div class="form-group mb-0">
                    <label for="ikon" class="font-weight-bold">Ikon <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener" class="font-weight-normal small">(nama Bootstrap Icons)</a></label>
                    <input type="text" id="ikon" name="ikon" value="<?= esc(old('ikon', $kategori['ikon'] ?? 'bag')) ?>" class="form-control <?= isset($errors['ikon']) ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback"><?= esc($errors['ikon'] ?? '') ?></div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                <a href="<?= site_url('admin/kategori') ?>" class="btn btn-light ml-2">Batal</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
