<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public $timestamps = false;


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function coinPurchases() {
        return $this->hasMany(CoinPurchase::class);
    }

    public function proPlayerSkills() {
        return $this->hasMany(ProPlayerSkill::class);
    }
}
