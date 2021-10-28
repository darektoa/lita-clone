<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    public function run()
    {
        Gender::create(['name' => 'Male']);
        Gender::create(['name' => 'Female']);
    }
}
