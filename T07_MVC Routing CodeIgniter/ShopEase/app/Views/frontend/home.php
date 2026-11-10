<?= $this->extend('layouts/main') ?><?= $this->section('content') ?>
<section class="hero wrap">
    <div class="hero-copy"><p class="eyebrow">A little easier, every day</p><h1>Good things for <em>your everyday.</em></h1><p>Browse familiar favorites and find something for work, play, and everything in between.</p><a class="button button-dark" href="<?= site_url('catalog') ?>">Shop all products</a><p class="hero-note">Explore electronics, fashion, and everyday accessories.</p></div>
    <?php $featured = array_values(array_filter($products, static fn ($product) => ! empty($product['image']))); ?>
    <?php if ($featured): ?>
    <div class="featured-carousel" role="region" aria-label="Featured products" aria-roledescription="carousel">
        <div class="featured-viewport"><div class="featured-track">
            <?php foreach ($featured as $index => $product): ?>
            <div class="featured-slide" role="group" aria-roledescription="slide" aria-label="<?= $index + 1 ?> of <?= count($featured) ?>">
                <a class="hero-image" href="<?= site_url('product/' . rawurlencode($product['slug'])) ?>">
                    <img src="<?= esc(product_image_url($product['image'])) ?>" width="1200" height="900" <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?> alt="<?= esc($product['name']) ?>">
                    <span class="hero-caption"><span>Featured product</span><strong><?= esc($product['name']) ?></strong></span>
                </a>
            </div>
            <?php endforeach ?>
        </div></div>
        <?php if (count($featured) > 1): ?>
        <button class="featured-arrow featured-arrow-prev" type="button" aria-label="Previous product" hidden>&#8249;</button>
        <button class="featured-arrow featured-arrow-next" type="button" aria-label="Next product" hidden>&#8250;</button>
        <?php endif ?>
    </div>
    <?php else: ?><div class="hero-image"><div class="image-fallback">Products will appear here when they are available.</div></div><?php endif ?>
</section>
<section class="category-section" id="kategori"><div class="wrap"><div class="section-head"><div><p class="eyebrow">Find your kind of everyday</p><h2>Shop by category</h2></div><p>Start with what you need.</p></div><div class="categories"><?php foreach ($categories as $category): ?><a class="category" href="<?= site_url('catalog') ?>?category=<?= (int) $category['id'] ?>"><strong><?= esc($category['name']) ?></strong><span>Browse <?= esc(strtolower($category['name'])) ?> <span aria-hidden="true">→</span></span></a><?php endforeach ?></div></div></section>
<section class="products-section wrap" id="produk"><div class="section-head"><div><p class="eyebrow">The collection</p><h2>Made for your day</h2></div><a class="text-link" href="<?= site_url('catalog') ?>">View all products</a></div>
<?php if (! $products): ?><p class="empty">Products will appear here when they are available.</p><?php else: ?><div class="product-grid"><?php foreach ($products as $product): ?><article class="product-card"><a href="<?= site_url('product/' . rawurlencode($product['slug'])) ?>"><div class="product-image"><?php if ($image = product_image_url($product['image'])): ?><img src="<?= esc($image) ?>" alt="<?= esc($product['name']) ?>" width="800" height="800" loading="lazy"><?php else: ?><div class="image-fallback">No product image available</div><?php endif ?></div><h3><?= esc($product['name']) ?></h3></a><p><?= format_rupiah((string) $product['price']) ?></p></article><?php endforeach ?></div><?php endif ?></section>
<?= $this->endSection() ?>
