<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $guarded = ['id'];
    public function calltype(){
        return $this->belongsTo(Calltype::class, 'calltype_id');
    }

    public function category(){
        return $this->belongsTo(Callcategory::class, 'category_id');
    }
    public function source(){
        return $this->belongsTo(Callsource::class, 'source_id');
    }

    public function priority(){
        return $this->belongsTo(Priorities::class, 'priority_id');
    }
    public function district(){
        return $this->belongsTo(District::class, 'district_id','districtid');
    }
    public function county(){
        return $this->belongsTo(County::class, 'county_id','countyid');
    }

    public function subcounty(){
        return $this->belongsTo(Sub_county::class, 'subcounty_id','subcountyid');
    }
    public function parish(){
        return $this->belongsTo(Parish::class, 'parish_id','parishid');
    }
    public function village(){
        return $this->belongsTo(Village::class, 'village_id');
    }

    public function division(){
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function status(){
        return $this->belongsTo(Callstatus::class, 'status_id');
    }

    public function job(){
        return $this->belongsTo(Job::class, 'id','call_id');
    }
}
