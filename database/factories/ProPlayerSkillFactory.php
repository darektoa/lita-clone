<?php

namespace Database\Factories;

use App\Models\{Game, Player};
use App\Models\ProPlayerSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProPlayerSkillFactory extends Factory
{
    protected $model = ProPlayerSkill::class;


    public function definition()
    {
        $player     = Player::inRandomOrder()->first();
        $game       = Game::inRandomOrder()->first();
        $gameTier   = $game->gameTiers()->inRandomOrder()->first();
        $gameRole  = $game->gameRoles()->inRandomOrder()->first();

        return [
            'player_id'     => $player->id,
            'game_id'       => $game->id,
            'game_tier'     => $gameTier->name,
            'game_roles'    => $gameRole->name,
            'game_level'    => rand(10, 30),
        ];
    }
}