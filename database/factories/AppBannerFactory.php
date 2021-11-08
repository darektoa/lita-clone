<?php

namespace Database\Factories;

use App\Models\AppBanner;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppBannerFactory extends Factory
{
    protected $model = AppBanner::class;


    public function definition()
    {
        return [
            'url'   => 'https://source.unsplash.com/335x115/?game',
            'alt'   => $this->faker->realText(100)
        ];
    }
}
