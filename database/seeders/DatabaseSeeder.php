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
            GameSeeder::class,
            GenderSeeder::class,
            AppSettingSeeder::class,
        ]);

        $player = Player::factory()
            ->count(1)
            ->hasProPlayerSkills(2);
        
        User::factory()
            ->count(20)
            ->has($player)
            ->create();
    }
}
