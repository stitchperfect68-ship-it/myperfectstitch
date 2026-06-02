<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Slider::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $sliders = [
            [
                'image_path'           => 'assets/slider/rk.jpg',
                'heading'              => 'Every Bag',
                'subheading'           => 'Tells a Story',
                'btn_text'             => 'Shop Bags',
                'btn_url'              => '#shop',
                'btn_secondary_text'   => 'Request a Quote',
                'sort_order'           => 1,
            ],
            [
                'image_path'           => 'assets/slider/rk2.jpg',
                'heading'              => 'Custom Furniture',
                'subheading'           => 'Built for You',
                'btn_text'             => 'View Furniture',
                'btn_url'              => '#corporate',
                'sort_order'           => 2,
            ],
            [
                'image_path'           => 'assets/slider/rk4.jpg',
                'heading'              => 'Interiors Built',
                'subheading'           => 'to Stand out',
                'btn_text'             => 'Interior Projects',
                'btn_url'              => '#corporate',
                'btn_secondary_text'   => 'Book Consultation',
                'sort_order'           => 3,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create(array_merge($slider, ['is_active' => true]));
        }
    }
}
