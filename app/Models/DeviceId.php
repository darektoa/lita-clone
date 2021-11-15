<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceId extends Model
{
    use HasFactory;

    protected $appends  = ['status_name'];

    protected $guarded  = ['id'];


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function getStatusNameAttribute() {
        $status = null;

        switch($this->status) {
            case 0: $status = 'Unsubscibed'; break;
            case 1: $status = 'Subscribed'; break;
            default: $status = 'Unknown';
        }

        return $status;
    }
}
