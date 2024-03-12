<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\User;
use App\Models\Admin;
use App\Models\Make;
use App\Models\Models;
use App\Models\Assigned;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Subscriptiontype;
use App\Models\Unit;
use App\Models\Checklisttype;
use App\Models\Estate;
use App\Models\Repair;
use App\Models\Checklist;
use App\Models\Vehicle;
use App\Models\Servicetype;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Workdone;
use App\Models\Orderdetails;
use App\Models\Order;
use App\Models\Log;
use App\Models\Department;
use App\Models\Division;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use PDF;


class JobcardController extends Controller
{
    public function print($id)
    {
        $admin=Auth::guard("admin")->user();
        $role=$admin->role_id;
        $emptyMessage = 'No Records found';
        $pageTitle = 'Defect Form Details';     
        $users = Repair::where("id",$id)
        ->with('assigned','operator','equipment','diognised','completedby','issuedby','testedBy',
        'VerifiedBy','preparedby')
        ->paginate(getPaginate());
        $extra_diognosis=[];
        $status=0;
        $checklisttypes = Checklist::orderBy('name','asc')
        ->with('type')
        ->get();

        $user=$users[0];
        $intervention="";
        if($user->service_type=="3")
        {
            $intervention="Repair";
        }
        else if($user->service_type=="2")
        {
            $intervention="Service";
        }
        else if($user->service_type=="1")
        {
            $intervention="Intervention";
        }

         $vehicle=Vehicle::where("id",$users[0]->vehicle_id)->first();
         $checklistraw=json_decode($vehicle->checklist);
        foreach($users as $user)
        {
            $checklists=[];
            
            foreach($checklistraw as $check)
            {
                $c=Checklist::find($check->item);
                $output['name']=$c->name;
                $output['checked']=$check->checked;
                $output['recommendation']=$check->recommendation;
                array_push($checklists,$output);
            }
            if($user->extra_diognosis!="")
            {
            $extra_diognosis=json_decode($user->extra_diognosis);
            }
            $status=($user->status);

           
        }
        $orderdetails=[];
        $order=Order::where("repair_id",$id)->get();
        if(sizeof($order)>0)
        {
        $orderdetails=Orderdetails::where("order_number",$order[0]->order_number)
        ->with('product')
        ->get();
        }

        $workdone=Workdone::where("repair_id",$id);
        if($status==5 && $role==4)
        {
           //ignore for mechanics
        }
        else{
           // $workdone=$workdone->where("user_id",$admin->id);
        }
        $workdone=$workdone
        ->with('user')
        ->get();
        /*
        $employees = Admin::orderBy('name','asc')->get();
        return view('admin.vehicles.jobcard', compact('pageTitle','users','id','emptyMessage','checklists',
        'extra_diognosis','orderdetails','workdone','employees','checklisttypes','user','intervention'));

        */


        $staffs=Assigned::where("repair_id",$id)
        ->leftjoin("admins as a","a.id","assigneds.user_id")
        ->select("a.*")->get();

        $font_family = "'arnamu','sans-serif'";
        $data = [
            'workdone' => $workdone,
            'font_family' => $font_family,
            'spares' => $orderdetails,
            'staffs' => $staffs,
            
            'intervention' => $intervention,
            'job' => $user,
            'checklisttypes' => $checklisttypes,
            'workdone' => $workdone,
            'extra_diognosis' => $extra_diognosis,
            
        ];

        $pdf = PDF::loadView('admin.jobcard.print', $data);
	    return $pdf->download('jobcard-'.$user->reference_code.'.pdf');

    }
      

}
