<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DirectorateController extends Controller
{

    public function list(){
        $pageTitle = 'Directorates';
        $emptyMessage = 'No Records Found';
        $types = Division::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.divisions.list', compact('pageTitle', 'emptyMessage','types'));
    }


    public function store(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:callcategories,name,'.$request->name,
        ]);
        $steering = new Division();
        $steering->name = $request->name;
        $steering->status = 1;
        $steering->save();
        $notify[] = ['success','Call Category successfully Saved'];
        return back()->withNotify($notify);
    }

    public function updatetype(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:divisions,name,'.$id,
        ]);
        // return $request;
        $steering = Division::find($id);
        $steering->name = $request->name;
        $steering->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $steering = Division::find($request->id);
        $steering->status = $steering->status == 1 ? 0 : 1;
        //$steering->save();
        /*if($steering->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }*/
        $notify[] = ['success', 'Deleted.'];
        $steering->delete();
        return back()->withNotify($notify);
    }

   
}
