<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoinTransaction extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $appends  = ['type_name'];


    static protected function boot() {
        parent::boot();

        static::creating(function($model) {
            $model->uuid = Str::uuid();
        });
    }


    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }


    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }


    public function getTypeNameAttribute() {
        $typeName = null;

        switch($this->type){
            case 0: $typeName = 'Topup'; break;
            case 1: $typeName = 'Order'; break;
            default: $typeName = 'Unknown';
        }

        return $typeName;
    }
}
