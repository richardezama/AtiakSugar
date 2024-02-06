<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Callsource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CallsourceController extends Controller
{

    public function list(){
        $pageTitle = 'Call Source';
        $emptyMessage = 'No Records Found';
        $types = Callsource::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.calls.source', compact('pageTitle', 'emptyMessage','types'));
    }


    public function storebooktype(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:callcategories,name,'.$request->name,
        ]);
        $steering = new Callsource();
        $steering->name = $request->name;
        $steering->status = 1;
        $steering->save();
        $notify[] = ['success','Call Category successfully Saved'];
        return back()->withNotify($notify);
    }

    public function booktypeUpdate(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:booktypes,name,'.$id,
        ]);
        // return $request;
        $steering = Callsource::find($id);
        $steering->name = $request->name;
        $steering->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $steering = Callsource::find($request->id);
        $steering->status = $steering->status == 1 ? 0 : 1;
        $steering->save();
        if($steering->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }
        return back()->withNotify($notify);
    }

   
}
