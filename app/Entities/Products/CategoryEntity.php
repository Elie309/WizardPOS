<?php

namespace App\Entities\Products;

use CodeIgniter\Entity\Entity;

class CategoryEntity extends Entity
{
    protected $datamap = [
        'category_id' => 'category_id',
        'name' => 'category_name',
        'description' => 'category_description',
        'image' => 'category_image',
        'is_active' => 'category_is_active',
    ];
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
