<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Data Fasilitas Kampus</h1>
    <a href="<?= site_url('admin/fasilitas/new') ?>" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Fasilitas
    </a>
</div>

<!-- Filter Bar -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="<?= site_url('admin/fasilitas') ?>" method="get" class="row">
            <div class="col-md-5 mb-2 mb-md-0">
                <input type="text" name="q" class="form-control" placeholder="Cari nama, kode, lokasi, atau spesifikasi..." value="<?= esc($keyword) ?>">
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select name="kategori" class="form-control">
                    <option value="">-- Semua Kategori --</option>
                    <?php foreach ($kategoriList as $k) : ?>
                        <option value="<?= $k['id'] ?>" <?= $kategoriId === (int) $k['id'] ? 'selected' : '' ?>>
                            <?= esc($k['nama']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <select name="status" class="form-control">
                    <option value="">-- Status --</option>
                    <option value="tersedia" <?= $status === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="reservasi" <?= $status === 'reservasi' ? 'selected' : '' ?>>Reservasi</option>
                    <option value="pemeliharaan" <?= $status === 'pemeliharaan' ? 'selected' : '' ?>>Pemeliharaan</option>
                </select>
            </div>
            <div class="col-md-2 d-flex">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter mr-1"></i> Filter</button>
                <?php if ($keyword !== '' || $kategoriId !== null || $status !== '') : ?>
                    <a href="<?= site_url('admin/fasilitas') ?>" class="btn btn-secondary ml-1" title="Reset"><i class="fas fa-times"></i></a>
                <?php endif ?>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Fasilitas -->
<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-items-center mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 80px;">Foto</th>
                        <th>Kode &amp; Nama Fasilitas</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>PIC</th>
                        <th class="text-right" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($fasilitas)) : ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Data fasilitas tidak ditemukan.</td></tr>
                    <?php else : ?>
                        <?php foreach ($fasilitas as $f) : ?>
                            <tr>
                                <td>
                                    <?php if (! empty($f['foto'])) : ?>
                                        <img src="<?= base_url('uploads/fasilitas/' . esc($f['foto'])) ?>" alt="" class="rounded" style="width: 60px; height: 45px; object-fit: cover;">
                                    <?php else : ?>
                                        <div class="bg-light rounded text-center text-muted" style="width: 60px; height: 45px; line-height: 45px;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark"><?= esc($f['nama']) ?></div>
                                    <small class="text-muted font-monospace"><i class="fas fa-barcode mr-1"></i><?= esc($f['kode']) ?></small>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-dark font-weight-bold"><?= esc($f['nama_kategori']) ?></span>
                                </td>
                                <td><small class="text-dark font-weight-bold"><?= esc($f['lokasi']) ?></small></td>
                                <td><span class="badge badge-info"><?= esc($f['kapasitas']) ?> Orang / Unit</span></td>
                                <td>
                                    <span class="badge badge-<?= $f['status'] === 'tersedia' ? 'success' : ($f['status'] === 'reservasi' ? 'warning' : 'danger') ?> text-uppercase">
                                        <?= esc($f['status']) ?>
                                    </span>
                                </td>
                                <td><small class="text-muted"><?= esc($f['penanggung_jawab']) ?></small></td>
                                <td class="text-right">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= site_url('fasilitas/' . esc($f['slug'])) ?>" target="_blank" class="btn btn-outline-info" title="Lihat publik">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= site_url('admin/fasilitas/' . $f['id'] . '/edit') ?>" class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <?php if (session('role') === 'admin') : ?>
                                            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
                                                    data-url="<?= site_url('admin/fasilitas/' . $f['id']) ?>"
                                                    data-nama="<?= esc($f['nama']) ?>" title="Hapus permanen">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (! empty($fasilitas)) : ?>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <span class="small text-muted">Menampilkan data fasilitas terdaftar</span>
            <div>
                <?= $pager->links('default', 'bootstrap_pagination') ?>
            </div>
        </div>
    <?php endif ?>
</div>
<?= $this->endSection() ?>

