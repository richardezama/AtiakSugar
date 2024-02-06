<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Division;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DepartmentController extends Controller
{

    public function list(){
        $pageTitle = 'Departments';
        $emptyMessage = 'No Records Found';
        $types = Department::orderBy('id','desc')->with('division')->paginate(getPaginate());
        //divisions
        $divisions = Division::orderBy('id','desc')->get();

        return view('admin.divisions.department', compact('pageTitle', 'emptyMessage','types','divisions'));
    }


    public function store(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:callcategories,name,'.$request->name,
        ]);
        $steering = new Department();
        $steering->name = $request->name;
        $steering->division_id = $request->division;
        
        $steering->status = 1;
        $steering->save();
        $notify[] = ['success','Call Category successfully Saved'];
        return back()->withNotify($notify);
    }

    public function booktypeUpdate(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:booktypes,name,'.$id,
            'division_id'        => 'required',
        ]);
        // return $request;
        $steering = Department::find($id);
        $steering->name = $request->name;
        $steering->division_id = $request->division;
        $steering->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $steering = Department::find($request->id);
        $steering->status = $steering->status == 1 ? 0 : 1;
        $steering->delete();
        /*$steering->save();
        if($steering->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }*/
        $notify[] = ['success', 'Deleted.'];
        return back()->withNotify($notify);
    }

   
}
