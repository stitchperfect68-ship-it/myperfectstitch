<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title'          => '<em>Business Excellence Award</em>',
                'tag'            => 'She Entrepreneur Founders Summit 2026',
                'event_type'     => 'award',
                'gallery_layout' => 'g5',
                'text_first'     => false,
                'event_date'     => '2026-03-15',
                'description'    => "Ruth Kayira Mooto, Founder and CEO of My Perfect Stitch, was honoured with the Business Excellence Award at the She Entrepreneur Founders Summit 2026 — one of the most prestigious gatherings for women in business across the SADC region.\n\nThe award recognises outstanding achievement in entrepreneurship, social impact, and business innovation. Ruth was recognised for her decade of building a world-class design and manufacturing business from Lusaka that now serves institutional clients across Zambia and the region.\n\nThis recognition reflects not just her personal journey, but the dedication of the entire My Perfect Stitch team — from our craftswomen on the production floor to our design and logistics teams.",
                'images'         => [
                    ['path'=>'assets/award.jpg', 'role'=>'hero'],
                    ['path'=>'assets/award1.jpg','role'=>'secondary'],
                    ['path'=>'assets/award2.jpg','role'=>'secondary'],
                    ['path'=>'assets/award3.jpg','role'=>'secondary'],
                    ['path'=>'assets/award4.jpg','role'=>'secondary'],
                ],
            ],
            [
                'title'          => "Celebrating the Women of <em>My Perfect Stitch</em>",
                'tag'            => "International Women's Day",
                'event_type'     => 'iwd',
                'gallery_layout' => 'g7',
                'text_first'     => true,
                'event_date'     => '2026-03-08',
                'description'    => "On International Women's Day 2026, we paused to celebrate the extraordinary women at the heart of My Perfect Stitch. From our skilled craftswomen who bring every design to life, to our operations team who keep the business running with precision — this day is for them.\n\nThis year's IWD theme, *Give to Gain*, resonates deeply with us. My Perfect Stitch was built on the conviction that when you invest in women — their skills, their confidence, their livelihoods — the returns flow back into families, communities, and the broader economy.\n\nWe remain committed to growing our team of female craftspeople, offering skills development, and creating a workplace where excellence and dignity go hand in hand.",
                'images'         => [
                    ['path'=>'assets/women.jpg', 'role'=>'hero'],
                    ['path'=>'assets/women1.jpg','role'=>'secondary'],
                    ['path'=>'assets/women2.jpg','role'=>'secondary'],
                    ['path'=>'assets/women3.jpg','role'=>'secondary'],
                    ['path'=>'assets/women4.jpg','role'=>'secondary'],
                    ['path'=>'assets/women5.jpg','role'=>'secondary'],
                    ['path'=>'assets/women6.jpg','role'=>'secondary'],
                ],
            ],
            [
                'title'          => "One Week of Hands-On <em>Leather Training</em>",
                'tag'            => 'Leather Training · Time & Tide Foundation',
                'event_type'     => 'workshop',
                'gallery_layout' => 'g1',
                'text_first'     => false,
                'event_date'     => '2025-11-10',
                'description'    => "My Perfect Stitch partnered with the Time and Tide Foundation to host a one-week intensive leather training workshop in collaboration with Fine Stitches Leather, a leading leather goods manufacturer.\n\nThe training covered everything from pattern cutting and edge finishing to hand stitching and hardware installation. Twelve participants — all women from Lusaka's informal economy — completed the full programme, with three subsequently joining our production team.\n\nSkills transfer is at the core of our mission. When we grow, we aim to grow the talent pipeline around us.",
                'images'         => [
                    ['path'=>'assets/workshop.jpg','role'=>'hero'],
                ],
            ],
        ];

        foreach ($events as $i => $data) {
            $images = $data['images'];
            unset($data['images']);

            $event = Event::firstOrCreate(
                ['event_type' => $data['event_type']],
                array_merge($data, [
                    'slug'       => Str::slug(strip_tags($data['title'])) . '-' . Str::random(4),
                    'is_active'  => true,
                    'sort_order' => $i,
                ])
            );

            if ($event->wasRecentlyCreated) {
                foreach ($images as $j => $img) {
                    EventImage::create([
                        'event_id'   => $event->id,
                        'path'       => $img['path'],
                        'alt'        => strip_tags($data['title']),
                        'role'       => $img['role'],
                        'sort_order' => $j,
                    ]);
                }
            }
        }
    }
}
