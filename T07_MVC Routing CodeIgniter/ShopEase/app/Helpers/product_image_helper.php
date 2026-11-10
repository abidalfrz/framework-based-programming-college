<?php

/** Resolve bundled sample images separately from administrator uploads. */
function product_image_url(?string $image): ?string
{
    if ($image === null || $image === '') {
        return null;
    }
    $name = basename($image);
    $folder = str_starts_with($name, 'sample-') && is_file(FCPATH . 'assets/products/' . $name)
        ? 'assets/products/' : 'uploads/products/';
    return base_url($folder . rawurlencode($name));
}
