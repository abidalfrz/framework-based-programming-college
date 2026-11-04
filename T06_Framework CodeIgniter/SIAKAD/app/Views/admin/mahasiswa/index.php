<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Mahasiswa</h1>
    <a href="<?= site_url('admin/mahasiswa/new') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Mahasiswa</a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <form method="get" class="form-inline" role="search">
            <label for="q" class="sr-only">Cari</label>
            <input type="search" id="q" name="q" value="<?= esc($keyword) ?>" class="form-control mr-2" placeholder="Cari NIM atau nama…">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i><span class="sr-only">Cari</span></button>
            <?php if ($keyword !== '') : ?><a href="<?= site_url('admin/mahasiswa') ?>" class="btn btn-link">Reset</a><?php endif ?>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th class="pl-4">NIM</th><th>Nama</th><th>Angkatan</th><th>Dosen wali</th><th class="text-right pr-4">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($mahasiswa as $m) : ?>
                <tr>
                    <td class="pl-4 tabular font-weight-bold"><?= esc($m['nim']) ?></td>
                    <td><?= esc($m['nama']) ?><div class="small text-gray-600"><?= esc($m['email']) ?></div></td>
                    <td class="tabular"><?= esc($m['angkatan']) ?></td>
                    <td class="small"><?= esc($m['nama_wali']) ?></td>
                    <td class="text-right pr-4 text-nowrap">
                        <a href="<?= site_url('admin/mahasiswa/' . $m['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i> Edit</a>
                        <form action="<?= site_url('admin/mahasiswa/' . $m['id'] . '/reset-password') ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Reset password ke NIM"><i class="fas fa-key"></i><span class="sr-only">Reset password</span></button>
                        </form>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
                                data-action="<?= site_url('admin/mahasiswa/' . $m['id']) ?>"
                                data-pesan="Hapus mahasiswa <?= esc($m['nim'] . ' ' . $m['nama'], 'attr') ?> beserta akun loginnya?">
                            <i class="fas fa-trash-alt"></i><span class="sr-only">Hapus</span>
                        </button>
                    </td>
                </tr>
            <?php endforeach ?>
            <?php if ($mahasiswa === []) : ?>
                <tr><td colspan="5" class="text-center text-gray-600 py-4">Tidak ada mahasiswa yang cocok.</td></tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white"><?= $pager->links('default', 'bootstrap') ?></div>
</div>
<?= $this->endSection() ?>
