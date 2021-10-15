<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];


    public function gameRoles() {
        return $this->hasMany(GameRole::class);
    }


    public function gameTiers() {
        return $this->hasMany(GameTier::class);
    }


    public function proPlayerSkill() {
        return $this->hasMany(ProPlayerSkill::class);
    }
}
