<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCoin extends Model
{
    use HasFactory;

    protected $casts    = [
        'data'  => 'object',
    ];

    protected $guarded  = ['id'];


    public function user() {
        return $this->belongsTo(User::class);
    }


    protected function generateData($quantity=10) {
        $coin           = 10;
        $data           = collect([]);
        $tz             = +7;
        $coinConversion = AppSetting::first()->coin_conversion;

        for($i = 0; $i < $quantity; $i++) {
            $data->push([
                'id'            => $i + 1,
                'coin'          => $coin,
                'balance'       => $coin * $coinConversion,
                'claimed_at'    => null,
                'created_at'    => now()->addHours($tz)->addDays($i)->toDateString(),
            ]);
        }

        return $data;
    }
}
