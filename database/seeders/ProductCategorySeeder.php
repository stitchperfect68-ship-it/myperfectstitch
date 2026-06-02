<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $bags = ProductCategory::firstOrCreate(
            ['slug' => 'bags'],
            ['name' => 'Bags', 'is_active' => true, 'sort_order' => 1]
        );

        $furniture = ProductCategory::firstOrCreate(
            ['slug' => 'furniture'],
            ['name' => 'Furniture', 'is_active' => true, 'sort_order' => 2]
        );

        $bagSubs = [
            ['name' => 'Backpacks',     'slug' => 'backpacks',     'sort_order' => 1],
            ['name' => 'Coin Purses',   'slug' => 'coin-purses',   'sort_order' => 2],
            ['name' => 'Laptop Bags',   'slug' => 'laptop-bags',   'sort_order' => 3],
            ['name' => 'Laptop Sleeves','slug' => 'laptop-sleeves','sort_order' => 4],
            ['name' => 'Pencil Cases',  'slug' => 'pencil-cases',  'sort_order' => 5],
            ['name' => 'Tote Bags',     'slug' => 'tote-bags',     'sort_order' => 6],
            ['name' => 'Travel Bags',   'slug' => 'travel-bags',   'sort_order' => 7],
        ];

        foreach ($bagSubs as $sub) {
            ProductCategory::firstOrCreate(
                ['slug' => $sub['slug']],
                array_merge($sub, ['parent_id' => $bags->id, 'is_active' => true])
            );
        }

        $furnitureSubs = [
            ['name' => 'Arm Chairs',   'slug' => 'arm-chairs',    'sort_order' => 1],
            ['name' => 'Benches',      'slug' => 'benches',       'sort_order' => 2],
            ['name' => 'Love Seats',   'slug' => 'love-seats',    'sort_order' => 3],
            ['name' => 'Modular Sofas','slug' => 'modular-sofas', 'sort_order' => 4],
            ['name' => 'Sofas',        'slug' => 'sofas',         'sort_order' => 5],
            ['name' => 'Stools',       'slug' => 'stools',        'sort_order' => 6],
        ];

        foreach ($furnitureSubs as $sub) {
            ProductCategory::firstOrCreate(
                ['slug' => $sub['slug']],
                array_merge($sub, ['parent_id' => $furniture->id, 'is_active' => true])
            );
        }
    }
}
