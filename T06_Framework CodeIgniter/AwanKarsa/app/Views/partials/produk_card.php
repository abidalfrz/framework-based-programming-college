<?php /** @var array $p */ ?>
<article class="produk-card h-100">
    <a href="<?= site_url('produk/' . $p['slug']) ?>" class="produk-link">
        <div class="produk-media">
            <?= foto_produk($p) ?>
            <?php if ((int) $p['stok'] === 0) : ?>
                <span class="stok-chip habis">Kapasitas penuh</span>
            <?php elseif ((int) $p['stok'] <= 5) : ?>
                <span class="stok-chip tipis">Sisa <?= (int) $p['stok'] ?></span>
            <?php endif ?>
        </div>
        <div class="p-3">
            <div class="produk-kategori"><?= esc($p['nama_kategori']) ?></div>
            <h3 class="produk-nama"><?= esc($p['nama']) ?></h3>
            <div class="produk-harga"><?= rupiah($p['harga']) ?> <small class="text-secondary">/ bulan</small></div>
        </div>
    </a>
</article>
