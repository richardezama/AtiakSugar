<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeatLayout;
use App\Models\FleetType;
use App\Models\Booktype;
use App\Models\Make;
use App\Models\Book;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ManageFleetController extends Controller
{
    public function make(){
        $pageTitle = 'Vehicle Makes';
        $emptyMessage = 'No Records Found';
        $items = Make::orderBy('id','desc');
        /*$company_id= Auth::guard('admin')->user()->company_id;
        $user= Auth::guard('admin')->user()->id;
        if($company_id!=0)
        {
            $items=$items
            ->where("created_by",$user);
            //get things of that company

        }*/
        $items = $items->paginate(getPaginate());    
  
        return view('admin.vehicles.make', compact('pageTitle', 'emptyMessage','items'));
    }
    //store make
    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $estate = Estate::find($request->id);
       /* $estate->status = $estate->status == 1 ? 0 : 1;
        $estate->save();
        if($estate->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }*/
        $estate->delete();
        $notify[] = ['success', 'deleted successfully.'];
        return back()->withNotify($notify);
    }


    public function vehicleSearch(Request $request){
        $search = $request->search;
        $pageTitle = 'Vehicles - '. $search;
        $emptyMessage = 'No vehicles found';
        $fleetType = FleetType::where('status', 1)->orderBy('id','desc')->get();
        $vehicles = Vehicle::with('fleetType')->where('register_no', $search)->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.fleet.vehicles', compact('pageTitle', 'emptyMessage', 'vehicles', 'fleetType', 'search'));
    }

   

    public function disableenable(Request $request){
        $request->validate(['id' => 'required|integer']);

        $vehicle = Vehicle::find($request->id);
        $vehicle->status = $vehicle->status == 1 ? 0 : 1;
        $vehicle->save();
        if($vehicle->status == 1){
            $notify[] = ['success', 'Vehicle active successfully.'];
        }else{
            $notify[] = ['success', 'Vehicle disabled successfully.'];
        }
        return back()->withNotify($notify);
    }


    //store make
    public function storemake(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:makes,name,'.$request->name,
        ]);
        $estate = new Make();
        $estate->name = $request->name;
        $estate->status = 1;
        $estate->save();
        $notify[] = ['success','Make successfully Saved'];
        return back()->withNotify($notify);
    }

    
    public function updatemake(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:units,name,'.$id,
        ]);
        // return $request;
        $estate = Make::find($id);
        $estate->name = $request->name;
        $estate->save();
        $notify[] = ['success','Make Updated'];
        return back()->withNotify($notify);
    }
}
