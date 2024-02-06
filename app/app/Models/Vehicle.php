<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function make(){
        return $this->belongsTo(Make::class, 'make_id');
    }
    public function model(){
        return $this->belongsTo(Models::class, 'model_id');
    }

    public function operator(){
        return $this->belongsTo(Admin::class, 'operator_id');
    }
}