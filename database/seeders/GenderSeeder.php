<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    public function run()
    {
        Gender::create(['name' => 'male']);
        Gender::create(['name' => 'female']);
    }
}
