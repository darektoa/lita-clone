<?php

namespace Database\Factories;

use App\Models\{Player, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;


    public function definition()
    {
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'coin' => rand(0, 1000)
        ];
    }
}
