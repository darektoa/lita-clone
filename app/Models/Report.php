<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function reporter() {
        return $this->belongsTo(User::class);
    }


    public function reportable() {
        return $this->morphTo();
    }
    
    
    public function getTypeNameAttribute() {
        $typeName = null;

        switch($this->type) {
            case 1: $typeName = 'Player'; break;
            case 2: $typeName = 'Order'; break;
            case 3: $typeName = 'Chat'; break;
            default: $typeName = 'Unknown'; break;
        }

        return $typeName;
    }
}
