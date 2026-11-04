<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php use App\Libraries\OrderStatus; ?>
<div class="row">
    <?php foreach ([
        ['Omzet (dibayar)', rupiah($omzetTotal), 'fa-coins', 'primary', null],
        ['Perlu tindakan', $perluAksi, 'fa-bell', 'warning', 'admin/pesanan?status=menunggu_verifikasi'],
        ['Belum dibayar', $perStatus[OrderStatus::MENUNGGU_PEMBAYARAN] ?? 0, 'fa-wallet', 'info', 'admin/pesanan?status=menunggu_pembayaran'],
        ['Selesai', $perStatus[OrderStatus::SELESAI] ?? 0, 'fa-check-circle', 'success', 'admin/pesanan?status=selesai'],
    ] as [$label, $nilai, $ikon, $warna, $url]) : ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-<?= $warna ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $warna ?> text-uppercase mb-1"><?= $label ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 tabular"><?= $nilai ?></div>
                        </div>
                        <div class="col-auto"><i class="fas <?= $ikon ?> fa-2x text-gray-300"></i></div>
                    </div>
                    <?php if ($url) : ?><a href="<?= site_url($url) ?>" class="stretched-link" aria-label="Lihat <?= $label ?>"></a><?php endif ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<div class="row">
    <div class="col-xl-8 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3"><h2 class="h6 m-0 font-weight-bold text-primary">Omzet 7 hari terakhir</h2></div>
            <div class="card-body"><canvas id="grafikOmzet" height="120" role="img" aria-label="Grafik omzet harian"></canvas></div>
        </div>
    </div>
    <div class="col-xl-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3"><h2 class="h6 m-0 font-weight-bold text-primary">Stok menipis (≤ 5)</h2></div>
            <ul class="list-group list-group-flush">
                <?php foreach ($menipis as $p) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?= site_url('admin/produk/' . $p['id'] . '/edit') ?>"><?= esc($p['nama']) ?></a>
                        <span class="badge <?= (int) $p['stok'] === 0 ? 'badge-danger' : 'badge-warning' ?> p-2"><?= (int) $p['stok'] === 0 ? 'Habis' : $p['stok'] . ' unit' ?></span>
                    </li>
                <?php endforeach ?>
                <?php if ($menipis === []) : ?><li class="list-group-item text-gray-600">Kapasitas tersedia.</li><?php endif ?>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between"><h2 class="h6 m-0 font-weight-bold text-primary">Pesanan terbaru</h2><a href="<?= site_url('admin/pesanan') ?>" class="small">Semua pesanan</a></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th class="pl-4">Kode</th><th>Pembeli</th><th>Status</th><th class="text-right pr-4">Total</th></tr></thead>
                    <tbody>
                    <?php foreach ($terbaru as $o) : ?>
                        <tr>
                            <td class="pl-4"><a href="<?= site_url('admin/pesanan/' . $o['kode']) ?>" class="font-weight-bold"><?= esc($o['kode']) ?></a><div class="small text-gray-600"><?= date('d M H:i', strtotime($o['created_at'])) ?></div></td>
                            <td><?= esc($o['nama_pembeli']) ?></td>
                            <td><?= badge_status($o['status']) ?></td>
                            <td class="text-right pr-4 tabular"><?= rupiah($o['total']) ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3"><h2 class="h6 m-0 font-weight-bold text-primary">Produk terlaris</h2></div>
            <ol class="list-group list-group-flush">
                <?php foreach ($terlaris as $i => $p) : ?>
                    <li class="list-group-item d-flex justify-content-between"><span><span class="text-gray-500 mr-2"><?= $i + 1 ?>.</span><?= esc($p['nama']) ?></span><span class="tabular font-weight-bold"><?= (int) $p['terjual'] ?></span></li>
                <?php endforeach ?>
            </ol>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/sb-admin-2/vendor/chart.js/Chart.min.js') ?>"></script>
<script>
    new Chart(document.getElementById('grafikOmzet'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_map(static fn (string $t): string => date('d/m', strtotime($t)), array_keys($omzet))) ?>,
            datasets: [{ label: 'Omzet', data: <?= json_encode(array_values($omzet)) ?>, backgroundColor: '#0f766e', hoverBackgroundColor: '#115e59' }]
        },
        options: {
            legend: { display: false },
            animation: { duration: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 600 },
            tooltips: { callbacks: { label: function (t) { return 'Rp' + Number(t.yLabel).toLocaleString('id-ID'); } } },
            scales: { yAxes: [{ ticks: { beginAtZero: true, callback: function (v) { return 'Rp' + (v / 1000) + 'rb'; } } }] }
        }
    });
</script>
<?= $this->endSection() ?>
