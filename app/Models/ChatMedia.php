<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatMedia extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $incrementing = false;


    public function sender() {
        $this->belongsTo(User::class, 'sender_id');
    }


    public function receiver() {
        $this->belongsTo(User::class, 'receiver_id');
    }
}
