<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerFollower extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function follower() {
        return $this->belongsTo(Player::class);
    }


    public function following() {
        return $this->belongsTo(Player::class);
    }
}
