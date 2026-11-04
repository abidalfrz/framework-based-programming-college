<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Produk</h1>
    <a href="<?= site_url('admin/produk/new') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Produk</a>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <form method="get" class="form-inline" role="search">
            <label for="q" class="sr-only">Cari produk</label>
            <input type="search" id="q" name="q" value="<?= esc($q) ?>" class="form-control mr-2" placeholder="Cari nama produk…">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i><span class="sr-only">Cari</span></button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th class="pl-4">Produk</th><th>Kategori</th><th class="text-right">Harga</th><th class="text-center">Stok</th><th class="text-center">Status</th><th class="text-right pr-4">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($produk as $p) : ?>
                <tr class="<?= $p['aktif'] ? '' : 'text-gray-500' ?>">
                    <td class="pl-4"><div class="d-flex align-items-center"><div class="thumb-admin mr-3"><?= foto_produk($p) ?></div><span class="font-weight-bold"><?= esc($p['nama']) ?></span></div></td>
                    <td><?= esc($p['nama_kategori']) ?></td>
                    <td class="text-right tabular"><?= rupiah($p['harga']) ?></td>
                    <td class="text-center tabular <?= (int) $p['stok'] === 0 ? 'text-danger font-weight-bold' : '' ?>"><?= $p['stok'] ?></td>
                    <td class="text-center"><?= $p['aktif'] ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Nonaktif</span>' ?></td>
                    <td class="text-right pr-4 text-nowrap">
                        <a href="<?= site_url('admin/produk/' . $p['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i> Edit</a>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
                                data-action="<?= site_url('admin/produk/' . $p['id']) ?>"
                                data-pesan="Hapus produk <?= esc($p['nama'], 'attr') ?>? Produk yang sudah pernah dipesan hanya akan dinonaktifkan.">
                            <i class="fas fa-trash-alt"></i><span class="sr-only">Hapus</span>
                        </button>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white"><?= $pager->links('default', 'bootstrap') ?></div>
</div>
<?= $this->endSection() ?>
