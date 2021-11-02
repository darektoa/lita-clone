<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tier extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];


    public function proPlayerSkills() {
        return $this->hasMany(ProPlayerSkill::class);
    }
}
