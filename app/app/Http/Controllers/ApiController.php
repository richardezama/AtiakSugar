<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Callcategory;
use App\Models\Callsource;
use App\Models\Priorities;
use App\Models\Department;
use App\Models\District;
use App\Models\County;
use App\Models\Unit;
use App\Models\Models;
use App\Models\Sub_county;
use App\Models\Parish;
use App\Models\Division;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ApiController extends Controller
{


   
    public function getcounties($district){
        $list = County::orderBy('countyname','asc')->where('districtid',$district)->get();
        return $list;
    }

    public function getsubcounties($county){
        $list = Sub_county::orderBy('subcountyname','asc')->where('countyid',$county)->get();
        return $list;
    }

    public function getparishes($subcounty){
        $list = Parish::orderBy('parishname','asc')->where('subcountyid',$subcounty)->get();
        return $list;
    }  

    public function getvillages($parish){
        $list = Village::orderBy('villagename','asc')->where('parishid',$parish)->get();
        return $list;
    }  

    public function getdepartments($division){
        $list = Department::orderBy('name','asc')->where('division_id',$division)->get();
        return $list;
    }  

    public function getUnits($division){
        $list = Unit::orderBy('name','asc')->where('estate_id',$division)->get();
        return $list;
    }  



    public function districts(){
        $list = District::orderBy('districtname','asc')->get();
        return $list;
    }
    


    public function getModels($division){
        $list = Models::orderBy('name','asc')->where('make_id',$division)->get();
        return $list;
    }  

   
}
