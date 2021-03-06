<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerPostMedia extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];


    public function playerPost() {
        return $this->belongsTo(PlayerPost::class);
    }
}
