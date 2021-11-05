<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $guarded   = ['id'];
    
    protected $withCount = ['followers', 'followings'];

    public $timestamps   = false;


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function coinPurchases() {
        return $this->hasMany(CoinPurchase::class);
    }


    public function proPlayerSkills() {
        return $this->hasMany(ProPlayerSkill::class);
    }


    public function proPlayerOrders() {
        return $this->hasMany(ProPlayerOrder::class);
    }


    public function followers() {
        return $this->hasMany(PlayerFollower::class, 'following_id');
    }


    public function followings() {
        return $this->hasMany(PlayerFollower::class, 'follower_id');
    }
}
