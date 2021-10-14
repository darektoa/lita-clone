<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'mobileLegends' => ['Assasin', 'Fighter', 'Mage', 'Marksman', 'Support', 'Tank'],
            'freeFire'      => ['Flanker', 'Hitscan', 'Leader', 'Sniper', 'Support', 'Survivor', 'Tracker'],
            'pubgMobile'    => ['Leader', 'Rusher', 'Scout', 'Sniper', 'Support'],
        ];

        $tiers = [
            'mobileLegends' => ['Warrior', 'Elite', 'Master', 'Grand Master', 'Epic', 'Legend', 'Mythic', 'Mythical Glory'],
            'freeFire'      => ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Heroic'],
            'pubgMobile'    => ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Crown', 'Ace', 'Conqueror'],
        ];


        // Mobile Legends
        $game = Game::create(['name' => 'Mobile Legends']);
        foreach($tiers['mobileLegends'] as $tier) $game->gameTiers()->create(['name' => $tier]);
        foreach($roles['mobileLegends'] as $role) $game->gameRoles()->create(['name' => $role]);
        

        // Free Fire
        $game = Game::create(['name' => 'Free Fire']);
        foreach($tiers['freeFire'] as $tier) $game->gameTiers()->create(['name' => $tier]);
        foreach($roles['freeFire'] as $role) $game->gameRoles()->create(['name' => $role]);
        
        
        // PUBG Mobile
        $game = Game::create(['name' => 'PUBG Mobile']);
        foreach($tiers['pubgMobile'] as $tier) $game->gameTiers()->create(['name' => $tier]);
        foreach($roles['pubgMobile'] as $role) $game->gameRoles()->create(['name' => $role]);
    }
}
