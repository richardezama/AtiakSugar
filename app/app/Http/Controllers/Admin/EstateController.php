<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Estatetype;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use App\Models\Subscriptiontype;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;

class EstateController extends Controller
{

    public function list(){
        $pageTitle = 'Estates';
        $emptyMessage = 'No Records Found';
        $items = Estate::orderBy('id','desc')->with('type');
        $company_id= Auth::guard('admin')->user()->company_id;
        $user= Auth::guard('admin')->user()->id;
        if($company_id!=0)
        {
            $items=$items
            ->where("created_by",$user);
            //get things of that company

        }
       


        $items = $items->paginate(getPaginate());
      
        $types = Estatetype::orderBy('id','desc')->get();
        return view('admin.estates.list', compact('pageTitle', 'emptyMessage','items','types'));
    }


    public function store(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:estates,name,'.$request->name,
        ]);




        $sub=Subscription::where("user_id",Auth::guard("admin")->user()->id)
        ->where("expiry",">",time())->get();

        if(sizeof($sub)==0)
        {
            $notify[] = ['warning', 'Subscription Missing'];
            return redirect()->back()->withNotify($notify);
        }


       $package=Subscriptiontype::find($sub[0]->subscription_type);
        $amount=$package->price;
        $type=$package->id;
        $elimit=$package->estate_limit;
        $tlimit=$package->tenant_limit;
        
        $users = Estate::orderBy('id','desc');
        $company_id= Auth::guard('admin')->user()->company_id;
        $user= Auth::guard('admin')->user()->id;
        if($company_id!=0)
        {
            $users=$users
            ->where("company_id",$company_id)
            ->where("created_by",$user);
            //get things of that company

        }
        $users = $users->get();
        //check the type


        if(sizeof($users)>=$elimit)
        {
            $notify[] = ['warning', 'Package Limit Reached Maximum Estates Supported'.$elimit];
            return redirect()->back()->withNotify($notify);
        }











        $company_id= Auth::guard('admin')->user()->company_id;
        $estate = new Estate();
        $estate->company_id = $company_id;
        $estate->name = $request->name;
        $estate->location = $request->location;
        $estate->units = $request->units;
        $estate->estate_type = $request->estate_type;
        $estate->created_by =Auth::guard('admin')->user()->id;
        $estate->status = 1;
        $estate->save();
        $notify[] = ['success','Estate successfully Saved'];
        return back()->withNotify($notify);
    }
    public function update(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:estates,name,'.$id,
        ]);
        // return $request;
        $estate = Estate::find($id);
        $estate->name = $request->name;
        $estate->location = $request->location;
        $estate->units = $request->units;
        $estate->estate_type = $request->estate_type;
       
        $estate->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $estate = Estate::find($request->id);
       /* $estate->status = $estate->status == 1 ? 0 : 1;
        $estate->save();
        if($estate->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }*/
        $estate->delete();
        $notify[] = ['success', 'deleted successfully.'];
        return back()->withNotify($notify);
    }


    
    public function types(){
        $pageTitle = 'Estate Types';
        $emptyMessage = 'No Records Found';
        $items = Estatetype::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.estates.types', compact('pageTitle', 'emptyMessage','items'));
    }

    public function storetype(Request $request){
        $request->validate([
            'name'        => 
            'required|unique:estatetypes,name,'.$request->name,
        ]);
        $type = new Estatetype();
        $type->name = $request->name;
        $type->save();
        $notify[] = ['success','Estate Type successfully Saved'];
        return back()->withNotify($notify);
    }

    
    public function deletetype(Request $request){
        $request->validate(['id' => 'required|integer']);
        $estate = Estatetype::find($request->id);
        $estate->delete();
        //remove the units
        $units = Unit::where("estate_id",$request->id)->delete();
        $notify[] = ['success', 'deleted successfully.'];
        return back()->withNotify($notify);
    }
   
   
}
