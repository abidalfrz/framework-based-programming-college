<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $allowedFields = ['user_id', 'order_number', 'checkout_token', 'recipient_name', 'phone', 'address', 'city', 'province', 'postal_code', 'subtotal', 'shipping_cost', 'total', 'order_status'];
    protected $useTimestamps = true;
}
