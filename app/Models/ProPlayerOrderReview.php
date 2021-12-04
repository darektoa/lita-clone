<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlayerOrderReview extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];


    public function proPlayerOrder() {
        return $this->belongsTo(ProPlayerOrder::class);
    }


    public function user() {
        $relation = $this->belongsTo(ProPlayerOrder::class, 'pro_player_order_id');

        return $relation
            ->select('users.*')
            ->leftJoin('pro_player_skills', 'pro_player_orders.pro_player_skill_id', '=', 'pro_player_skills.id')
            ->leftJoin('players', 'pro_player_skills.player_id', '=', 'players.id')
            ->leftJoin('users', 'players.user_id', '=', 'users.id');
    }
}
