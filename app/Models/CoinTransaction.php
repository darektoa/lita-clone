<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoinTransaction extends Model
{
    use HasFactory;

    protected $appends  = ['type_name'];

    protected $guarded  = ['id'];

    protected $casts    = [
        'invoice'   => 'json',
        'detail'    => 'object',
    ];


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
            case 2: $typeName = 'Refund'; break;
            default: $typeName = 'Unknown';
        }

        return $typeName;
    }
}
