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
            'name'        => 'Admin',
            'username'          => 'admin',
            'email'             => 'admin@gmail.com',
            'password'          => Hash::make('password'),
            'bio'               => 'I want to be a professional administrator',
            'email_verified_at' => now()
        ]);
        
        $userPlayer = User::create([
            'name'        => 'Player',
            'username'          => 'player',
            'email'             => 'player@gmail.com',
            'password'          => Hash::make('password'),
            'bio'               => 'Hii introdouce myself, i am a pro player',
            'email_verified_at' => now()
        ]);


        $userAdmin->admin()->create(['id' => 100000]);
        $userAdmin->loginTokens()->create([
            'token' => '$2y$10$Ubw/2M/n.VYJLyRI4URRzOlIz/eWoclIZPTl7lIOd0nqcLJXLD26S'
        ]);
        
        $userPlayer->player()->create(['id' => 100000]);
        $userPlayer->loginTokens()->create([
            'token' => '$2y$10$O5ffWLD7nnRqNOkGW2K1Xui5Fe6kmsZUBBY7LVn/nxTcazaxdi5ii'
        ]);
    }
}
