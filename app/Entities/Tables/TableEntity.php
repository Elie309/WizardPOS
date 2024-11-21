<?php

namespace App\Entities\Tables;

use CodeIgniter\Entity\Entity;

class TableEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = [
        'table_created_at', 
        'table_updated_at', 
        'table_deleted_at'
    ];
    protected $casts   = [
        'table_id' => 'integer',
        'table_max_capacity' => 'integer',
        'table_is_active' => 'boolean',
    ];
}
