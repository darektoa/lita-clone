<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMedia extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function sender() {
        $this->belongsTo(User::class, 'sender_id');
    }
}
