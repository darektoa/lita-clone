<?php

namespace App\Models;

use App\Helpers\RandomCodeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $appends   = ['activity', 'activity_name'];

    protected $guarded   = ['id'];
    
    protected $withCount = ['followers', 'followings'];

    public $timestamps   = false;


    static protected function boot() {
        parent::boot();

        parent::creating(function($model) {
            if(!$model->referral_code)
                $model->referral_code = RandomCodeHelper::make();
        });
    }


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function playerPosts() {
        return $this->hasMany(PlayerPost::class);
    }


    public function playerPostLikes() {
        return $this->hasMany(PlayerPostLike::class);
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


    public function proPlayerServices() {
        return $this->hasMany(ProPlayerService::class);
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


    public function getFollowedAttribute() {
        $player     = auth()->user()->player ?? null;
        $followed   = false;

        if($player && $player->followings
            ->where('following_id', $this->id)
            ->first()
        ) $followed = true;

        return $followed;
    }


    public function updateRate() {
        $avgRate = ProPlayerOrderReview::whereHas('proPlayerOrder', function($query) {
            $query->whereRelation('proPlayerSkill', 'player_id', $this->id);
        })->get()->avg('star');

        return $this->update(['rate' => $avgRate]);
    }
}
