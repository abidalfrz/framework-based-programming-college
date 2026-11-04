<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4 p-md-5 border-0 rounded-4 shadow-sm bg-white">
            <span class="badge bg-teal-subtle text-teal border px-3 py-2 rounded-pill mb-3 align-self-start">
                <i class="bi bi-headset me-1"></i> Layanan Bantuan
            </span>
            <h2 class="fw-extrabold text-dark mb-2">Kontak &amp; Aduan Sarpras</h2>
            <p class="text-muted mb-4">
                Sampaikan pertanyaan ketersediaan, permohonan survei, atau laporan kendala/kerusakan fasilitas kampus kepada petugas kami.
            </p>

            <form action="<?= site_url('contact') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= old('nama') ?>" required placeholder="Contoh: Muhammad Abid">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required placeholder="email@institusi.ac.id">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor WhatsApp / HP</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp') ?>" placeholder="08123456789">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fasilitas Terkait (Opsional)</label>
                        <select name="fasilitas_id" class="form-select">
                            <option value="">-- Pertanyaan Umum / Bukan Fasilitas Khusus --</option>
                            <?php foreach ($fasilitasOpt as $opt) : ?>
                                <option value="<?= $opt['id'] ?>" <?= (string) old('fasilitas_id', $preselectedId) === (string) $opt['id'] ? 'selected' : '' ?>>
                                    [<?= esc($opt['kode']) ?>] <?= esc($opt['nama']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Subjek Pesan <span class="text-danger">*</span></label>
                        <input type="text" name="subjek" class="form-control" value="<?= old('subjek') ?>" required placeholder="Contoh: Pertanyaan Ketersediaan Lab AI untuk Workshop">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Isi Pesan / Laporan Kendala <span class="text-danger">*</span></label>
                        <textarea name="isi" rows="5" class="form-control" required placeholder="Tuliskan pesan, waktu rencana pemakaian, atau rincian kendala fasilitas secara lengkap..."><?= old('isi') ?></textarea>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-portal btn-lg w-100">
                            <i class="bi bi-send me-1"></i> Kirimkan Pesan ke Petugas Sarpras
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

