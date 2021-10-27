<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    public function run()
    {
        AppSetting::create([
            'coin_conversion'   => 150,
            'company_revenue'   => 25,
        ]);
    }
}
