<?php

namespace Database\Factories;

use App\Models\{ProPlayerOrder, ProPlayerOrderReview};
use Illuminate\Database\Eloquent\Factories\Factory;

class ProPlayerOrderReviewFactory extends Factory
{
    protected $model = ProPlayerOrderReview::class;

    public function definition()
    {
        $order = ProPlayerOrder::inRandomOrder()->first();

        return [
            'pro_player_order_id'   => $order->id,
            'star'                  => rand(1, 5),
            'review'                => $this->faker->realText(),
        ];
    }
}
