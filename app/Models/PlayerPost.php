<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerPost extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];


    public function player() {
        return $this->belongsTo(Player::class);
    }


    public function postMedia() {
        return $this->hasMany(PlayerPostMedia::class);
    }
}