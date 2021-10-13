<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function gameRole() {
        $this->hasMany(GameRole::class);
    }


    public function gameTier() {
        $this->hasMany(GameTier::class);
    }
}
