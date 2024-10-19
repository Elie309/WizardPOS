<?php

namespace App\Entities\Products;

use CodeIgniter\Entity\Entity;

class ProductEntity extends Entity
{
    protected $datamap = [
        'product_id' => 'product_id',
        'sku' => 'product_sku',
        'slug' => 'product_slug',
        'name' => 'product_name',
        'description' => 'product_description',
        'price' => 'product_price',
        'category_id' => 'product_category_id',
        'production_date' => 'product_production_date',
        'expiry_date' => 'product_expiry_date',
        'image' => 'product_image',
        'is_active' => 'product_is_active',
        'created_at' => 'product_created_at',
        'updated_at' => 'product_updated_at',
        'deleted_at' => 'product_deleted_at',
    ];
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
        'product_production_date' => 'date',
        'product_expiry_date' => 'date',

    ];
}
