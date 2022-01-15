<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerPost extends Model
{
    use HasFactory;

    protected $appends      = ['liked'];

    protected $guarded      = ['id'];

    protected $withCount    = ['playerPostLikes'];


    public function player() {
        return $this->belongsTo(Player::class);
    }


    public function postMedia() {
        return $this->hasMany(PlayerPostMedia::class);
    }


    public function playerPostLikes() {
        return $this->hasMany(PlayerPostLike::class);
    }


    public function getLikedAttribute() {
        $player = auth()->user()->player ?? null;
        $liked  = false;

        if($player && $player->playerPostLikes
            ->where('player_post_id', $this->id)
            ->first() 
        ) $liked = true;

        return $liked;
    }
}
