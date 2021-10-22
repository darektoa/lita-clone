<?php

namespace Database\Seeders;

use App\Models\PredefineCoin;
use Illuminate\Database\Seeder;

class CoinSeeder extends Seeder
{
    public function run()
    {
        $coins = [
            [
                'coin' => 100,
                'balance' => 14000,
            ],
            [
                'coin' => 200,
                'balance' => 27000,
            ],
            [
                'coin' => 500,
                'balance' => 68000,
            ],
            [
                'coin' => 1000,
                'balance' => 135000,
            ],
            [
                'coin' => 2000,
                'balance' => 260000,
            ],
            [
                'coin' => 5000,
                'balance' => 650000,
            ],
        ];

        foreach($coins as $coin){
            PredefineCoin::create($coin);
        }
    }
}
