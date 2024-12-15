<?php

namespace App\Database\Seeds\Orders;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $clientIds = $this->db->table('clients')->select('client_id')->get()->getResultArray();
        $employeeIds = $this->db->table('employees')->select('employee_id')->get()->getResultArray();
        $productIds = $this->db->table('products')->select('product_id, product_price')->get()->getResultArray();
        $restaurantTables = $this->db->table('restaurant_tables')->select('table_id, table_name')->get()->getResultArray();

        $currentMonth = date('m');
        $currentYear = date('Y');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            for ($i = 0; $i < 20; $i++) {
                $orderSubtotal = 0;
                $orderDiscount = 0;
                $orderTax = 0;
                $orderTotal = 0;

                $orderData = [
                    'order_client_id' => $faker->randomElement($clientIds)['client_id'],
                    'order_employee_id' => $faker->randomElement($employeeIds)['employee_id'],
                    'order_type' => $faker->randomElement(['take-away', 'dine-in', 'delivery']),
                    'order_reference' => str_replace(' ', '_', $faker->randomElement($restaurantTables)['table_name']) . '-' . $faker->unique()->numberBetween(1000, 9999),
                    'order_date' => date('Y-m-d', strtotime("$currentYear-$currentMonth-$day")),
                    'order_time' => $faker->time(),
                    'order_note' => $faker->optional()->text(),
                    'order_subtotal' => $orderSubtotal,
                    'order_discount' => $orderDiscount,
                    'order_tax' => $orderTax,
                    'order_total' => $orderTotal,
                    'order_status' => $faker->randomElement(
                        array_merge(
                            array_fill(0, 10, 'on-going'),
                            array_fill(0, 8, 'completed'),
                            array_fill(0, 2, 'cancelled')
                        )
                    ),
                ];

                $this->db->table('orders')->insert($orderData);
                $orderId = $this->db->insertID();

                for ($j = 0; $j < $faker->numberBetween(1, 5); $j++) {
                    $product = $faker->randomElement($productIds);
                    $quantity = $faker->numberBetween(1, 10);
                    $orderItemTotal = $product['product_price'] * $quantity;
                    $orderSubtotal += $orderItemTotal;

                    $orderItemData = [
                        'order_id' => $orderId,
                        'order_item_product_id' => $product['product_id'],
                        'order_item_quantity' => $quantity,
                        'order_item_total' => $orderItemTotal,
                    ];

                    $this->db->table('order_items')->insert($orderItemData);
                }

                // Update order totals after inserting order items
                $orderTax = $orderSubtotal * 0.11;
                $orderTotal = $orderSubtotal + $orderTax;
                $orderDiscount = $faker->randomFloat(2, 0, (($orderTotal /2) * 0.5));

                $orderSubtotal = $orderSubtotal - $orderDiscount;
                $orderTax = $orderSubtotal * 0.11;
                $orderTotal = $orderSubtotal + $orderTax;

                $this->db->table('orders')->update([
                    'order_subtotal' => $orderSubtotal,
                    'order_discount' => $orderDiscount,
                    'order_tax' => $orderTax,
                    'order_total' => $orderTotal
                ], ['order_id' => $orderId]);
            }
        }
    }
}
