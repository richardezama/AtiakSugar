<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Drugrequest;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
class DrugsController extends Controller
{
    public function list(){
        $pageTitle = 'Drug Requests';
        $emptyMessage = 'No Records Found';
        $userid= Auth::guard('admin')->user()->id;
        $e=Estate::where("created_by",$userid)->get();
        $estates=[];
        foreach($e as $i)
        {
            array_push($estates,$i->id);
        }
        $items = Drugrequest::orderBy('id','desc')
        ->where("patient_id",$userid)
        ->with('user')
        ->paginate(getPaginate());
        $estates = Estate::orderBy('id','desc')
        ->where("created_by",$userid)
        ->get();
        return view('admin.estates.drugs', compact('pageTitle', 'emptyMessage',
        'items','estates'));
    }
    public function store(Request $request){
        $userid= Auth::guard('admin')->user()->id;
        $request->validate([
            'location'        => 
            'required'
        ]);
        $estate = new Drugrequest();
        $estate->description = $request->description;
        $estate->type = $request->type;
        $estate->patient_id = $userid;
        $estate->status = 1;
        $estate->save();
        $notify[] = ['success','Request successfully Sent'];
        return back()->withNotify($notify);

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
            'status'        => 'required',
        ]);
        // return $request;
        $estate = Drugrequest::find($id);
        $estate->status = $request->status;
        $estate->comment = $request->comment;
        $estate->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $unit = Drugrequest::find($request->id);
        $unit->delete();
        $notify[] = ['success', 'deleted successfully.'];
          return back()->withNotify($notify);
    }






    public function sent(){
        $pageTitle = 'Drug Requests Sent';
        $emptyMessage = 'No Records Found';
        $userid= Auth::guard('admin')->user()->id;
        $e=Estate::where("created_by",$userid)->get();
        $estates=[];
        foreach($e as $i)
        {
            array_push($estates,$i->id);
        }
        $items = Drugrequest::orderBy('id','desc')
       // ->where("patient_id",$userid)
        ->with('user')
        ->paginate(getPaginate());
        $estates = Estate::orderBy('id','desc')
        ->where("created_by",$userid)
        ->get();
        return view('admin.estates.drugrequests', compact('pageTitle', 'emptyMessage',
        'items','estates'));
    }
}
