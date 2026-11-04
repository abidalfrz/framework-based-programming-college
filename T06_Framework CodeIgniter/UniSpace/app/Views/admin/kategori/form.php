<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$isEdit = ! empty($kategori);
$action = $isEdit ? site_url('admin/kategori/' . $kategori['id']) : site_url('admin/kategori');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><?= esc($title) ?></h1>
    <a href="<?= site_url('admin/kategori') ?>" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Kategori Fasilitas</h6>
            </div>
            <div class="card-body">
                <form action="<?= $action ?>" method="post">
                    <?= csrf_field() ?>
                    <?php if ($isEdit) : ?>
                        <input type="hidden" name="_method" value="PUT">
                    <?php endif ?>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Kode Singkat <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control" value="<?= old('kode', $kategori['kode'] ?? '') ?>" required placeholder="Misal: LAB, KUL, SEM">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= old('nama', $kategori['nama'] ?? '') ?>" required placeholder="Contoh: Laboratorium & Komputasi">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nama Ikon Bootstrap</label>
                        <input type="text" name="icon" class="form-control" value="<?= old('icon', $kategori['icon'] ?? 'bi-building') ?>" placeholder="Contoh: bi-laptop, bi-easel, bi-people">
                        <small class="form-text text-muted">Gunakan class icon dari Bootstrap Icons (misal: <code>bi-laptop</code>, <code>bi-building</code>).</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Deskripsi Kategori</label>
                        <textarea name="deskripsi" rows="3" class="form-control" placeholder="Penjelasan singkat ruang lingkup kategori fasilitas ini..."><?= old('deskripsi', $kategori['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <a href="<?= site_url('admin/kategori') ?>" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary font-weight-bold px-4">
                            <i class="fas fa-save mr-1"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

