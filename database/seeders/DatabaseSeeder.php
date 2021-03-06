<?php

namespace Database\Seeders;

use App\Models\{Player, ProPlayerOrder, ProPlayerSkill, User};
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
            ServiceSeeder::class,
            ProPlayerSeeder::class,
            AppBannerSeeder::class, 
            AppSettingSeeder::class,
            AvailableTransferSeeder::class,
            UsernameExceptionSeeder::class,
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

        ProPlayerOrder::factory()
            ->count(100)
            ->hasReview(1)
            ->create([
                'status' => 4 // Ended status
            ]);
    }
}
