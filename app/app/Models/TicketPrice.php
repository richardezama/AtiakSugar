<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPrice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function fleetType(){
        return $this->belongsTo(FleetType::class);
    }

    public function car(){
        return $this->belongsTo(Vehicle::class , 'vehicle_id');
    }

    public function prices(){
        return $this->hasMany(TicketPriceByStoppage::class);
    }
    public function company(){
        return $this->belongsTo(BusCompanies::class , 'company_id');
    }
}
