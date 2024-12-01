<?php

namespace App\Database\Seeds\Reservations;

use CodeIgniter\Database\Seeder;


class ReservationSeeder extends Seeder
{
    public function run()
    {
        // Use faker
        $faker = \Faker\Factory::create();

        // Load the database
        $db = \Config\Database::connect();

        // Get data from clients table
        $clients = $db->table('clients')->select('client_id')->get()->getResultArray();

        // Get data from restaurant_tables table
        $tables = $db->table('restaurant_tables')->select('table_id')->get()->getResultArray();

        // Get data from employees table
        $employees = $db->table('employees')->select('employee_id')->get()->getResultArray();

        // Insert fake reservations for the current day
        foreach ($tables as $table) {
            $reservationsCount = $faker->numberBetween(1, 4);
            for ($i = 0; $i < $reservationsCount; $i++) {

                //faker time
                $startingTime = $faker->time();
                //Add 1 to 3 hours randomly
                $endingTime = date('H:i:s', strtotime($startingTime . ' + ' . $faker->numberBetween(1, 3) . ' hours'));

                $db->table('reservations')->insert([
                    'reservation_client_id' => $faker->randomElement($clients)['client_id'],
                    'reservation_table_id' => $table['table_id'],
                    'reservation_employee_id' => $faker->randomElement($employees)['employee_id'],
                    'reservation_date' => date('Y-m-d'),
                    'reservation_starting_time' => $startingTime,
                    'reservation_ending_time' => $endingTime,
                    'reservation_guests' => $faker->optional()->numberBetween(1, 10),
                    'reservation_status' => $faker->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
                    'reservation_created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                    'reservation_updated_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}