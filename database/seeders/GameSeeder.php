<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run()
    {
        $games = [
            [
                'name'  => 'Mobile Legends',
                'icon'  => 'images/game-icons/2787b04a-b885-41c1-92fa-6134d89d278a.jpg',
                'tiers' => ['Warrior', 'Elite', 'Master', 'Grand Master', 'Epic', 'Legend', 'Mythic', 'Mythical Glory'],
                'roles' => ['Assasin', 'Fighter', 'Mage', 'Marksman', 'Support', 'Tank'],
            ], [
                'name'  => 'Free Fire',
                'icon'  => 'images/game-icons/7f4bc08c-2ab6-4fb9-90b8-1c5872d19588.jpg',
                'tiers' => ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Heroic'],
                'roles' => ['Flanker', 'Hitscan', 'Leader', 'Sniper', 'Support', 'Survivor', 'Tracker'],
            ], [
                'name'  => 'PUBG Mobile',
                'icon'  => 'images/game-icons/8dc23b2e-4a50-4b8d-818e-3f37e3f5230f.jpg',
                'tiers' => ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Crown', 'Ace', 'Conqueror'],
                'roles' => ['Leader', 'Rusher', 'Scout', 'Sniper', 'Support'],
            ], [
                'name'  => 'Call of Duty Mobile',
                'icon'  => 'images/game-icons/aac7e851-85a4-4727-ab7d-e9516154585c.jpg',
                'tiers' => ['Rookie', 'Veteran', 'Elite', 'Pro', 'Master', 'Legendary'],
                'roles' => ['Airborne', 'Clown', 'Defender', 'Mechanic', 'Medic', 'Ninja', 'Scout', 'Trickster'],
            ], [
                'name'  => 'Valorant',
                'icon'  => 'images/game-icons/cbbea757-49b3-42e9-8163-91093a8c6e43.jpg',
                'tiers' => ['Iron', 'Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Immortal', 'Radiant'], 
                'roles' => ['Duelist', 'Controller', 'Initiator', 'Sentinel'],
            ], [
                'name'  => 'LOL: Wild Rift',
                'icon'  => 'images/game-icons/617663f9-3f71-41e9-b95f-0acad5302657.jpg',
                'tiers' => ['Iron', 'Bronze', 'Silver', 'Gold', 'Platinum', 'Emerald', 'Diamond', 'Master', 'Grandmaster', 'Challenger'],
                'roles' => ['Top Laner', 'Jungler', 'Mid Laner', 'Bot Laner', 'Support'],
            ], [
                'name'  => 'Pokemon Unite',
                'icon'  => 'images/game-icons/efed3590-1818-45f2-848f-777599eb9c6a.jpg',
                'tiers' => ['Beginner', 'Great', 'Expert', 'Veteran', 'Ultra', 'Master'],
                'roles' => ['Attacker', 'All-Rounder', 'Defender', 'Speedster', 'Supporter'],
            ],
        ];


        
        foreach($games as $game) {
            $model = Game::create([
                'name' => $game['name'],
                'icon' => $game['icon'],
            ]);

            foreach($game['tiers'] as $tier) $model->gameTiers()->create(['name' => $tier]);
            foreach($game['roles'] as $role) $model->gameRoles()->create(['name' => $role]);
        }
    }
}
