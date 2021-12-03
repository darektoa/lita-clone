<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $appends   = ['activity', 'activity_name'];

    protected $guarded   = ['id'];
    
    protected $withCount = ['followers', 'followings'];

    public $timestamps   = false;


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function coinPurchases() {
        return $this->hasMany(CoinPurchase::class);
    }


    public function playerPosts() {
        return $this->hasMany(PlayerPost::class);
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


    public function getActivityAttribute() {
        $skills     = ProPlayerSkill::where('player_id', $this->id);
        $online     = $skills->where('activity', 1)->count();
        $inOrder    = $skills->where('activity', 2)->count();

        if($inOrder) return 2;
        if($online) return 1;
        return 0;
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


    public function updateRate() {
        $avgRate = ProPlayerOrderReview::whereHas('proPlayerOrder', function($query) {
            $query->whereRelation('proPlayerSkill', 'player_id', $this->id);
        })->get()->avg('star');

        return $this->update(['rate' => $avgRate]);
    }
}
