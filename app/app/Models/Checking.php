<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checking extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function hotel(){
        return $this->belongsTo(Estate::class, 'hotel_id');
    }
    public function room(){
        return $this->belongsTo(Unit::class, 'room_id');
    }
}