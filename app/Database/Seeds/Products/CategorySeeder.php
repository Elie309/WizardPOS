<?php

namespace App\Database\Seeds\Products;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {

        $food_categories = [
            [
                'category_name' => 'Appetizers',
                'category_description' => 'Start your meal with delicious small bites and appetizers.',
                'category_image' => 'https://picsum.photos/seed/appetizers/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,
            ],
            [
                'category_name' => 'Salads',
                'category_description' => 'Fresh and healthy salads with seasonal ingredients.',
                'category_image' => 'https://picsum.photos/seed/salads/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,

            ],
            [
                'category_name' => 'Soups',
                'category_description' => 'Warm and comforting soups for every occasion.',
                'category_image' => 'https://picsum.photos/seed/soups/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,

            ],
            [
                'category_name' => 'Main Courses',
                'category_description' => 'Hearty and satisfying main dishes to enjoy.',
                'category_image' => 'https://picsum.photos/seed/maincourses/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,

            ],
            [
                'category_name' => 'Desserts',
                'category_description' => 'Sweet treats to end your meal on a high note.',
                'category_image' => 'https://picsum.photos/seed/desserts/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,

            ],
            [
                'category_name' => 'Beverages',
                'category_description' => 'A variety of drinks to complement your meal.',
                'category_image' => 'https://picsum.photos/seed/beverages/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,

            ],
            [
                'category_name' => 'Seafood',
                'category_description' => 'Fresh seafood delicacies prepared to perfection.',
                'category_image' => 'https://picsum.photos/seed/seafood/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,

            ],
            [
                'category_name' => 'Vegan',
                'category_description' => 'Tasty plant-based meals for a healthy lifestyle.',
                'category_image' => 'https://picsum.photos/seed/vegan/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,

            ],
            [
                'category_name' => 'Grill',
                'category_description' => 'Flame-grilled meats and vegetables with bold flavors.',
                'category_image' => 'https://picsum.photos/seed/grill/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,

            ],
            [
                'category_name' => 'Pizza',
                'category_description' => 'Delicious pizzas with a variety of toppings.',
                'category_image' => 'https://picsum.photos/seed/pizza/640/480',
                'category_is_active' => 1,
                'category_show_in_menu' => 1,
            ],
        ];

        $this->db->table('categories')->insertBatch($food_categories);
    }
}
