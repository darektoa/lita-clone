<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CoinPurchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id'
    ];


    public function player() {
        return $this->belongsTo(Player::class);
    }


    public function admin() {
        return $this->belongsTo(Admin::class);
    }


    public function coin() {
        return $this->belongsTo(Coin::class);
    }


    public function statusName() {
        $statusName = null;

        switch($this->status) {
            case 0: $statusName = 'Pending';break;
            case 1: $statusName = 'Rejected';break;
            case 2: $statusName = 'Approved';break;
            case 3: $statusName = 'Canceled';break;
            default: $statusName = 'Unknown';break;
        }

        return $statusName;
    }
}
