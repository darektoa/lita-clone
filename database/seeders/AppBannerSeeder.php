<?php

namespace Database\Seeders;

use App\Models\AppBanner;
use Illuminate\Database\Seeder;

class AppBannerSeeder extends Seeder
{
    public function run()
    {
        $banners = [
            [
                'image' => 'images/banners/4e888c49-59e4-4cae-bfb4-74e534ed4d6d.png',
                'alt'   => 'Daftar untuk menjadi pro player',
            ], [
                'image' => 'images/banners/301f761e-de9d-4fbf-9c5a-3bdae0e7d985.png',
                'alt'   => 'Bosan hanya bermain dengan teman yang cupu?',
            ], [
                'image' => 'images/banners/9d27362c-72ef-4119-b7ee-da75aa2e38bc.png',
                'alt'   => 'Topup coin mudah'
            ]
        ];


        foreach($banners as $banner) {
            AppBanner::create([
                'image' => $banner['image'],
                'alt'   => $banner['alt'],
            ]);
        }
    }
}
