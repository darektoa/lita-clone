<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlayerService extends Model
{
    use HasFactory;

    protected $appends = [
        'status_name',
    ];

    protected $guarded = ['id'];


    public function player() {
        return $this->belongsTo(Player::class);
    }


    public function service() {
        return $this->belongsTo(Service::class);
    }


    public function getStatusNameAttribute() {
        $statusName = null;

        switch($this->status) {
            case 0: $statusName = 'Pending'; break;
            case 1: $statusName = 'Rejected'; break;
            case 2: $statusName = 'Approved'; break;
            case 3: $statusName = 'Banned'; break;
            default: $statusName = 'Unknown';
        }

        return $statusName;
    }
}
