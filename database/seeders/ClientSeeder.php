<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name'=>'Airtel Zambia',         'industry'=>'Telecoms'],
            ['name'=>'ABSA Bank',             'industry'=>'Financial Services'],
            ['name'=>'MTN Zambia',            'industry'=>'Telecoms'],
            ['name'=>'LuSE',                  'industry'=>'Financial Services'],
            ['name'=>'Standard Chartered',    'industry'=>'Financial Services'],
            ['name'=>'First Quantum Minerals','industry'=>'Mining'],
            ['name'=>'ZESCO',                 'industry'=>'Utilities'],
            ['name'=>'Zanaco',                'industry'=>'Financial Services'],
            ['name'=>'Stanbic Bank',          'industry'=>'Financial Services'],
            ['name'=>'Bongohive',             'industry'=>'Innovation Hub'],
            ['name'=>'Hybrid',                'industry'=>'Corporate'],
            ['name'=>'Hobbiton',              'industry'=>'Technology'],
            ['name'=>'Latitude15',            'industry'=>'Technology'],
            ['name'=>'Kenya Re',              'industry'=>'Insurance'],
            ['name'=>'Madison General',       'industry'=>'Insurance'],
            ['name'=>'COMESA',                'industry'=>'Trade Bloc'],
            ['name'=>'Zambia Development Agency','industry'=>'Government'],
            ['name'=>'TEVETA',                'industry'=>'Education'],
            ['name'=>'ZICTA',                 'industry'=>'Regulation'],
            ['name'=>'MultiChoice',           'industry'=>'Media'],
            ['name'=>'Zambia Airports Corp.', 'industry'=>'Aviation'],
            ['name'=>'Ministry of Fisheries', 'industry'=>'Government'],
            ['name'=>'Zambia Tourism',        'industry'=>'Tourism'],
            ['name'=>'Time and Tide Foundation','industry'=>'NGO'],
        ];

        foreach ($clients as $i => $client) {
            Client::firstOrCreate(
                ['name' => $client['name']],
                array_merge($client, ['is_active' => true, 'sort_order' => $i])
            );
        }
    }
}
