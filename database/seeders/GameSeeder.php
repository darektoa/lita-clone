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
                'icon'  => 'images/game-icons/c2042d83-236c-4e8f-86b9-5af295c11eda.png',
                'tiers' => ['Warrior', 'Elite', 'Master', 'Grand Master', 'Epic', 'Legend', 'Mythic', 'Mythical Glory'],
                'roles' => ['Assasin', 'Fighter', 'Mage', 'Marksman', 'Support', 'Tank'],
            ], [
                'name'  => 'Free Fire',
                'icon'  => 'images/game-icons/ac6256da-0a0d-4ef7-a58e-b5ed59842252.png',
                'tiers' => ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Heroic'],
                'roles' => ['Flanker', 'Hitscan', 'Leader', 'Sniper', 'Support', 'Survivor', 'Tracker'],
            ], [
                'name'  => 'PUBG Mobile',
                'icon'  => 'images/game-icons/be0b28ea-26c0-43db-92d2-e52df57107ad.png',
                'tiers' => ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Crown', 'Ace', 'Conqueror'],
                'roles' => ['Leader', 'Rusher', 'Scout', 'Sniper', 'Support'],
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
