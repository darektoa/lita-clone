<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProPlayerSkill extends Model
{
    use HasFactory, SoftDeletes;

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
            case 3: $statusName = 'Banned'; break;
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
            $this->updateTier();
            $skill          = ProPlayerSkill::find($this->id);
            $coinConversion = AppSetting::first()->coin_conversion;
            $basePrice      = $skill->game->base_price;
            $priceIncrease  = $basePrice * ($skill->tier->price_increase/100);
            $coinPrice      = round($basePrice + $priceIncrease);
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
            $this->updateTier();
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


    public function updateRate() {
        $avgRate = ProPlayerOrderReview::whereHas('proPlayerOrder', function($query) {
            $query->whereRelation('proPlayerSkill', 'id', $this->id);
        })->get()->avg('star');

        $this->update(['rate' => $avgRate]);
    }


    public function updateTier() {
        $order  = ProPlayerOrder::where('pro_player_skill_id', $this->id)
            ->whereIn('status', [2, 4])
            ->count();
        $tier   = Tier::orderBy('min_order', 'desc')
            ->where('min_order', '<=', $order)
            ->first();

        $this->update(['tier_id' => $tier->id]);
    }
}
