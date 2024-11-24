<?php

namespace App\Entities\Reservations;

use CodeIgniter\Entity\Entity;

class ReservationEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}
