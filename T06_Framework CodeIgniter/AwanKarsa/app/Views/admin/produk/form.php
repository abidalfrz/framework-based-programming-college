<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $produk !== null;
$errors = session('errors') ?? [];
$nilai  = static fn (string $f, $default = '') => old($f, $produk[$f] ?? $default);
$cls    = static fn (string $f): string => isset($errors[$f]) ? 'is-invalid' : '';
$fotoUrl = $isEdit && ! empty($produk['foto']) ? base_url('uploads/produk/' . $produk['foto']) : null;
$aktif   = old('nama') !== null ? (bool) old('aktif') : (bool) ($produk['aktif'] ?? true);
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent px-0 mb-2">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/produk') ?>">Produk</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit' : 'Tambah' ?></li>
    </ol>
</nav>
<h1 class="h3 mb-4 text-gray-800"><?= esc($title) ?></h1>

<form action="<?= $isEdit ? site_url('admin/produk/' . $produk['id']) : site_url('admin/produk') ?>" method="post" enctype="multipart/form-data" novalidate>
    <?= csrf_field() ?>
    <?php if ($isEdit) : ?><input type="hidden" name="_method" value="PUT"><?php endif ?>
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama" class="font-weight-bold">Nama paket</label>
                        <input type="text" id="nama" name="nama" value="<?= esc($nilai('nama')) ?>" class="form-control <?= $cls('nama') ?>">
                        <div class="invalid-feedback"><?= esc($errors['nama'] ?? '') ?></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="kategori_id" class="font-weight-bold">Kategori</label>
                            <select id="kategori_id" name="kategori_id" class="form-control <?= $cls('kategori_id') ?>">
                                <option value="">— Pilih —</option>
                                <?php foreach ($kategori as $k) : ?>
                                    <option value="<?= $k['id'] ?>" <?= (string) $nilai('kategori_id') === (string) $k['id'] ? 'selected' : '' ?>><?= esc($k['nama']) ?></option>
                                <?php endforeach ?>
                            </select>
                            <div class="invalid-feedback"><?= esc($errors['kategori_id'] ?? '') ?></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="harga" class="font-weight-bold">Harga per bulan (Rp)</label>
                            <input type="text" inputmode="numeric" id="harga" name="harga" value="<?= esc($nilai('harga')) ?>" class="form-control <?= $cls('harga') ?>">
                            <div class="invalid-feedback"><?= esc($errors['harga'] ?? '') ?></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="stok" class="font-weight-bold">Kapasitas tersedia (unit)</label>
                            <input type="number" id="stok" name="stok" min="0" value="<?= esc($nilai('stok', '0')) ?>" class="form-control <?= $cls('stok') ?>">
                            <div class="invalid-feedback"><?= esc($errors['stok'] ?? '') ?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi" class="font-weight-bold">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" class="form-control <?= $cls('deskripsi') ?>"><?= esc($nilai('deskripsi')) ?></textarea>
                        <div class="invalid-feedback"><?= esc($errors['deskripsi'] ?? '') ?></div>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="aktif" name="aktif" value="1" <?= $aktif ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="aktif">Tampilkan di katalog</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3"><h2 class="h6 m-0 font-weight-bold text-primary">Foto produk</h2></div>
                <div class="card-body text-center">
                    <img id="preview" src="<?= $fotoUrl ? esc($fotoUrl, 'attr') : '' ?>" alt="Pratinjau foto" class="foto-preview mb-3 <?= $fotoUrl ? '' : 'd-none' ?>">
                    <div id="preview-kosong" class="foto-preview mb-3 mx-auto align-items-center justify-content-center text-gray-400 <?= $fotoUrl ? 'd-none' : 'd-flex' ?>"><i class="fas fa-camera fa-2x"></i></div>
                    <div class="custom-file text-left">
                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" class="custom-file-input <?= $cls('foto') ?>">
                        <label class="custom-file-label" for="foto">Pilih gambar…</label>
                        <div class="invalid-feedback"><?= esc($errors['foto'] ?? '') ?></div>
                    </div>
                    <small class="form-text text-muted text-left">JPG, PNG, atau WEBP maksimal 2 MB. Tanpa foto, toko menampilkan ikon kategori.</small>
                    <?php if ($fotoUrl) : ?>
                        <div class="custom-control custom-checkbox text-left mt-3">
                            <input type="checkbox" class="custom-control-input" id="hapus_foto" name="hapus_foto" value="1">
                            <label class="custom-control-label" for="hapus_foto">Hapus foto saat ini</label>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?></button>
    <a href="<?= site_url('admin/produk') ?>" class="btn btn-light ml-2">Batal</a>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('foto').addEventListener('change', function () {
        var file = this.files[0];
        if (!file) { return; }
        this.nextElementSibling.textContent = file.name;
        var img = document.getElementById('preview');
        img.src = URL.createObjectURL(file);
        img.classList.remove('d-none');
        var kosong = document.getElementById('preview-kosong');
        kosong.classList.remove('d-flex');
        kosong.classList.add('d-none');
    });
</script>
<?= $this->endSection() ?>
