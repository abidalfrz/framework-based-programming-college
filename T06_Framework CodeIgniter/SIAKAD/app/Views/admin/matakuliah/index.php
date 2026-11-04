<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Mata Kuliah</h1>
    <a href="<?= site_url('admin/matakuliah/new') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Mata Kuliah</a>
</div>

<div class="card shadow mb-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th class="pl-4">Kode</th><th>Nama</th><th class="text-center">SKS</th><th class="text-center">Smt</th><th>Dosen pengampu</th><th class="text-center">Kuota</th><th class="text-right pr-4">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($matakuliah as $mk) : ?>
                <tr>
                    <td class="pl-4 font-weight-bold"><?= esc($mk['kode']) ?></td>
                    <td><?= esc($mk['nama']) ?></td>
                    <td class="text-center tabular"><?= $mk['sks'] ?></td>
                    <td class="text-center tabular"><?= $mk['semester'] ?></td>
                    <td class="small"><?= esc($mk['nama_dosen']) ?></td>
                    <td class="text-center tabular"><?= $mk['kuota'] ?></td>
                    <td class="text-right pr-4 text-nowrap">
                        <a href="<?= site_url('admin/matakuliah/' . $mk['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i> Edit</a>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
                                data-action="<?= site_url('admin/matakuliah/' . $mk['id']) ?>"
                                data-pesan="Hapus mata kuliah <?= esc($mk['kode'] . ' ' . $mk['nama'], 'attr') ?>?">
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
