<?php

namespace Database\Seeders;

use App\Models\{CoinPurchase, Player};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CoinSeeder::class,
        ]);

        Player::factory()
            ->count(10)
            ->hasCoinPurchases(2)
            ->create();

        // \App\Models\User::factory(10)->create();
    }
}
