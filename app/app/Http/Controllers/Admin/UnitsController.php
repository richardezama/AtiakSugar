<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
class UnitsController extends Controller
{
    public function list(){
        $pageTitle = 'Units';
        $emptyMessage = 'No Records Found';
        $userid= Auth::guard('admin')->user()->id;
        $e=Estate::where("created_by",$userid)->get();
        $estates=[];
        foreach($e as $i)
        {
            array_push($estates,$i->id);
        }
        $items = Unit::orderBy('id','desc')
        ->where("created_by",$userid)
        ->with('estate')
        ->paginate(getPaginate());
        $estates = Estate::orderBy('id','desc')
        ->where("created_by",$userid)
        ->get();
        return view('admin.estates.units', compact('pageTitle', 'emptyMessage',
        'items','estates'));
    }
    public function store(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:units,name,'.$request->name,
        ]);
        $estate = new Unit();
        $estate->name = $request->name;
        $estate->estate_id = $request->estate_id;
        $estate->bathrooms = $request->bathrooms;
        $estate->bedrooms = $request->bedrooms;
        $estate->floor = $request->floor;
        $estate->rent = $request->rent;
        $estate->block = $request->block;
        $estate->created_by =Auth::guard('admin')->user()->id;
        $estate->nwsc = $request->nwsc;
        $estate->umeme = $request->umeme;
        $estate->status = 0;
        $estate->save();
        $notify[] = ['success','Unit successfully Saved'];
        return back()->withNotify($notify);
    }
    public function manage(Request $request, $id){
        $pageTitle = 'Manage Unit';
        $emptyMessage = 'No Records Found';
        $unit = Unit::find($id);
        $tenants = Tenant::orderBy('id','desc')->get();
        return view('admin.estates.manage', compact('pageTitle', 'emptyMessage',
        'tenants','unit'));
        
    }

    
    public function updatemanage(Request $request){
       
        $id=$request->id;         
        $tenant=$request->tenant;
        // return $request;
        $unit = Unit::find($id);
        if($tenant=="0")
        {
            $unit->status=0;
        }
        else{
            $unit->status=1;
            $ten = User::find($tenant);
            $ten->unit_id=$id;
            $ten->save();
        }
        $unit->tenent_id=$tenant;
        $unit->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }



    public function update(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:units,name,'.$id,
        ]);
        // return $request;
        $estate = Unit::find($id);
        $estate->name = $request->name;
        //$estate->estate_id = $request->estate_id;
        $estate->bathrooms = $request->bathrooms;
        $estate->bedrooms = $request->bedrooms;
        $estate->floor = $request->floor;
        $estate->block = $request->block;
        $estate->nwsc = $request->nwsc;
        $estate->umeme = $request->umeme;
        $estate->rent = $request->rent;
        $estate->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $unit = Unit::find($request->id);
        /*$estate->status = $estate->status == 1 ? 0 : 1;
        $estate->save();
        if($estate->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }*/
        $unit->delete();
        $notify[] = ['success', 'active successfully.'];
          return back()->withNotify($notify);
    }

   
}
