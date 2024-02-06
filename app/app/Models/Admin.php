<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function role(){
        return $this->belongsTo(Role::class , 'role_id');
    }

    public function division(){
        return $this->belongsTo(Division::class , 'division_id');
    }
    public function department(){
        return $this->belongsTo(Department::class , 'department_id');
    }
}
