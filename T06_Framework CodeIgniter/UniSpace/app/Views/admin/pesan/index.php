<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Kotak Masuk Pesan &amp; Aduan Fasilitas</h1>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-items-center mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 140px;">Status</th>
                        <th>Pengirim &amp; Kontak</th>
                        <th>Fasilitas Terkait</th>
                        <th>Subjek &amp; Pesan</th>
                        <th>Tanggal Masuk</th>
                        <th class="text-right" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pesanList)) : ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesan atau aduan masuk.</td></tr>
                    <?php else : ?>
                        <?php foreach ($pesanList as $p) : ?>
                            <tr>
                                <td>
                                    <form action="<?= site_url('admin/pesan/' . $p['id'] . '/status') ?>" method="post" class="m-0">
                                        <?= csrf_field() ?>
                                        <select name="status" class="form-control form-control-sm font-weight-bold text-uppercase 
                                            <?= $p['status'] === 'baru' ? 'border-danger text-danger' : ($p['status'] === 'dibaca' ? 'border-warning text-warning' : 'border-success text-success') ?>"
                                            onchange="this.form.submit()">
                                            <option value="baru" <?= $p['status'] === 'baru' ? 'selected' : '' ?>>Baru</option>
                                            <option value="dibaca" <?= $p['status'] === 'dibaca' ? 'selected' : '' ?>>Dibaca</option>
                                            <option value="selesai" <?= $p['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark"><?= esc($p['nama']) ?></div>
                                    <small class="text-muted"><i class="fas fa-envelope mr-1"></i><?= esc($p['email']) ?></small>
                                    <?php if (! empty($p['no_hp'])) : ?>
                                        <br><small class="text-muted"><i class="fab fa-whatsapp mr-1"></i><?= esc($p['no_hp']) ?></small>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php if (! empty($p['nama_fasilitas'])) : ?>
                                        <span class="badge badge-light border text-dark font-weight-bold"><?= esc($p['nama_fasilitas']) ?></span><br>
                                        <small class="text-muted font-monospace"><?= esc($p['kode_fasilitas']) ?></small>
                                    <?php else : ?>
                                        <span class="text-muted small">Pertanyaan Umum</span>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark mb-1"><?= esc($p['subjek']) ?></div>
                                    <div class="small text-secondary bg-light p-2 rounded" style="max-width: 380px; white-space: pre-wrap;"><?= esc($p['isi']) ?></div>
                                </td>
                                <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></small></td>
                                <td class="text-right">
                                    <?php if (session('role') === 'admin') : ?>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#modalHapus"
                                                data-url="<?= site_url('admin/pesan/' . $p['id']) ?>"
                                                data-nama="Pesan dari <?= esc($p['nama']) ?> (<?= esc($p['subjek']) ?>)" title="Hapus pesan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

