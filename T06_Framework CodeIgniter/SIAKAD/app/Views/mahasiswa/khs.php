<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-end justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Kartu Hasil Studi</h1>
        <span class="text-gray-600"><?= esc($mhs['nim'] . ' · ' . $mhs['nama']) ?></span>
    </div>
    <?php if ($perPeriode !== []) : ?>
        <form method="get" class="form-inline mt-2 mt-sm-0">
            <label for="periode" class="mr-2 font-weight-bold">Periode</label>
            <select id="periode" name="periode" class="form-control" onchange="this.form.submit()">
                <?php foreach ($perPeriode as $i => $p) : ?>
                    <option value="<?= esc($kunci[$i], 'attr') ?>" <?= $aktif !== null && $p['label'] === $aktif['label'] ? 'selected' : '' ?>><?= esc($p['label']) ?></option>
                <?php endforeach ?>
            </select>
            <noscript><button class="btn btn-primary ml-2">Tampilkan</button></noscript>
        </form>
    <?php endif ?>
</div>

<?php if ($aktif === null) : ?>
    <div class="alert alert-info">Belum ada riwayat studi.</div>
<?php else : ?>
    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3"><h2 class="h6 m-0 font-weight-bold text-primary"><?= esc($aktif['label']) ?></h2></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th class="pl-4">Kode</th><th>Mata kuliah</th><th class="text-center">SKS</th><th class="text-right">Nilai</th><th class="text-center">Huruf</th><th class="text-right pr-4">SKS × bobot</th></tr></thead>
                        <tbody>
                        <?php foreach ($aktif['baris'] as $b) : ?>
                            <tr>
                                <td class="pl-4 font-weight-bold"><?= esc($b['kode']) ?></td>
                                <td><?= esc($b['nama_mk']) ?></td>
                                <td class="text-center tabular"><?= $b['sks'] ?></td>
                                <td class="text-right tabular"><?= $b['nilai_angka'] === null ? '–' : esc($b['nilai_angka']) ?></td>
                                <td class="text-center"><?= badge_nilai($b['nilai_huruf']) ?></td>
                                <td class="text-right pr-4 tabular"><?= $b['nilai_huruf'] === null ? '–' : number_format($b['sks'] * $penilaian->bobot($b['nilai_huruf']), 1) ?></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-4">
            <div class="ipk-hero shadow p-4 mb-4">
                <div class="label">IP semester</div>
                <div class="angka my-2"><?= $aktif['dinilai'] ? format_ip($aktif['ip']) : '–' ?></div>
                <div class="small"><?= $aktif['sks'] ?> SKS diambil</div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">IPK sampai periode ini</div>
                    <div class="h3 font-weight-bold text-gray-800 mb-0 tabular"><?= $aktif['dinilai'] ? format_ip($aktif['ipk']) : '–' ?></div>
                    <?php if (! $aktif['dinilai']) : ?><small class="text-gray-600">Nilai semester ini belum dimasukkan dosen.</small><?php endif ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<?= $this->endSection() ?>
