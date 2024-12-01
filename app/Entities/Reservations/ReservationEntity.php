<?php

namespace App\Entities\Reservations;

use CodeIgniter\Entity\Entity;

class ReservationEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['reservation_created_at', 
    'reservation_updated_at', 
    'reservation_deleted_at'];
    protected $casts   = [];

    //Reservation date 
    public function setReservationDate(string $date): ReservationEntity
    {
        $this->attributes['reservation_date'] = date('Y-m-d', strtotime($date));
        return $this;
    }

}
