<?php

namespace App\Entities\Clients;

use CodeIgniter\Entity\Entity;

class ClientEntity extends Entity
{
    protected $datamap = [
        'client_id' => 'client_id',
        'first_name' => 'client_first_name',
        'last_name' => 'client_last_name',
        'phone_number' => 'client_phone_number',
        'email' => 'client_email',
        'address' => 'client_address',
        'is_active' => 'client_is_active',

    ];
    protected $dates   = ['client_created_at', 
    'client_updated_at', 
    'client_deleted_at'];
    protected $casts   = [
        'client_id' => 'int',
        'client_is_active' => 'boolean',
        'client_first_name' => 'string',
        'client_last_name' => 'string',
        'client_phone_number' => 'string',
        'client_email' => 'string',
        'client_address' => 'string',
    ];
}
