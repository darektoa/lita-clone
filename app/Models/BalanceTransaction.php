<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BalanceTransaction extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];


    static protected function boot() {
        parent::boot();

        parent::creating(function($model) {
            $model->uuid = Str::uuid();
        });
    }


    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }


    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    
    public function typeName() {
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
