<?= $this->extend('layouts/cetak') ?>

<?= $this->section('content') ?>
<h1>TRANSKRIP AKADEMIK SEMENTARA</h1>
<table class="identitas">
    <tr><td>Nama</td><td>: <strong><?= esc($mhs['nama']) ?></strong></td></tr>
    <tr><td>NIM</td><td>: <?= esc($mhs['nim']) ?></td></tr>
    <tr><td>Angkatan</td><td>: <?= esc($mhs['angkatan']) ?></td></tr>
</table>

<?php foreach ($perSemester as $smt => $baris) : ?>
    <h2>Semester <?= $smt ?></h2>
    <table class="data">
        <thead><tr><th>Kode</th><th>Mata kuliah</th><th class="c">SKS</th><th class="c">Nilai</th><th class="c">Bobot</th><th class="c">Mutu</th></tr></thead>
        <tbody>
        <?php foreach ($baris as $b) : ?>
            <?php $bobot = $penilaian->bobot($b['nilai_huruf']); ?>
            <tr><td><?= esc($b['kode']) ?></td><td><?= esc($b['nama_mk']) ?></td><td class="c"><?= $b['sks'] ?></td><td class="c"><?= esc($b['nilai_huruf']) ?></td><td class="c"><?= number_format($bobot, 1) ?></td><td class="c"><?= number_format($b['sks'] * $bobot, 1) ?></td></tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endforeach ?>

<table class="data">
    <tfoot>
        <tr><td>Total SKS</td><td class="c"><?= $totalSks ?></td><td>Indeks Prestasi Kumulatif</td><td class="c"><?= format_ip($ipk) ?></td></tr>
    </tfoot>
</table>

<div class="ttd">
    <div></div>
    <div>Surabaya, <?= date('d-m-Y') ?><br>Ketua Program Studi<div class="garis">Dr. Budi Santoso, S.Kom., M.Kom.</div></div>
</div>
<?= $this->endSection() ?>
