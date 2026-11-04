<?= $this->extend('layouts/toko') ?>

<?= $this->section('content') ?>
<?php
$urutan = \App\Models\ProdukModel::URUTAN;
$params = static fn (array $ubah): string => '?' . http_build_query(array_filter(array_merge(['q' => $q, 'kategori' => $kategoriAktif, 'urut' => $urut], $ubah)));
$namaKategori = '';
foreach ($kategori as $k) {
    if ($k['slug'] === $kategoriAktif) {
        $namaKategori = $k['nama'];
    }
}
?>
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-1"><?= $namaKategori !== '' ? esc($namaKategori) : 'Katalog Paket' ?></h1>
        <p class="text-secondary mb-0">
            <?= $pager->getTotal() ?> paket<?= $q !== '' ? ' untuk "<strong>' . esc($q) . '</strong>"' : '' ?>
        </p>
    </div>
    <form method="get" class="d-flex gap-2 align-items-center">
        <?php if ($q !== '') : ?><input type="hidden" name="q" value="<?= esc($q) ?>"><?php endif ?>
        <?php if ($kategoriAktif !== '') : ?><input type="hidden" name="kategori" value="<?= esc($kategoriAktif) ?>"><?php endif ?>
        <label for="urut" class="text-secondary small text-nowrap">Urutkan</label>
        <select id="urut" name="urut" class="form-select" onchange="this.form.submit()">
            <?php foreach ($urutan as $kunci => [, , $label]) : ?>
                <option value="<?= $kunci ?>" <?= $kunci === $urut ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach ?>
        </select>
        <noscript><button class="btn btn-toko">Terapkan</button></noscript>
    </form>
</div>

<div class="chip-row mb-4" role="navigation" aria-label="Filter kategori">
    <a href="<?= site_url('katalog') . $params(['kategori' => null]) ?>" class="chip <?= $kategoriAktif === '' ? 'aktif' : '' ?>">Semua</a>
    <?php foreach ($kategori as $k) : ?>
        <a href="<?= site_url('katalog') . $params(['kategori' => $k['slug']]) ?>" class="chip <?= $kategoriAktif === $k['slug'] ? 'aktif' : '' ?>">
            <i class="bi bi-<?= esc($k['ikon'], 'attr') ?>" aria-hidden="true"></i> <?= esc($k['nama']) ?>
        </a>
    <?php endforeach ?>
</div>

<?php if ($produk === []) : ?>
    <div class="empty-state">
        <i class="bi bi-search" aria-hidden="true"></i>
        <h2 class="h5 fw-bold">Paket tidak ditemukan</h2>
        <p class="text-secondary">Coba kata kunci lain atau hapus filter kategori.</p>
        <a href="<?= site_url('katalog') ?>" class="btn btn-toko">Lihat semua paket</a>
    </div>
<?php else : ?>
    <div class="row g-3">
        <?php foreach ($produk as $p) : ?>
            <div class="col-6 col-md-4 col-lg-3"><?= $this->setData(['p' => $p])->include('partials/produk_card') ?></div>
        <?php endforeach ?>
    </div>
    <div class="mt-4 d-flex justify-content-center"><?= $pager->links('default', 'bootstrap') ?></div>
<?php endif ?>
<?= $this->endSection() ?>
