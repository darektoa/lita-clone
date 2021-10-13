<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameTier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function game() {
        $this->belongsTo(Game::class);
    }
}
