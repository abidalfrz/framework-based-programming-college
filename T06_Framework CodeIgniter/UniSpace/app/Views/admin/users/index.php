<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Kelola Akun Petugas Sarpras</h1>
    <a href="<?= site_url('admin/users/new') ?>" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
        <i class="fas fa-user-plus fa-sm text-white-50 mr-1"></i> Tambah Petugas
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-items-center mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Nama Petugas</th>
                        <th>Username</th>
                        <th>Peran (Role)</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-right" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u) : ?>
                        <tr>
                            <td class="font-weight-bold text-dark"><?= esc($u['nama']) ?></td>
                            <td><span class="font-monospace text-muted"><?= esc($u['username']) ?></span></td>
                            <td>
                                <span class="badge badge-<?= $u['role'] === 'admin' ? 'primary' : 'success' ?> text-uppercase">
                                    <?= esc($u['role']) ?>
                                </span>
                            </td>
                            <td class="small text-muted"><?= date('d F Y', strtotime($u['created_at'])) ?></td>
                            <td class="text-right">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= site_url('admin/users/' . $u['id'] . '/edit') ?>" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <?php if ((int) session('user_id') !== (int) $u['id']) : ?>
                                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
                                                data-url="<?= site_url('admin/users/' . $u['id']) ?>"
                                                data-nama="<?= esc($u['nama']) ?>" title="Hapus akun">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

