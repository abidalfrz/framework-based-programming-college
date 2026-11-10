<?php

namespace App\Models;

use CodeIgniter\Model;

class ShipmentModel extends Model
{
    protected $table = 'shipments';
    protected $allowedFields = ['order_id', 'courier', 'tracking_number', 'shipped_at', 'delivered_at'];
}
