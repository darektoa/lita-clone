<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function reporter() {
        return $this->belongsTo(User::class);
    }


    public function reportable() {
        return $this->morphTo();
    }
}
