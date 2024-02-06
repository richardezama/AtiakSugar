<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Expensetype;
use App\Models\Checking;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; 

class CheckinController extends Controller
{
    public function checking(){
        $pageTitle = 'Active Checkings';
        $emptyMessage = 'No Records Found';
        $items = Checking::orderBy('id','desc')
        ->with('room','hotel')
        ->where("status",0)
        ->paginate(getPaginate());
        $estates = Estate::orderBy('id','desc')->with('type')
        ->where("estate_type",3)
        ->get();
        $types = Category::orderBy('id','desc')->get();
        return view('admin.products.checking', compact('pageTitle', 'emptyMessage',
        'items','types','estates'));
    }
    public function store(Request $request){   
        $request->validate([
            'name' => 
            'required',
            'telephone' => 
            'required',
            'startdate'        => 
            'required'
        ]);
        $admin=\Auth::guard("admin")->user();
        $checking = new Checking();
        $checking->name=$request->name;
        $checking->telephone=$request->telephone;
        $checking->room_id=$request->room_id;
        $checking->hotel_id=$request->hotel_id;
        $checking->created_by=$admin->id;
        $checking->start_date=$request->startdate;
        $checking->end_date=$request->enddate;
        $checking->status=0;
        $checking->save();


        $room =Unit::find($request->room_id);
        $room->status=1;
        $room->tenant_id=$checking->id;
        $room->update();


        $notify[] = ['success','Checked In'];
        return back()->withNotify($notify);
    }

    public function update(Request $request,$id){   
        $request->validate([
            'name' => 
            'required',
            'telephone' => 
            'required',
            'startdate'        => 
            'required'
        ]);



      
        $admin=\Auth::guard("admin")->user();
        $checking =  Checking::find($request->id);
        $room1 =Unit::find($checking->room_id);
        $room1->status=0;
        $room1->tenant_id=0;
        $room1->update();
        //release previous room

        $checking->name=$request->name;
        $checking->telephone=$request->telephone;
        $checking->room_id=$request->room_id;
        $checking->start_date=$request->startdate;
        $checking->end_date=$request->enddate;
        $checking->update();


        $room =Unit::find($request->room_id);
        $room->status=1;
        $room->tenant_id=$request->id;
        $room->update();

        $notify[] = ['success','Checked In'];
        return back()->withNotify($notify);
    }
    public function delete(Request $request){
        $request->validate(['id' => 'required|integer']);
        $checking = Checking::find($request->id);      
        $checking->delete();
        $notify[] = ['success', 'removed successfully.'];    
        return back()->withNotify($notify);
    }
}
