<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        ProductImage::truncate();
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $bags      = ProductCategory::where('slug', 'bags')->first();
        $furniture = ProductCategory::where('slug', 'furniture')->first();
        $subs      = ProductCategory::whereIn('slug', [
            'backpacks','coin-purses','laptop-bags','laptop-sleeves',
            'pencil-cases','tote-bags','travel-bags',
            'arm-chairs','benches','love-seats','modular-sofas','sofas','stools',
        ])->get()->keyBy('slug');

        // Exact match with static index.html — image paths, names, prices, tags
        $products = [
            // ── BAGS › Backpacks ─────────────────────────────────────────────
            [
                'name'  => 'Muchinga',
                'price' => 750,
                'cat'   => 'bags', 'sub' => 'backpacks',
                'img'   => 'assets/bags/BACKPACKS.jpg',
                'tag_type' => 'tag-std', 'tag_label' => 'Popular',
                'short_description' => 'Spacious chitenge backpacks with padded straps and multiple compartments.',
            ],
            [
                'name'  => 'Muchinga',
                'price' => 750,
                'cat'   => 'bags', 'sub' => 'backpacks',
                'img'   => 'assets/bags/Muchinga back pack.jpg',
                'short_description' => 'Spacious chitenge backpack with padded straps and multiple compartments.',
            ],
            [
                'name'  => 'Nyika',
                'price' => 750,
                'cat'   => 'bags', 'sub' => 'backpacks',
                'img'   => 'assets/bags/Nyika BACKPACK.jpg',
                'short_description' => 'Spacious chitenge backpack with padded straps and multiple compartments.',
            ],
            [
                'name'  => 'Nyika',
                'price' => 750,
                'cat'   => 'bags', 'sub' => 'backpacks',
                'img'   => 'assets/bags/Nyika BACKPACK2.jpg',
                'short_description' => 'Spacious chitenge backpack with padded straps and multiple compartments.',
            ],
            // ── BAGS › Coin Purses ───────────────────────────────────────────
            [
                'name'  => 'Coin Purse',
                'price' => 100,
                'cat'   => 'bags', 'sub' => 'coin-purses',
                'img'   => 'assets/bags/COIN PURSES.jpg',
                'short_description' => 'Compact chitenge coin purses in vibrant African prints for everyday carry.',
            ],
            [
                'name'  => 'Choma',
                'price' => 100,
                'cat'   => 'bags', 'sub' => 'coin-purses',
                'img'   => 'assets/bags/Choma mini-coin purse.jpg',
                'short_description' => 'Compact chitenge coin purses in vibrant African prints for everyday carry.',
            ],
            [
                'name'  => 'Choma 1',
                'price' => 100,
                'cat'   => 'bags', 'sub' => 'coin-purses',
                'img'   => 'assets/bags/Choma mini-coin purse1.jpg',
                'short_description' => 'Compact chitenge coin purses in vibrant African prints for everyday carry.',
            ],
            // ── BAGS › Laptop Bags ───────────────────────────────────────────
            [
                'name'  => 'Luangwa',
                'price' => 750,
                'cat'   => 'bags', 'sub' => 'laptop-bags',
                'img'   => 'assets/bags/luapula LAPTOP BAG.jpg',
                'short_description' => 'Padded chitenge laptop bags designed to protect and impress.',
            ],
            [
                'name'  => 'Luapula',
                'price' => 650,
                'cat'   => 'bags', 'sub' => 'laptop-bags',
                'img'   => 'assets/bags/Luapula laptop bag1.jpg',
                'short_description' => 'Padded chitenge laptop bags designed to protect and impress.',
            ],
            [
                'name'  => 'Luangwa Chitenge & leather',
                'price' => 750,
                'cat'   => 'bags', 'sub' => 'laptop-bags',
                'img'   => 'assets/bags/Luapula laptop bag2.jpg',
                'short_description' => 'Padded chitenge laptop bags designed to protect and impress.',
            ],
            // ── BAGS › Laptop Sleeves ────────────────────────────────────────
            [
                'name'  => 'Chambeshi',
                'price' => 375,
                'cat'   => 'bags', 'sub' => 'laptop-sleeves',
                'img'   => 'assets/bags/LAPTOP SLEEVES.jpg',
                'short_description' => 'Slim, protective laptop sleeves in bold African print fabrics.',
            ],
            [
                'name'  => 'Chambeshi',
                'price' => 375,
                'cat'   => 'bags', 'sub' => 'laptop-sleeves',
                'img'   => 'assets/bags/laptop sleeve1.jpg',
                'short_description' => 'Slim, protective laptop sleeves in bold African print fabrics.',
            ],
            [
                'name'  => 'Chambeshi',
                'price' => 375,
                'cat'   => 'bags', 'sub' => 'laptop-sleeves',
                'img'   => 'assets/bags/laptop sleeve2.jpg',
                'short_description' => 'Slim, protective laptop sleeves in bold African print fabrics.',
            ],
            // ── BAGS › Pencil Cases ──────────────────────────────────────────
            [
                'name'  => 'Pencil Case',
                'price' => 185,
                'cat'   => 'bags', 'sub' => 'pencil-cases',
                'img'   => 'assets/bags/PENCIL CASES.jpg',
                'short_description' => 'Colourful chitenge pencil cases — perfect for school or the office.',
            ],
            [
                'name'  => 'Pencil Case 1',
                'price' => 185,
                'cat'   => 'bags', 'sub' => 'pencil-cases',
                'img'   => 'assets/bags/PENCIL CASES1.jpg',
                'short_description' => 'Colourful chitenge pencil cases — perfect for school or the office.',
            ],
            // ── BAGS › Tote Bags ─────────────────────────────────────────────
            [
                'name'  => 'Kariba',
                'price' => 275,
                'cat'   => 'bags', 'sub' => 'tote-bags',
                'img'   => 'assets/bags/TOTE BAGS.jpg',
                'tag_type' => 'tag-std', 'tag_label' => 'New Arrival',
                'short_description' => 'Stylish chitenge tote bags — spacious, structured, and unmistakably Zambian.',
            ],
            [
                'name'  => 'Kafue',
                'price' => 600,
                'cat'   => 'bags', 'sub' => 'tote-bags',
                'img'   => 'assets/bags/tote kafue.jpg',
                'short_description' => 'Stylish chitenge tote bags — spacious, structured, and unmistakably Zambian.',
            ],
            [
                'name'  => 'Kafue',
                'price' => 600,
                'cat'   => 'bags', 'sub' => 'tote-bags',
                'img'   => 'assets/bags/tote Kafue1.jpg',
                'short_description' => 'Stylish chitenge tote bags — spacious, structured, and unmistakably Zambian.',
            ],
            [
                'name'  => 'Kariba',
                'price' => 750,
                'cat'   => 'bags', 'sub' => 'tote-bags',
                'img'   => 'assets/bags/tote.jpg',
                'short_description' => 'Stylish chitenge tote bags — spacious, structured, and unmistakably Zambian.',
            ],
            // ── BAGS › Travel Bags ───────────────────────────────────────────
            [
                'name'  => 'Mosi-oa-Tunya',
                'price' => 850,
                'cat'   => 'bags', 'sub' => 'travel-bags',
                'img'   => 'assets/bags/Mosi-oa-Tunya travel bag.jpg',
                'short_description' => 'Durable chitenge travel bag — roomy, structured, and built for the journey.',
            ],
            [
                'name'  => 'Mosi-oa-Tunya',
                'price' => 850,
                'cat'   => 'bags', 'sub' => 'travel-bags',
                'img'   => 'assets/bags/Mosi-oa-Tunya travel bag2.jpg',
                'short_description' => 'Durable chitenge travel bag — roomy, structured, and built for the journey.',
            ],
            // ── FURNITURE ────────────────────────────────────────────────────
            [
                'name'  => 'Arm Chair',
                'price' => 0,
                'cat'   => 'furniture', 'sub' => 'arm-chairs',
                'img'   => 'assets/furniture/ARM CHAIRS.jpg',
                'short_description' => 'Custom upholstered arm chairs tailored to your space and style.',
            ],
            [
                'name'  => 'Bench',
                'price' => 0,
                'cat'   => 'furniture', 'sub' => 'benches',
                'img'   => 'assets/furniture/BENCHES.jpg',
                'short_description' => 'Handcrafted benches in leather, fabric, and mixed-media finishes.',
            ],
            [
                'name'  => 'Love Seat',
                'price' => 0,
                'cat'   => 'furniture', 'sub' => 'love-seats',
                'img'   => 'assets/furniture/LOVE SEATS.jpg',
                'short_description' => 'Intimate two-seater love seats for lounges and living spaces.',
            ],
            [
                'name'  => 'Modular Sofa',
                'price' => 0,
                'cat'   => 'furniture', 'sub' => 'modular-sofas',
                'img'   => 'assets/furniture/MODULAR SOFAS.jpg',
                'short_description' => 'Configurable modular sofa systems for commercial and residential use.',
            ],
            [
                'name'  => 'Sofa',
                'price' => 0,
                'cat'   => 'furniture', 'sub' => 'sofas',
                'img'   => 'assets/furniture/SOFAS.jpg',
                'short_description' => 'Classic and contemporary sofas built to spec with premium upholstery.',
            ],
            [
                'name'  => 'Stool',
                'price' => 0,
                'cat'   => 'furniture', 'sub' => 'stools',
                'img'   => 'assets/furniture/STOOLS.jpg',
                'short_description' => 'Versatile upholstered stools for bars, offices, and home interiors.',
            ],
        ];

        foreach ($products as $i => $data) {
            $catModel = $data['cat'] === 'bags' ? $bags : $furniture;
            $subModel = $subs[$data['sub']] ?? null;

            $product = Product::create([
                'slug'              => Str::slug($data['name']) . '-' . Str::random(4),
                'name'              => $data['name'],
                'short_description' => $data['short_description'] ?? null,
                'category_id'       => $catModel?->id,
                'subcategory_id'    => $subModel?->id,
                'price'             => $data['price'],
                'tag_type'          => $data['tag_type'] ?? null,
                'tag_label'         => $data['tag_label'] ?? null,
                'is_active'         => true,
                'sort_order'        => $i,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $data['img'],
                'alt'        => $data['name'],
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }
    }
}
