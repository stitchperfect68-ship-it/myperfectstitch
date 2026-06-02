<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        GalleryItem::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $items = [
            // Bags
            ['category' => 'bags', 'path' => 'assets/gallery/bags5.jpg',  'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags6.jpg',  'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags7.webp', 'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags8.jpg',  'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags9.jpg',  'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags10.jpg', 'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags11.jpg', 'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags12.jpg', 'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags13.jpg', 'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags15.jpg', 'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/bags20.jpg', 'alt' => 'Bag product'],
            ['category' => 'bags', 'path' => 'assets/gallery/hob1.jpg',   'alt' => 'Branded laptop sleeve'],
            // Furniture
            ['category' => 'furniture', 'path' => 'assets/gallery/furniture.jpg',  'alt' => 'Custom furniture'],
            ['category' => 'furniture', 'path' => 'assets/gallery/furniture1.jpg', 'alt' => 'Custom furniture'],
            ['category' => 'furniture', 'path' => 'assets/gallery/furniture2.jpg', 'alt' => 'Custom furniture'],
            ['category' => 'furniture', 'path' => 'assets/gallery/furniture3.jpg', 'alt' => 'Custom furniture'],
            ['category' => 'furniture', 'path' => 'assets/gallery/furniture4.jpg', 'alt' => 'Custom furniture'],
            ['category' => 'furniture', 'path' => 'assets/gallery/furniture5.jpg', 'alt' => 'Custom furniture'],
            ['category' => 'furniture', 'path' => 'assets/gallery/furniture6.jpg', 'alt' => 'Custom furniture'],
            ['category' => 'furniture', 'path' => 'assets/gallery/furniture8.jpg', 'alt' => 'Custom furniture'],
            // Interior
            ['category' => 'interior', 'path' => 'assets/gallery/interior.jpg',   'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior1.jpg',  'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior2.jpg',  'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior3.jpg',  'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior4.jpg',  'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior5.jpg',  'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior6.jpg',  'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior7.jpg',  'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior8.jpg',  'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior9.jpg',  'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior10.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior11.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior12.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior13.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior14.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior15.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior16.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior17.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior18.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior19.jpg', 'alt' => 'Interior design'],
            ['category' => 'interior', 'path' => 'assets/gallery/interior20.jpg', 'alt' => 'Interior design'],
        ];

        foreach ($items as $i => $item) {
            GalleryItem::create(array_merge($item, ['is_active' => true, 'sort_order' => $i]));
        }
    }
}
