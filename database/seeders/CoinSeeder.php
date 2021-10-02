<?php

namespace Database\Seeders;

use App\Models\Coin;
use Illuminate\Database\Seeder;

class CoinSeeder extends Seeder
{
    public function run()
    {
        $coins = [
            [
                'coin' => 1,
                'price' => 120,
            ],
            [
                'coin' => 105,
                'price' => 14000,
            ],
            [
                'coin' => 103,
                'price' => 25000,
            ],
            [
                'coin' => 301,
                'price' => 36000,
            ],
            [
                'coin' => 525,
                'price' => 61000,
            ],
            [
                'coin' => 1043,
                'price' => 118000,
            ],
            [
                'coin' => 2093,
                'price' => 230000,
            ],
        ];

        foreach($coins as $coin){
            Coin::create($coin);
        }
    }
}
