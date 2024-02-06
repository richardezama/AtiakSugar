<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Digging extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function farm(){
        return $this->belongsTo(Farm::class, 'farm_id');
    }
    public function user(){
        return $this->belongsTo(Admin::class, 'user_id');
    }
}
