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


    public function scopeClaim($query, $user) {
        $dailyCoin = $query->firstOrCreate(
            ['user_id' => $user->id],
            ['data'    => $this->generateData()],
        );

        $tz     = +7;
        $date   = now()->addHours($tz)->toDateString();
        $data   = collect($dailyCoin->data);
        $claimed = $data
            ->where('created_at', $date)
            ->whereNull('claimed_at')
            ->first();

        if(!$claimed) return null;

        $data   = $data->map(function($item) use($claimed) {
            if($item->id === $claimed->id)
                $item->claimed_at = now()->toDateTimeString();

            return $item;
        });

        $dailyCoin->update([
            'data'  => $data,
        ]);

        $user->player->update([
            'coin'  => $user->player->coin + $claimed->coin,
        ]);

        return $claimed;
    }
}
