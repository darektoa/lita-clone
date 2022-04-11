<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Support\Str;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded   = [];

    public $incrementing = false;


    static protected function boot() {
        parent::boot();

        parent::creating(function($data) {
            if(!$data->id) $data->id = Str::uuid();
        });
    }


    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }


    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }


    public function media() {
        return $this->hasOne(ChatMedia::class);
    }
}
