<?php

namespace Database\Seeders;

use App\Models\Tier;
use Illuminate\Database\Seeder;

class TierSeeder extends Seeder
{
    public function run()
    {
        $tier = new Tier();

        $tier->create(['name' => 'B',   'price_increase' => 10,  'min_order' => 0   ]);
        $tier->create(['name' => 'B+',  'price_increase' => 20,  'min_order' => 50  ]);
        $tier->create(['name' => 'A',   'price_increase' => 40,  'min_order' => 100 ]);
        $tier->create(['name' => 'A+',  'price_increase' => 60,  'min_order' => 200 ]);
        $tier->create(['name' => 'S',   'price_increase' => 90,  'min_order' => 400 ]);
        $tier->create(['name' => 'S+',  'price_increase' => 120, 'min_order' => 700 ]);
    }
}
