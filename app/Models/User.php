<?php

namespace App\Models;

use App\Traits\ChartTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use ChartTrait, HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $casts    = [
        'email_verified_at' => 'datetime',
    ];

    protected $guarded  = ['id'];

    protected $hidden   = [
        'password',
        'remember_token',
    ];

    protected $with     = [
        'gender'
    ];


    public function admin() {
        return $this->hasOne(Admin::class);
    }


    public function player() {
        return $this->hasOne(Player::class);
    }


    public function playerPosts() {
        return $this->hasManyThrough(PlayerPost::class, Player::class);
    }


    public function gender() {
        return $this->belongsTo(Gender::class);
    }


    public function coinSendingTransactions() {
        return $this->hasMany(CoinTransaction::class, 'sender_id');
    }
    

    public function coinReceivingTransactions() {
        return $this->hasMany(CoinTransaction::class, 'receiver_id');
    }


    public function balanceSendingTransactions() {
        return $this->hasMany(BalanceTransaction::class, 'sender_id');
    }


    public function balanceReceivingTransactions() {
        return $this->hasMany(BalanceTransaction::class, 'receiver_id');
    }


    public function chatSendingImages() {
        return $this->hasMany(ChatImage::class, 'sender_id');
    }


    public function loginTokens() {
        return $this->hasMany(LoginToken::class);
    }


    public function deviceIds() {
        return $this->hasMany(DeviceId::class);
    }


    public function withdrawAccounts() {
        return $this->hasMany(WithdrawAccount::class);
    }


    public function scopeToday($query) {
        return $query->whereDate('created_at', now());
    }
}
