<?php

namespace Database\Factories;

use App\Models\{Player, ProPlayerOrder, ProPlayerSkill};
use Illuminate\Database\Eloquent\Factories\Factory;

class ProPlayerOrderFactory extends Factory
{
    protected $model = ProPlayerOrder::class;


    public function definition()
    {
        $player         = Player::inRandomOrder()->first();
        $proPlayerSkill = ProPlayerSkill::where('status', 2)->inRandomOrder()->first();
        $revenue        = $proPlayerSkill->pro_player_price;
        $quantity       = rand(1, 10);

        return [
            'player_id'             => $player->id,
            'pro_player_skill_id'   => $proPlayerSkill->id,
            'coin'                  => $revenue['coin'],
            'balance'               => $revenue['balance'],
            'quantity'              => $quantity,
            'play_duration'         => 30 * $quantity,
            'expiry_duration'       => rand(5, 60),
            'status'                => rand(0, 5)
        ];
    }
}
