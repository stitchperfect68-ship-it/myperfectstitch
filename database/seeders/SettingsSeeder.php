<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name'        => 'My Perfect Stitch',
            'site_tagline'     => 'Creating Happiness, Lusaka Zambia',
            'contact_email'    => 'hello@myperfectstitch.com',
            'contact_phone'    => '+260 968 531 630',
            'address'          => 'Lusaka, Zambia',
            'whatsapp_number'  => '260968531630',
            'facebook_url'     => '',
            'instagram_url'    => '',
            'linkedin_url'     => '',
            'currency'         => 'ZMW',
            'currency_symbol'  => 'K',
            'meta_title'       => 'My Perfect Stitch — Creating Happiness, Lusaka Zambia',
            'meta_description' => "Zambia's premier design and manufacturing studio — custom bags, bespoke furniture, and commercial interiors. Built in Lusaka, delivered to world-class standards.",
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
