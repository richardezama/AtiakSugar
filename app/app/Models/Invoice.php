<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
    //scope
    public function scopePending(){
        return $this->where('status', 0);
    }
    
    public function scopePaid(){
        return $this->where('status', 1);
    }
    

    public function unit(){
        return $this->belongsTo(Unit::class,  'unit_id');
    }
    public function estate(){
        return $this->belongsTo(Estate::class,  'estate_id');
    }

    public function user(){
        return $this->belongsTo(User::class,  'user_id');
    }
   
}
