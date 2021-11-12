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
        $this->autoExpired($value);
        $this->autoEnded($value);
        
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


    protected function autoExpired($status) {
        $createdAt      = $this->created_at;
        $expiryDuration = $this->expiry_duration;

        if($status === 0){
            if(now()->diffInMinutes($createdAt) >= $expiryDuration) {
                $this->update([
                    'status'     => 5,
                    'expired_at' => now(),
                ]);
            }

            CoinTransaction::create([
                'receiver_id'   => $this->player->user->id,
                'coin'          => $this->proPlayerSkill->price_permatch['coin'],
                'balance'       => $this->proPlayerSkill->price_permatch['balance'],
                'type'          => 3,
            ]);
        }
        
        return;
    }


    protected function autoEnded($status) {
        $updatedAt      = $this->updated_at;
        $playDuration   = $this->play_duration;

        if($status === 2)
            if(now()->diffInMinutes($updatedAt) >= $playDuration) {
                $this->update([
                    'status'    => 4,
                    'ended_at'  => now(),
                ]);
            }

        return;
    }
}
