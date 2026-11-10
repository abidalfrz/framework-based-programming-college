<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItemModel extends Model
{
    protected $table = 'cart_items';
    protected $allowedFields = ['cart_id', 'product_id', 'quantity', 'price_at_add'];
    protected $useTimestamps = true;
}
