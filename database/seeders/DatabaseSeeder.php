<?php

namespace Database\Seeders;

use App\Models\{CoinPurchase, Player, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CoinSeeder::class,
        ]);

        $player = Player::factory()
            ->count(1)
            ->hasCoinPurchases(2);
        
        User::factory()
            ->count(10)
            ->has($player)
            ->create();
    }
}
