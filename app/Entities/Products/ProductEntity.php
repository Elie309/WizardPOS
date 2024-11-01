<?php

namespace App\Entities\Products;

use CodeIgniter\Entity\Entity;

class ProductEntity extends Entity
{
    protected $datamap = [ ];
    protected $dates   = ['product_created_at', 
    'product_updated_at', 
    'product_deleted_at'
];
    protected $casts   = [
        'product_id' => 'int',
        'product_category_id' => 'int',
        'product_price' => 'float',
        'product_is_active' => 'boolean',
        'product_sku' => 'string',
        'product_slug' => 'string',
        'product_name' => 'string',
        'product_description' => 'string',
        'product_image' => 'string',
        'product_show_in_menu' => 'boolean',
    ];
}
