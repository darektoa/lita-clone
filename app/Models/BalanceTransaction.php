<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];


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
            default: $typeName = 'Unknown';
        }

        return $typeName;
    }
}
