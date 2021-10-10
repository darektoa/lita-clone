<?php

namespace Database\Seeders;

use App\Models\{User, Admin, Player};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userAdmin = User::create([
            'first_name'        => 'Admin',
            'last_name'         => 'B',
            'username'          => 'admin',
            'email'             => 'admin@gmail.com',
            'password'          =>  Hash::make('password'),
            'email_verified_at' => now()
        ]);
        
        $userAdmin->admin()->create();
        $userAdmin->loginTokens()->create([
            'token' => '$2y$10$Ubw/2M/n.VYJLyRI4URRzOlIz/eWoclIZPTl7lIOd0nqcLJXLD26S'
        ]);

        
        User::create([
            'first_name'        => 'Player',
            'last_name'         => 'B',
            'username'          => 'player',
            'email'             => 'player@gmail.com',
            'password'          =>  Hash::make('password'),
            'email_verified_at' => now()
        ])->player()->create();
    }
}
