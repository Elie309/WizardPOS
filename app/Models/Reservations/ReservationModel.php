<?php

namespace App\Models\Reservations;

use CodeIgniter\Model;


class ReservationModel extends Model
{
    protected $table            = 'reservations';
    protected $primaryKey       = 'reservation_id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Reservations\ReservationEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'reservation_client_id',
        'reservation_table_id',
        'reservation_employee_id',
        'reservation_date',
        'reservation_starting_time',
        'reservation_ending_time',
        'reservation_guests',
        'reservation_status',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'reservation_created_at';
    protected $updatedField  = 'reservation_updated_at';
    protected $deletedField  = 'reservation_deleted_at';

    // Validation
    protected $validationRules      = [
        'reservation_id' => 'permit_empty|integer',
        'reservation_client_id' => 'required|integer',
        'reservation_table_id' => 'required|integer',
        'reservation_employee_id' => 'required|integer',
        'reservation_date' => 'required|valid_date',
        'reservation_starting_time' => 'required|valid_date',
        'reservation_ending_time' => 'required|valid_date',
        'reservation_guests' => 'permit_empty|integer',
        'reservation_status' => 'required|in_list[pending,confirmed,cancelled,completed]',
    ];
    protected $validationMessages   = [
        'reservation_id' => [
            'integer' => 'Reservation ID should be an integer',
        ],
        'reservation_client_id' => [
            'required' => 'Client is required',
            'integer' => 'Client ID should be an integer',
        ],
        'reservation_table_id' => [
            'required' => 'Table is required',
            'integer' => 'Table ID should be an integer',
        ],
        'reservation_employee_id' => [
            'required' => 'Employee is required',
            'integer' => 'Employee ID should be an integer',
        ],
        'reservation_date' => [
            'required' => 'Reservation date is required',
            'valid_date' => 'Reservation date should be a valid date',
        ],
        'reservation_starting_time' => [
            'required' => 'Reservation starting time is required',
            'valid_date' => 'Reservation starting time should be a valid time',
        ],
        'reservation_ending_time' => [
            'required' => 'Reservation ending time is required',
            'valid_date' => 'Reservation ending time should be a valid time',
        ],
        'reservation_guests' => [
            'integer' => 'Number of guests should be an integer',
        ],
        'reservation_status' => [
            'required' => 'Reservation status is required',
            'in_list' => 'Reservation status should be one of: pending, confirmed, cancelled, completed',
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
