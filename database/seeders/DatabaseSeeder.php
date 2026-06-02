<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingsSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            GallerySeeder::class,
            PortfolioSeeder::class,
            EventSeeder::class,
            SliderSeeder::class,
            ClientSeeder::class,
        ]);
    }
}
