<?php

namespace Database\Seeders;

use App\Models\{CoinPurchase, Player, ProPlayerSkill, User};
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

        $proPlayerSkills = ProPlayerSkill::factory()
            ->count(2)
            ->hasProPlayerSkillScreenshots(3);

        $player = Player::factory()
            ->count(1)
            ->has($proPlayerSkills);
        
        User::factory()
            ->count(20)
            ->has($player)
            ->create();
    }
}
