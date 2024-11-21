<?php

namespace App\Models\Clients;

use CodeIgniter\Model;


class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'client_id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Clients\ClientEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'client_first_name',
        'client_last_name',
        'client_phone_number',
        'client_email',
        'client_address',
        'client_is_active',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'client_created_at';
    protected $updatedField  = 'client_updated_at';
    protected $deletedField  = 'client_deleted_at';

    // Validation
    protected $validationRules      = [
        'client_first_name' => 'required|string|max_length[50]',
        'client_last_name' => 'required|string|max_length[50]',
        'client_phone_number' => 'required|string|max_length[15]|is_unique[clients.client_phone_number]',
        'client_email' => 'permit_empty|valid_email|max_length[100]',
        'client_address' => 'permit_empty|string',
        'client_is_active' => 'permit_empty|boolean',
    ];
    protected $validationMessages   = [
        'client_first_name' => [
            'required' => 'The first name field is required.',
            'string' => 'The first name field must contain only letters.',
            'max_length' => 'The first name field cannot exceed 50 characters in length.',
        ],
        'client_last_name' => [
            'required' => 'The last name field is required.',
            'string' => 'The last name field must contain only letters.',
            'max_length' => 'The last name field cannot exceed 50 characters in length.',
        ],
        'client_phone_number' => [
            'required' => 'The phone number field is required.',
            'string' => 'The phone number field must contain only letters.',
            'max_length' => 'The phone number field cannot exceed 15 characters in length.',
            'is_unique' => 'The phone number field must be unique.',
        ],
        'client_email' => [
            'valid_email' => 'The email field must contain a valid email address.',
            'max_length' => 'The email field cannot exceed 100 characters in length.',
        ],
        'client_address' => [
            'string' => 'The address field must contain only letters.',
        ],
        'client_is_active' => [
            'boolean' => 'The is active field must be a boolean value.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
