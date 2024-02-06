<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Make;
use App\Models\Models;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
class ModelsController extends Controller
{
    public function list(){
        $pageTitle = 'Models';
        $emptyMessage = 'No Records Found';
        $userid= Auth::guard('admin')->user()->id;
        $e=Make::get();
        $estates=[];
        foreach($e as $i)
        {
            array_push($estates,$i->id);
        }
        $items = Models::orderBy('id','desc')
        ->with('make')
        ->paginate(getPaginate());
        $estates = Make::orderBy('id','desc')
        ->get();
        return view('admin.vehicles.models', compact('pageTitle', 'emptyMessage',
        'items','estates'));
    }
    public function store(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:units,name,'.$request->name,
        ]);
        $estate = new Models();
        $estate->name = $request->name;
        $estate->make_id = $request->make_id;
        $estate->status = 1;
        $estate->save();
        $notify[] = ['success','Model successfully Saved'];
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
