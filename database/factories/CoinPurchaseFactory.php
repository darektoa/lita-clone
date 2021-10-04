<?php

namespace Database\Factories;

use App\Models\{Admin, Coin, CoinPurchase, Player};
use Illuminate\Database\Eloquent\Factories\Factory;

class CoinPurchaseFactory extends Factory
{
    protected $model = CoinPurchase::class;


    public function definition()
    {
        $player = Player::inRandomOrder()->first();
        $admin = Admin::inRandomOrder()->first();
        $coin = Coin::skip(1)->inRandomOrder()->first();

        return [
            'player_id' => $player->id,
            'admin_id' => $admin->id,
            'coin_id' => $coin->id,
        ];
    }
}
