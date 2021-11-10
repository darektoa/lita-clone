<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlayerSkill extends Model
{
    use HasFactory;

    protected $appends  = [
        'status_name',
        'price_permatch'
    ];

    protected $guarded  = ['id'];


    public function player() {
        return $this->belongsTo(Player::class);
    }


    public function game() {
        return $this->belongsTo(Game::class);
    }


    public function tier() {
        return $this->belongsTo(Tier::class);
    }


    public function proPlayerSkillScreenshots() {
        return $this->hasMany(ProPlayerSkillScreenshot::class);
    }


    public function getStatusNameAttribute() {
        $statusName = null;

        switch($this->status) {
            case 0: $statusName = 'Pending'; break;
            case 1: $statusName = 'Rejected'; break;
            case 2: $statusName = 'Approved'; break;
            default: $statusName = 'Unknown';
        }

        return $statusName;
    }


    public function getPricePermatchAttribute() {
        try{
            $skill          = $this::with(['game', 'tier']);
            $coinConversion = AppSetting::first()->coin_conversion;
            $basePrice      = $skill->game->base_price;
            $priceIncrease  = $basePrice * ($skill->tier->price_increase/100);
            $coinPrice      = $basePrice + $priceIncrease;
            $balancePrice   = $coinPrice * $coinConversion;

            $this->unsetRelations();
    
            return [
                'coin'      => $coinPrice,
                'balance'   => $balancePrice,
            ];
        }catch(Exception $err) {
            return [];
        }
    }
}
