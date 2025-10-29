<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run()
    {
        $regions = [
            [
                'name' => 'Jakarta',
                'whatsapp' => '628123456789',
                'order_num' => 1,
                'meta_title' => 'Wilayah Jakarta - Layanan Terbaik',
                'meta_description' => 'Layanan terbaik di wilayah Jakarta dengan tim profesional.'
            ],
            [
                'name' => 'Bandung',
                'whatsapp' => '628987654321',
                'order_num' => 2,
                'meta_title' => 'Wilayah Bandung - Layanan Terpercaya',
                'meta_description' => 'Layanan terpercaya di wilayah Bandung dengan pengalaman bertahun-tahun.'
            ]
        ];

        foreach ($regions as $regionData) {
            Region::create($regionData);
        }
    }
}