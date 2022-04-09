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


    static public function boot() {
        parent::boot();

        static::creating(function($data) {
            $data->id = Str::uuid();
        });
    }


    public function chat() {
        return $this->belongsTo(Chat::class);
    }
}
