<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Drugrequest;
use App\Models\Orderdetails;
use App\Models\Order;
use App\Models\Log;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
class RequisitionController extends Controller
{
    public function list(){
        $pageTitle = 'Requisitions';
        $emptyMessage = 'No Records Found';
        $userid= Auth::guard('admin')->user()->id;
        $e=Estate::where("created_by",$userid)->get();
        $estates=[];
        foreach($e as $i)
        {
            array_push($estates,$i->id);
        }
        $items = Order::orderBy('id','desc')
        ->with('user')
        ->paginate(getPaginate());
        $estates = [];
        return view('admin.requisition.list', compact('pageTitle', 'emptyMessage',
        'items','estates'));
    }


    public function details($id)
    {
        $pageTitle = 'Requsisition Details';
        $item = Order::orderBy('id','desc')
        ->where("id",$id)
        ->with('user')->get()[0];
        //get the items

        $details=Orderdetails::where("order_number",$item->order_number)
        ->with('product')
        ->get();

        $logs=Log::where("requisition_id",$item->id)
        ->with('user')
        ->get();


        return view('admin.requisition.detail', compact('pageTitle','item','details','id','logs'));
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
       
       $order = Order::find($id);
        //get the current order

        $status=$request->status;
        $order_status=$order->status;
        if($status==0)
        {
            //rejected
            $order->status=5;
        }
        else{

            if($order_status==1 && $status==1)
            {
                //go to two
                $order->status = 2;
       
            }
            else if($order_status==2 && $status==1)
            {
                //go to two
                $order->status = 3;
            }
            else if($order_status==3 && $status==1)
            {
                //go to two
                $order->status = 4;
            }

        }

        $admin=Auth::guard("admin")->user();
        
      

        $order->comment = $request->comment;
        $order->save();


        $log=new Log();
        $log->comment="Spare Parts Requisitioned";
        $log->remark=$request->comment;
        $log->requisition_id=$order->id;
        $log->status=$order->status;
        $log->user_id=$admin->id;
        $log->save();

        $notify[] = ['success','Requisition Successfully Updated'];
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
