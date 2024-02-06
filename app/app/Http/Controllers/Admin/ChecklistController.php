<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Make;
use App\Models\Type;
use App\Models\Models;
use App\Models\Checklisttype;
use App\Models\Checklist;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
class ChecklistController extends Controller
{

    public function types(){
        $pageTitle = 'Checklist Types';
        $emptyMessage = 'No Records Found';
        $items = Checklisttype::orderBy('id','desc')
        ->paginate(getPaginate());
        return view('admin.vehicles.checklisttypes', compact('pageTitle', 'emptyMessage',
        'items'));
    }
    public function storetype(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:units,name,'.$request->name,
        ]);
        $estate = new Checklisttype();
        $estate->name = $request->name;
        $estate->status = 1;
        $estate->save();
        $notify[] = ['success','Checklist successfully Saved'];
        return back()->withNotify($notify);
    }


    public function list(){
        $pageTitle = 'Checklist';
        $emptyMessage = 'No Records Found';
        $types = Checklisttype::orderBy('name','asc')->get();
        $items = Checklist::orderBy('id','desc')
        ->with('type')
        ->paginate(getPaginate());
        return view('admin.vehicles.checklists', compact('pageTitle', 'emptyMessage',
        'items','types'));
    }
    public function store(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:units,name,'.$request->name,
        ]);
        $estate = new Checklist();
        $estate->name = $request->name;
        $estate->checklist_type = $request->checklist_type;
        $estate->status = 1;
        $estate->save();
        $notify[] = ['success','Checklist successfully Saved'];
        return back()->withNotify($notify);
    }


    public function update(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:units,name,'.$id,
        ]);
        // return $request;
        $estate = Checklist::find($id);
        $estate->name = $request->name;
        $estate->checklist_type = $request->checklist_type;
        $estate->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $unit = Checklist::find($request->id);
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



    public function updatetype(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:units,name,'.$id,
        ]);
        // return $request;
        $estate = Checklisttype::find($id);
        $estate->name = $request->name;
        $estate->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabledtype(Request $request){
        $request->validate(['id' => 'required|integer']);
        $unit = Checklisttype::find($request->id);
        /*$estate->status = $estate->status == 1 ? 0 : 1;
        $estate->save();
        if($estate->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }*/
    
        $checklists=Checklist::where("checklist_type",$request->id)->get();
        foreach($checklists as $check)
        {
            $it=Checklist::find($request->id)->delete();
        }
        $unit->delete();

        $notify[] = ['success', 'active successfully.'];
          return back()->withNotify($notify);
    }

   
}
