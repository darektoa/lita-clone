<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlayerSkill extends Model
{
    use HasFactory;

    protected $appends  = [
        'activity_name',
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


    public function proPlayerOrders() {
        return $this->hasMany(ProPlayerOrder::class);
    }


    public function proPlayerOrderReviews() {
        return $this->hasManyThrough(ProPlayerOrderReview::class, ProPlayerOrder::class);
    }


    public function getStarsAttribute() {
        $stars = ProPlayerSkill::with(['proPlayerOrderReviews'])
            ->find($this->id)
            ->proPlayerOrderReviews()
            ->where('star', '!=', null)
            ->selectRaw('star, COUNT(*) as total')
            ->groupBy('star')
            ->get();

        $stars = $stars
            ->pluck('total', 'star')
            ->put('total', $stars->sum('total'));
        
        return $stars;
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


    public function getActivityNameAttribute() {
        $activityName = null;

        switch($this->activity) {
            case 0: $activityName = 'Offline'; break;
            case 1: $activityName = 'Online'; break;
            case 2: $activityName = 'In Order'; break;
            default: $activityName = 'Unknown';
        }

        return $activityName;
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


    public function getProPlayerPriceAttribute() {
        try{
            $skill              = ProPlayerSkill::find($this->id);
            $companyRevenue     = AppSetting::first()->company_revenue;
            $proPlayerRevenue   = (100 - $companyRevenue) / 100;
            $pricePermatch      = $skill->price_permatch;
            $coinPrice          = $pricePermatch['coin'] * $proPlayerRevenue;
            $balancePrice       = $pricePermatch['balance'] * $proPlayerRevenue;

            return [
                'coin'      => $coinPrice,
                'balance'   => $balancePrice
            ];
        }catch(Exception $err) {
            return [];
        }
    }
}
