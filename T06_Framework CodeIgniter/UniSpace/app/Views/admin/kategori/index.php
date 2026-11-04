<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Kategori Fasilitas &amp; Sarpras</h1>
    <a href="<?= site_url('admin/kategori/new') ?>" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Kategori
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-items-center mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 70px;">Kode</th>
                        <th>Nama Kategori</th>
                        <th>Ikon</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Fasilitas</th>
                        <th class="text-right" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kategoriList)) : ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada kategori fasilitas.</td></tr>
                    <?php else : ?>
                        <?php foreach ($kategoriList as $k) : ?>
                            <tr>
                                <td class="font-weight-bold font-monospace"><?= esc($k['kode']) ?></td>
                                <td>
                                    <span class="font-weight-bold text-dark"><?= esc($k['nama']) ?></span><br>
                                    <small class="text-muted">Slug: <?= esc($k['slug']) ?></small>
                                </td>
                                <td><code><?= esc($k['icon'] ?: '-') ?></code></td>
                                <td><small class="text-muted"><?= esc($k['deskripsi'] ?: '-') ?></small></td>
                                <td>
                                    <span class="badge badge-primary badge-pill px-3 py-2">
                                        <?= (int) $k['jumlah_fasilitas'] ?> Fasilitas
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= site_url('admin/kategori/' . $k['id'] . '/edit') ?>" class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
                                                data-url="<?= site_url('admin/kategori/' . $k['id']) ?>"
                                                data-nama="<?= esc($k['nama']) ?>" title="Hapus kategori">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

