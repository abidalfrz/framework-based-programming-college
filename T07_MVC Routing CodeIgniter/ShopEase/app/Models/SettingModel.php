<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $allowedFields = ['shipping_cost', 'payment_instructions', 'checkout_ready'];
}
