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
        'online_name',
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


    public function proPlayerOrderReviews() {
        return $this->hasManyThrough(ProPlayerOrderReview::class, ProPlayerOrder::class);
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


    public function getOnlineNameAttribute() {
        $onlineName = null;

        switch($this->online) {
            case 0: $onlineName = 'Offline'; break;
            case 1: $onlineName = 'Online'; break;
            case 2: $onlineName = 'In Order'; break;
            default: $onlineName = 'Unknown';
        }

        return $onlineName;
    }


    public function getPricePermatchAttribute() {
        try{
            $skill          = ProPlayerSkill::find($this->id);
            $coinConversion = AppSetting::first()->coin_conversion;
            $basePrice      = $skill->game->base_price;
            $priceIncrease  = $basePrice * ($skill->tier->price_increase/100);
            $coinPrice      = $basePrice + $priceIncrease;
            $balancePrice   = $coinPrice * $coinConversion;
    
            return [
                'coin'      => $coinPrice,
                'balance'   => $balancePrice,
            ];
        }catch(Exception $err) {
            return [];
        }
    }
}
