<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CoinSeeder::class,
            TierSeeder::class,
            GameSeeder::class,
            GenderSeeder::class,
            AppBannerSeeder::class, 
            AppSettingSeeder::class,
            AvailableTransferSeeder::class,
        ]);
    }
}
