<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function assigned(){
        return $this->belongsTo(Admin::class, 'engineer_assigned');
    }

    public function preparedby(){
        return $this->belongsTo(Admin::class, 'created_by');
    }

    
    public function operator(){
        return $this->belongsTo(Admin::class, 'delivered_by');
    }
    public function equipment(){
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function diognised(){
        return $this->hasOne(Admin::class,"id",'diognised_by');
    }

    
    public function completedby(){
        return $this->hasOne(Admin::class, "id",'completed_by');
    }

    
    
    public function issuedby(){
        return $this->belongsTo(Admin::class, 'issued_by');
    }


    public function testedBy(){
        return $this->belongsTo(Admin::class, 'tested_by');
    }

    public function VerifiedBy(){
        return $this->belongsTo(Admin::class, 'certified_by');
    }

    public function statuses(){
        return $this->belongsTo(Status::class, 'status');
    }

    public function persons_assigned(){
        return $this->hasmany(Assigned::class, "repair_id","id");
    }


    

}