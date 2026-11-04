<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Dosen</h1>
    <a href="<?= site_url('admin/dosen/new') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Dosen</a>
</div>

<div class="card shadow mb-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th class="pl-4">NIDN</th><th>Nama</th><th class="text-center">Mata kuliah</th><th class="text-center">Mhs wali</th><th class="text-right pr-4">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($dosen as $d) : ?>
                <tr>
                    <td class="pl-4 tabular font-weight-bold"><?= esc($d['nidn']) ?></td>
                    <td><?= esc($d['nama']) ?><div class="small text-gray-600"><?= esc($d['email']) ?></div></td>
                    <td class="text-center tabular"><?= $d['jumlah_mk'] ?></td>
                    <td class="text-center tabular"><?= $d['jumlah_wali'] ?></td>
                    <td class="text-right pr-4 text-nowrap">
                        <a href="<?= site_url('admin/dosen/' . $d['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i> Edit</a>
                        <form action="<?= site_url('admin/dosen/' . $d['id'] . '/reset-password') ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Reset password ke NIDN"><i class="fas fa-key"></i><span class="sr-only">Reset password</span></button>
                        </form>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
                                data-action="<?= site_url('admin/dosen/' . $d['id']) ?>"
                                data-pesan="Hapus dosen <?= esc($d['nama'], 'attr') ?> beserta akun loginnya?">
                            <i class="fas fa-trash-alt"></i><span class="sr-only">Hapus</span>
                        </button>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
