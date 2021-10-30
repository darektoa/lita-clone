<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $hidden   = [
        'password',
        'remember_token',
    ];

    protected $guarded  = ['id'];

    protected $with     = [
        'player',
        'admin',
        'gender'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function admin() {
        return $this->hasOne(Admin::class);
    }


    public function player() {
        return $this->hasOne(Player::class);
    }


    public function gender() {
        return $this->belongsTo(Gender::class);
    }

    
    public function loginTokens() {
        return $this->hasMany(LoginToken::class);
    }
}
