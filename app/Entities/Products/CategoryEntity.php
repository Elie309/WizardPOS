<?php

namespace App\Entities\Products;

use CodeIgniter\Entity\Entity;

class CategoryEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['category_created_at', 
    'category_updated_at', 'category_deleted_at'];
    protected $casts   = [
        'category_id' => 'int',
        'category_is_active' => 'boolean',
        'category_name' => 'string',
        'category_description' => 'string',
        'category_image' => 'string',
    ];
}
