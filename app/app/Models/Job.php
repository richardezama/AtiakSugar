<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
   // protected $connection = 'mysql2';
   public function completed(){
    return $this->belongsTo(Admin::class, 'completedby');
}

public function accepted(){
    return $this->belongsTo(Admin::class, 'acceptedby');
}

public function staff(){
    return $this->belongsTo(Admin::class, 'staffassigned');
}

}