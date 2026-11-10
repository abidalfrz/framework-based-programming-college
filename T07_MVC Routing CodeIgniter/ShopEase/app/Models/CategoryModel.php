<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $allowedFields = ['name', 'slug', 'description', 'is_active'];
    protected $useTimestamps = true;
}
