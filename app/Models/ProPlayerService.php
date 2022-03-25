<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProPlayerService extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'activity_name',
        'status_name',
        'price_permatch',
    ];

    protected $guarded = ['id'];


    public function player() {
        return $this->belongsTo(Player::class);
    }


    public function service() {
        return $this->belongsTo(Service::class);
    }

    
    public function proPlayerOrderReviews() {
        return $this->hasManyThrough(ProPlayerOrderReview::class, ProPlayerOrder::class);
    }


    public function getStarsAttribute() {
        $stars = ProPlayerService::with(['proPlayerOrderReviews'])
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
            $service        = ProPlayerService::find($this->id);
            $coinConversion = AppSetting::first()->coin_conversion;
            $basePrice      = $service->service->price;
            $coinPrice      = $basePrice;
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
            $service            = ProPlayerService::find($this->id);
            $playerRevenue      = $service->service->player_revenue / 100;
            $pricePermatch      = $service->price_permatch;
            $coinPrice          = $pricePermatch['coin'] * $playerRevenue;
            $balancePrice       = $pricePermatch['balance'] * $playerRevenue;

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
            $query->whereRelation('proPlayerService', 'id', $this->id);
        })->get()->avg('star');

        $this->update(['rate' => $avgRate]);
    }
}
