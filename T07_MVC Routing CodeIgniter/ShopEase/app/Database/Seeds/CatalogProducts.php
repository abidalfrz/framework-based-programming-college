<?php

namespace App\Database\Seeds;

final class CatalogProducts
{
    /** Product prices and stock in the seed are illustrative, not real offers. */
    public static function all(): array
    {
        return [
            'Electronics' => [
                ['Bose QuietComfort 35 II', 'sample-bose-qc35ii.jpg'],
                ['JBL Flip 5', 'sample-jbl-flip-5.jpg'],
                ['Nintendo Switch OLED', 'sample-switch-oled.png'],
                ['Apple iPhone 13', 'sample-iphone-13.jpg'],
                ['Amazon Kindle Paperwhite', 'sample-kindle.jpg'],
            ],
            'Fashion' => [
                ['Apple Watch Series 7', 'sample-apple-watch-7.jpg'],
                ['Casio F-91W', 'sample-casio.jpg'],
                ['adidas Stan Smith', 'sample-adidas.jpg'],
                ['Converse Chuck Taylor All Star', 'sample-converse.png'],
                ['Nike Air Force 1', 'sample-nike.jpg'],
            ],
            'Accessories' => [
                ['Apple AirPods Pro', 'sample-airpods-pro.jpg'],
                ['Logitech MX Master 3S', 'sample-mx-master-3s.jpg'],
                ['Apple Pencil (2nd generation)', 'sample-pencil.jpg'],
                ['Logitech K380', 'sample-k380.jpg'],
                ['Anker PowerCore 10000', 'sample-anker.jpg'],
            ],
        ];
    }
}
