<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
$persen = min(100, (int) round($sksIni / max(1, $batasSks) * 100));
$sisa   = $batasSks - $sksIni;
?>
<div class="d-sm-flex align-items-end justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Kartu Rencana Studi</h1>
        <span class="text-gray-600"><?= esc(label_periode($akademik->tahunAjaran, $akademik->semester)) ?> &middot; masa KRS <?= esc($akademik->krsMulai) ?> s.d. <?= esc($akademik->krsSelesai) ?></span>
    </div>
    <a href="<?= site_url('mahasiswa/krs/cetak') ?>" class="btn btn-outline-primary mt-2 mt-sm-0 <?= $krs === [] ? 'disabled' : '' ?>" target="_blank" rel="noopener"><i class="fas fa-print mr-1"></i> Cetak KRS</a>
</div>

<?php if (! $terbuka) : ?>
    <div class="alert alert-warning">Masa pengisian KRS sudah ditutup. KRS hanya dapat dilihat dan dicetak.</div>
<?php endif ?>

<div class="row">
    <div class="col-xl-5 mb-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span class="font-weight-bold text-gray-800">SKS diambil</span>
                    <span class="tabular font-weight-bold"><?= $sksIni ?> / <?= $batasSks ?></span>
                </div>
                <div class="meter my-2 <?= $persen >= 100 ? 'penuh' : '' ?>" role="img" aria-label="<?= $sksIni ?> dari <?= $batasSks ?> SKS"><span style="width:<?= $persen ?>%"></span></div>
                <small class="text-gray-600">
                    <?= $ipLalu === null ? 'Mahasiswa baru: batas ' . $batasSks . ' SKS.' : 'IP semester lalu ' . format_ip($ipLalu) . ' → batas ' . $batasSks . ' SKS.' ?>
                    Sisa <?= max(0, $sisa) ?> SKS.
                </small>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header py-3"><h2 class="h6 m-0 font-weight-bold text-primary">KRS saya (<?= count($krs) ?> mata kuliah)</h2></div>
            <ul class="list-group list-group-flush">
                <?php foreach ($krs as $k) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="font-weight-bold"><?= esc($k['kode']) ?></span> <?= esc($k['nama_mk']) ?>
                            <div class="small text-gray-600"><?= $k['sks'] ?> SKS &middot; <?= esc($k['nama_dosen']) ?></div>
                        </div>
                        <?php if ($k['nilai_huruf'] !== null) : ?>
                            <?= badge_nilai($k['nilai_huruf']) ?>
                        <?php elseif ($terbuka) : ?>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalHapus"
                                    data-action="<?= site_url('mahasiswa/krs/' . $k['id']) ?>" data-tombol="Ya, batalkan"
                                    data-pesan="Batalkan <?= esc($k['kode'] . ' ' . $k['nama_mk'], 'attr') ?> dari KRS?">Batal</button>
                        <?php endif ?>
                    </li>
                <?php endforeach ?>
                <?php if ($krs === []) : ?>
                    <li class="list-group-item text-center text-gray-600 py-4">Belum ada mata kuliah. Pilih dari daftar penawaran.</li>
                <?php endif ?>
            </ul>
        </div>
    </div>

    <div class="col-xl-7 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h2 class="h6 m-0 font-weight-bold text-primary">Mata kuliah ditawarkan</h2></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th class="pl-4">Mata kuliah</th><th class="text-center">SKS</th><th class="text-center">Kuota</th><th class="text-right pr-4">Status</th></tr></thead>
                    <tbody>
                    <?php foreach ($ditawarkan as $mk) : ?>
                        <?php
                        $id    = (int) $mk['id'];
                        $penuh = (int) $mk['peserta'] >= (int) $mk['kuota'];
                        ?>
                        <tr>
                            <td class="pl-4"><span class="font-weight-bold"><?= esc($mk['kode']) ?></span> <?= esc($mk['nama']) ?><div class="small text-gray-600">Smt <?= $mk['semester'] ?> &middot; <?= esc($mk['nama_dosen']) ?></div></td>
                            <td class="text-center tabular"><?= $mk['sks'] ?></td>
                            <td class="text-center tabular <?= $penuh ? 'text-danger font-weight-bold' : '' ?>"><?= $mk['peserta'] ?>/<?= $mk['kuota'] ?></td>
                            <td class="text-right pr-4 text-nowrap">
                                <?php if (in_array($id, $diambil, true)) : ?>
                                    <span class="badge badge-primary p-2"><i class="fas fa-check mr-1"></i>Diambil</span>
                                <?php elseif (isset($lulus[$id])) : ?>
                                    <span class="small text-gray-600">Lulus <?= badge_nilai($lulus[$id]) ?></span>
                                <?php elseif ($terbuka) : ?>
                                    <!-- Tombol tetap aktif agar aturan diperiksa server (kuota, batas SKS) dan pesannya tampil -->
                                    <form action="<?= site_url('mahasiswa/krs') ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="matakuliah_id" value="<?= $id ?>">
                                        <button type="submit" class="btn btn-sm <?= $penuh ? 'btn-outline-secondary' : 'btn-primary' ?>"><i class="fas fa-plus mr-1"></i>Ambil</button>
                                    </form>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
