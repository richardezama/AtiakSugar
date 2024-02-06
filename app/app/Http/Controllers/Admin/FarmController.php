<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Farm;
use App\Models\Digging;
use App\Models\Estatetype;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class FarmController extends Controller
{
    
    public function list(){
        $pageTitle = 'Farms Management';
        $emptyMessage = 'No Records Found';
        $items = Farm::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.farms.index', compact('pageTitle', 'emptyMessage','items'));
    }


    public function maps(){
        $pageTitle = 'Fieldwork View';
        $emptyMessage = 'No Records Found';
         $items = Digging::orderBy('id','desc')
        ->with("farm","user")
        ->paginate(getPaginate());


        $json=[];
        foreach($items as $item)
        {

            $output['user']=$item->user->name;
            $output['farm']=$item->farm->name;
            $output['latitude']=$item->latitude;
            $output['longitude']=$item->longitude;
            $output['started']=$item->started;
            $output['stopped']=$item->stopped;
            $output['created_at']=date("d/m/Y h:m:s",strtotime($item->created_at));
            $output['finishedon']=date("d/m/Y h:m:s",$item->stopped_date);
            

            array_push($json,$output);
        }
     
        $json=json_encode($json);
        return view('admin.farms.maps', compact('pageTitle', 'emptyMessage','json'));
    }

    public function store(Request $request){
        $request->validate([
            'name'        => 
            'required|unique:farms,name,'.$request->name,
        ]);
        $type = new Farm();
        $type->name = $request->name;
        $type->latitude = $request->latitude;
        $type->longitude = $request->longitude;
        $type->save();
        $notify[] = ['success','Farm successfully Saved'];
        return back()->withNotify($notify);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'        => 
            'required',
        ]);
        $type = Farm::find($id);
        $type->name = $request->name;
        $type->latitude = $request->latitude;
        $type->longitude = $request->longitude;
        $type->save();
        $notify[] = ['success','Farm successfully Saved'];
        return back()->withNotify($notify);
    }

    
    public function delete(Request $request){
        $request->validate(['id' => 'required|integer']);
        $estate = Farm::find($request->id);
        $estate->delete();
        $notify[] = ['success', 'deleted successfully.'];
        return back()->withNotify($notify);
    }
   
   
}
