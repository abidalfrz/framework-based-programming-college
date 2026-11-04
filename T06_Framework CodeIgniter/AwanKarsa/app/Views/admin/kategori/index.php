<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kategori</h1>
    <a href="<?= site_url('admin/kategori/new') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Kategori</a>
</div>
<div class="card shadow mb-4">
    <table class="table table-hover mb-0">
        <thead><tr><th class="pl-4">Kategori</th><th>Slug</th><th class="text-center">Produk aktif</th><th class="text-right pr-4">Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($kategori as $k) : ?>
            <tr>
                <td class="pl-4 font-weight-bold"><i class="bi bi-<?= esc($k['ikon'], 'attr') ?> text-primary mr-2" aria-hidden="true"></i><?= esc($k['nama']) ?></td>
                <td><code><?= esc($k['slug']) ?></code></td>
                <td class="text-center tabular"><?= $k['jumlah'] ?></td>
                <td class="text-right pr-4 text-nowrap">
                    <a href="<?= site_url('admin/kategori/' . $k['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i> Edit</a>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
                            data-action="<?= site_url('admin/kategori/' . $k['id']) ?>" data-pesan="Hapus kategori <?= esc($k['nama'], 'attr') ?>?">
                        <i class="fas fa-trash-alt"></i><span class="sr-only">Hapus</span>
                    </button>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
