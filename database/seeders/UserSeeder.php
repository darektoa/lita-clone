<?php

namespace Database\Seeders;

use App\Models\{User, Admin, Player};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'first_name'    => 'Admin',
            'last_name'     => 'B',
            'username'      => 'admin',
            'email'         => 'admin@gmail.com',
            'password'      =>  Hash::make('password')
        ])->admin()->create();

        User::create([
            'first_name'    => 'Player',
            'last_name'     => 'B',
            'username'      => 'player',
            'email'         => 'player@gmail.com',
            'password'      =>  Hash::make('password')
        ])->player()->create();
    }
}
