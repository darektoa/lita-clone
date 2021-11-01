<?php

namespace Database\Seeders;

use App\Models\ProPlayerSkillTier;
use Illuminate\Database\Seeder;

class ProPlayerSkillTierSeeder extends Seeder
{
    public function run()
    {
        $tier = new ProPlayerSkillTier();

        $tier->create(['name' => 'B',   'price_increase' => 10]);
        $tier->create(['name' => 'B+',  'price_increase' => 20]);
        $tier->create(['name' => 'A',   'price_increase' => 40]);
        $tier->create(['name' => 'A+',  'price_increase' => 60]);
        $tier->create(['name' => 'S',   'price_increase' => 90]);
        $tier->create(['name' => 'S+',  'price_increase' => 120]);
    }
}
