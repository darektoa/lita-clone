<?php

namespace Database\Seeders;

use App\Models\{User, Game, Tier};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProPlayerSeeder extends Seeder
{
    public function run()
    {
        $userPro = User::create([
            'name'              => 'Pro Player',
            'username'          => 'pro.player',
            'email'             => 'pro.player@gmail.com',
            'password'          => Hash::make('password'),
            'bio'               => 'Hii introdouce myself, i am a pro player',
            'email_verified_at' => now()
        ]);

        $userPro->player()->create();
        $userPro->loginTokens()->create([
            'token' => '$2y$10$rbtnK1LTxxrkQaAHxRsHXOWSkPa6KHG6oPFMGypLHse/vdaQqpZoW'
        ]);

        $game = Game::inRandomOrder()->first();
        $userPro->player->proPlayerSkills()->create([
            'game_id'       => $game->id,
            'game_user_id'  => 810720,
            'game_tier'     => $game->gameTiers()->first()->name,
            'game_roles'    => $game->gameRoles()->first()->name,
            'game_level'    => 25,
            'tier_id'       => Tier::first()->id,
            'status'        => 2,
        ]);
    }
}
