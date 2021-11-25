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
}
