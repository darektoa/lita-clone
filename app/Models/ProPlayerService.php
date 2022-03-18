<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlayerService extends Model
{
    use HasFactory;

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
}
