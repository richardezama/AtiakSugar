<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Make;
use App\Models\Type;
use App\Models\Models;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
class TypeController extends Controller
{
    public function list(){
        $pageTitle = 'Type';
        $emptyMessage = 'No Records Found';
        
        $items = Type::orderBy('id','desc')
        ->paginate(getPaginate());
      
        return view('admin.vehicles.types', compact('pageTitle', 'emptyMessage',
        'items'));
    }
    public function store(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:units,name,'.$request->name,
        ]);
        $estate = new Type();
        $estate->name = $request->name;
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
        $estate = Type::find($id);
        $estate->name = $request->name;
        $estate->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $unit = Type::find($request->id);
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
