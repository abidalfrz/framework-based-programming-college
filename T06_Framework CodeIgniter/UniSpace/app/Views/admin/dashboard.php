<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<!-- Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Dashboard Sarana Prasarana</h1>
    <span class="text-muted small">Ringkasan status sarpras dan operasional fasilitas kampus</span>
</div>

<!-- 4 Summary Cards -->
<div class="row">
    <!-- Total Fasilitas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Fasilitas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($statistik['total_fasilitas']) ?> Unit</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cubes fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Tersedia -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Siap Digunakan (Tersedia)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($statistik['tersedia']) ?> Unit</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Pemeliharaan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Dalam Pemeliharaan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($statistik['pemeliharaan']) ?> Unit</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesan / Aduan Masuk -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pesan &amp; Laporan Kendala</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($totalPesan) ?> Pesan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-envelope fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Baris Chart & Ringkasan -->
<div class="row">
    <!-- Chart Distribusi Fasilitas per Kategori -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie mr-1"></i> Distribusi Fasilitas per Kategori</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar" style="height: 320px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Info Cepat -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle mr-1"></i> Ringkasan Kapasitas</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small font-weight-bold text-muted">Total Daya Tampung Ruang:</div>
                    <div class="h4 font-weight-bold text-gray-800"><?= number_format($statistik['total_kapasitas'], 0, ',', '.') ?> Orang</div>
                </div>
                <div class="mb-3">
                    <div class="small font-weight-bold text-muted">Total Kategori Rumpun:</div>
                    <div class="h4 font-weight-bold text-gray-800"><?= esc($statistik['total_kategori']) ?> Rumpun</div>
                </div>
                <div class="mb-3">
                    <div class="small font-weight-bold text-muted">Total Petugas Terdaftar:</div>
                    <div class="h4 font-weight-bold text-gray-800"><?= esc($totalUsers) ?> Akun</div>
                </div>
                <hr>
                <a href="<?= site_url('admin/fasilitas/new') ?>" class="btn btn-primary btn-block font-weight-bold py-2">
                    <i class="fas fa-plus mr-1"></i> Tambah Fasilitas Baru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Fasilitas Terbaru & Pesan Masuk -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Fasilitas Baru Ditambahkan</h6>
                <a href="<?= site_url('admin/fasilitas') ?>" class="small font-weight-bold">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light small">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Fasilitas</th>
                                <th>Kategori</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fasilitasBaru as $f) : ?>
                                <tr>
                                    <td class="font-weight-bold font-monospace small"><?= esc($f['kode']) ?></td>
                                    <td><a href="<?= site_url('admin/fasilitas/' . $f['id'] . '/edit') ?>"><?= esc($f['nama']) ?></a></td>
                                    <td><span class="badge badge-light border"><?= esc($f['nama_kategori']) ?></span></td>
                                    <td>
                                        <span class="badge badge-<?= $f['status'] === 'tersedia' ? 'success' : ($f['status'] === 'reservasi' ? 'warning' : 'danger') ?>">
                                            <?= esc(ucfirst($f['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Pesan &amp; Laporan Kendala Terbaru</h6>
                <a href="<?= site_url('admin/pesan') ?>" class="small font-weight-bold">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light small">
                            <tr>
                                <th>Pengirim</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pesanTerbaru)) : ?>
                                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada pesan masuk.</td></tr>
                            <?php else : ?>
                                <?php foreach ($pesanTerbaru as $p) : ?>
                                    <tr>
                                        <td class="font-weight-bold small"><?= esc($p['nama']) ?></td>
                                        <td class="small text-truncate" style="max-width: 180px;"><?= esc($p['subjek']) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $p['status'] === 'baru' ? 'danger' : ($p['status'] === 'dibaca' ? 'warning' : 'success') ?>">
                                                <?= esc(ucfirst($p['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="small text-muted"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/sb-admin-2/vendor/chart.js/Chart.min.js') ?>"></script>
<script>
    var ctx = document.getElementById("categoryChart");
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $chartLabels ?>,
            datasets: [{
                label: "Jumlah Fasilitas",
                backgroundColor: "#0d9488",
                hoverBackgroundColor: "#0f766e",
                borderColor: "#0d9488",
                data: <?= $chartData ?>,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            scales: {
                xAxes: [{ gridLines: { display: false, drawBorder: false }, maxBarThickness: 40 }],
                yAxes: [{
                    ticks: { min: 0, stepSize: 1 },
                    gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false }
                }],
            },
            legend: { display: false },
        }
    });
</script>
<?= $this->endSection() ?>

