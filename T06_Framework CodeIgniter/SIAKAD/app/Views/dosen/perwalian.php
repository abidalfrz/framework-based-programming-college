<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-1 text-gray-800">Mahasiswa Perwalian</h1>
<p class="text-gray-600 mb-4">Pantau IPK dan beban SKS <?= esc(label_periode($akademik->tahunAjaran, $akademik->semester)) ?>.</p>

<div class="card shadow mb-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th class="pl-4">NIM</th><th>Nama</th><th class="text-center">Angkatan</th><th class="text-right">SKS lulus</th><th class="text-right">IPK</th><th class="text-right">SKS semester ini</th><th class="pr-4"></th></tr></thead>
            <tbody>
            <?php foreach ($mahasiswa as $m) : ?>
                <tr>
                    <td class="pl-4 tabular font-weight-bold"><?= esc($m['nim']) ?></td>
                    <td><?= esc($m['nama']) ?></td>
                    <td class="text-center tabular"><?= esc($m['angkatan']) ?></td>
                    <td class="text-right tabular"><?= $m['sks_lulus'] ?></td>
                    <td class="text-right tabular font-weight-bold <?= $m['sks_lulus'] > 0 && $m['ipk'] < 2 ? 'text-danger' : '' ?>"><?= $m['sks_lulus'] > 0 ? format_ip($m['ipk']) : '–' ?></td>
                    <td class="text-right tabular"><?= $m['sks_ini'] === 0 ? '<span class="badge badge-warning">Belum isi KRS</span>' : $m['sks_ini'] ?></td>
                    <td class="pr-4 text-right"><a href="<?= site_url('dosen/perwalian/' . $m['id']) ?>" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            <?php endforeach ?>
            <?php if ($mahasiswa === []) : ?>
                <tr><td colspan="7" class="text-center text-gray-600 py-4">Belum ada mahasiswa perwalian.</td></tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
