<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends  = ['type_name'];

    protected $guarded  = ['id'];


    public function withdrawAccounts() {
        return $this->hasMany(WithdrawAccount::class, 'transfer_id');
    }


    public function getTypeNameAttribute() {
        $type = null;

        switch($this->type) {
            case 0: $type   = 'BANK'; break;
            case 1: $type   = 'E-WALLET'; break;
            default: $type  = 'Unknown';
        }

        return $type;
    }
}
