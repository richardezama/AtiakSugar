<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function assigned(){
        return $this->hasOne(Admin::class,'id', 'engineer_assigned');
    }

    public function preparedby(){
        return $this->hasOne(Admin::class,'id', 'created_by');
    }

    
    public function operator(){
        return $this->hasOne(Admin::class,'id', 'delivered_by');
    }
    public function equipment(){
        return $this->hasOne(Vehicle::class,'id', 'vehicle_id');
    }

    public function diognised(){
        return $this->hasOne(Admin::class,'id', 'diognised_by');
    }

    
    public function completedby(){
        return $this->hasOne(Admin::class,'id', 'completed_by');
    }

    
    
    public function issuedby(){
        return $this->hasOne(Admin::class,'id', 'issued_by');
    }


    public function testedBy(){
        return $this->hasOne(Admin::class,'id', 'tested_by');
    }

    public function VerifiedBy(){
        return $this->hasOne(Admin::class,'id', 'certified_by');
    }


    public function statuses(){
        return $this->hasOne(Status::class,'id', 'status');
    }

    public function persons_assigned(){
        return $this->hasMany(Assigned::class, "repair_id","id");
    }

}