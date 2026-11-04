<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-end justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Transkrip Akademik</h1>
        <span class="text-gray-600">Mata kuliah yang diulang hanya dihitung nilai terbaiknya.</span>
    </div>
    <a href="<?= site_url('mahasiswa/transkrip/cetak') ?>" class="btn btn-primary mt-2 mt-sm-0" target="_blank" rel="noopener"><i class="fas fa-print mr-1"></i> Cetak Transkrip</a>
</div>

<div class="row">
    <div class="col-xl-8">
        <?php foreach ($perSemester as $smt => $baris) : ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h2 class="h6 m-0 font-weight-bold text-primary">Semester <?= $smt ?></h2>
                    <span class="small tabular"><?= array_sum(array_column($baris, 'sks')) ?> SKS</span>
                </div>
                <table class="table mb-0">
                    <thead><tr><th class="pl-4">Kode</th><th>Mata kuliah</th><th class="text-center">SKS</th><th class="text-center pr-4">Nilai</th></tr></thead>
                    <tbody>
                    <?php foreach ($baris as $b) : ?>
                        <tr><td class="pl-4 font-weight-bold"><?= esc($b['kode']) ?></td><td><?= esc($b['nama_mk']) ?></td><td class="text-center tabular"><?= $b['sks'] ?></td><td class="text-center pr-4"><?= badge_nilai($b['nilai_huruf']) ?></td></tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach ?>
        <?php if ($perSemester === []) : ?>
            <div class="alert alert-info">Belum ada mata kuliah yang dinilai.</div>
        <?php endif ?>
    </div>
    <div class="col-xl-4">
        <div class="ipk-hero shadow p-4 mb-4">
            <div class="label">IPK</div>
            <div class="angka my-2"><?= $totalSks > 0 ? format_ip($ipk) : '–' ?></div>
            <div class="small">Total <?= $totalSks ?> SKS</div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
