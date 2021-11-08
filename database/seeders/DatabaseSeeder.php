<?php

namespace Database\Seeders;

use App\Models\{AppBanner, Player, ProPlayerSkill, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CoinSeeder::class,
            TierSeeder::class,
            GameSeeder::class,
            GenderSeeder::class,
            AppSettingSeeder::class,
        ]);

        AppBanner::factory()->count(6)->create();

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
