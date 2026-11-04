<?php /** @var array{subtotal:int, ongkir:int, total:int, kurang_gratis:int} $ringkasan */ ?>
<div class="surface p-4 ringkasan">
    <h2 class="h5 fw-bold mb-3">Ringkasan sewa</h2>
    <dl class="mb-0">
        <div class="d-flex justify-content-between"><dt class="fw-normal text-secondary">Subtotal</dt><dd class="mb-2"><?= rupiah($ringkasan['subtotal']) ?></dd></div>
        <div class="d-flex justify-content-between"><dt class="fw-normal text-secondary">Biaya layanan</dt><dd class="mb-2"><?= $ringkasan['ongkir'] === 0 ? '<span class="text-success fw-bold">Gratis</span>' : rupiah($ringkasan['ongkir']) ?></dd></div>
        <div class="d-flex justify-content-between border-top pt-3 mt-1"><dt class="fw-bold">Total</dt><dd class="mb-0 h4 fw-bold text-toko"><?= rupiah($ringkasan['total']) ?></dd></div>
    </dl>
</div>
