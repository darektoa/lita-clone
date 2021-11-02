<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlayerSkill extends Model
{
    use HasFactory;

    protected $appends  = ['status_name'];

    protected $guarded  = ['id'];


    public function player() {
        return $this->belongsTo(Player::class);
    }


    public function game() {
        return $this->belongsTo(Game::class);
    }


    public function tier() {
        return $this->belongsTo(Tier::class);
    }


    public function proPlayerSkillScreenshots() {
        return $this->hasMany(ProPlayerSkillScreenshot::class);
    }


    public function getStatusNameAttribute() {
        $statusName = null;

        switch($this->status) {
            case 0: $statusName = 'Pending'; break;
            case 1: $statusName = 'Rejected'; break;
            case 2: $statusName = 'Approved'; break;
            default: $statusName = 'Unknown';
        }

        return $statusName;
    }
}
