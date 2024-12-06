<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $this->call('App\Database\Seeds\Settings\EmployeeSeeder');
        $this->call('App\Database\Seeds\Products\CategorySeeder');
        $this->call('App\Database\Seeds\Products\ProductSeeder');
        $this->call('App\Database\Seeds\Clients\ClientSeeder');
        $this->call('App\Database\Seeds\Tables\TableSeeder');
        $this->call('App\Database\Seeds\Reservations\ReservationSeeder');
        $this->call('App\Database\Seeds\Orders\OrderSeeder');
    }
}
