<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlayerOrder extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $appends  = ['status', 'status_name'];


    public function player() {
        return $this->belongsTo(Player::class);
    }

    
    public function proPlayerSkill() {
        return $this->belongsTo(ProPlayerSkill::class);
    }


    public function getStatusAttribute($value) {
        if($value === 0)
            if(now()->diffInMinutes($this->created_at) >= $this->expiry_duration)
                $this->update(['status' => 5]);

        return $value;
    }


    public function getStatusNameAttribute() {
        $statusName = null;

        switch($this->status) {
            case 0: $statusName = 'Pending'; break;
            case 1: $statusName = 'Rejected'; break;
            case 2: $statusName = 'Approved'; break;
            case 3: $statusName = 'Canceled'; break;
            case 4: $statusName = 'Ended'; break;
            case 5: $statusName = 'Expired'; break;
            default: $statusName = 'Unknown';
        }

        return $statusName;
    }
}
