<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        Service::create([
            'icon'  => 'images/service-icons/f1fe6ba5-ae9c-4abe-a70a-03a2e3524524.png',
            'name'  => 'Teman Curhat',
            'price' => 100,
        ]);
    }
}
