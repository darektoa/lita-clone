<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public $timestamps = false;


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function loginTokens() {
        return $this->hasManyThrough(LoginToken::class, User::class);
    }
}
