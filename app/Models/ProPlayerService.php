<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlayerService extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function player() {
        return $this->belongsTo(Player::class);
    }


    public function service() {
        return $this->belongsTo(Service::class);
    }
}
