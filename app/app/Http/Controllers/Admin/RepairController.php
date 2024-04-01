<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\User;
use App\Models\Admin;
use App\Models\Make;
use App\Models\Models;
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
use App\Models\Assigned;
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


class RepairController extends Controller
{
    public function list(Request $request)
    {
        //all repairs
        $pageTitle = 'All Requests';
        $emptyMessage = 'No Records found';
        $users = Repair::orderBy('id','desc')
        ->with('assigned','operator','equipment','statuses','persons_assigned');
        if(isset($request->search))
        {
$users=$users->where("reference_number",'LIKE',$request->search);
        }
        if(isset($request->status))
        {
$users=$users->where("status",'=',$request->status);
        }


        $admin=Auth::guard("admin")->user();
        $role=$admin->role_id;

        
        if(($role==4))
        {
            //operator
            $users=$users->whereIn("status",[1,3])
            ->leftjoin("assigneds as a","a.repair_id","repairs.id")
            ->where("a.completed",'=',0)
            ->where("a.user_id",'=',$admin->id)
            //or where status is three and one of these guyz is assigned
            ->orWhere([
                ['status','=',3],
                ['a.completed','=',1],
                ['a.user_id','=',$admin->id]
            ]);
            //here anyone assigned
        }
        else if(($role==5))
        {
            //ass workshop manager //ass 
            $users=$users->whereIn("status",[2,1,6]);
        }

        else if(($role==3))
        {
            //workshop manager
           
            $users=$users->whereIn("status",[2,4]);
        }
        
        else if(($role==6))
        {
            //stores
            $users=$users->where("status",'=',5);
        }
        
        /*
        else if(($role==7))
        {
            //tester
            $users=$users->where("status",'=',7);
        }

        else if(($role==8))
        {
            //certify
            $users=$users->where("status",'=',8);
        }*/
        //roles
        $users = $users
        ->SELECT("repairs.*")
        ->orderBy("repairs.id","desc")
        ->paginate(getPaginate()); 
        //return $users;
        return view('admin.vehicles.repairs', compact('pageTitle', 'emptyMessage', 'users'));
    }



    //printing job cards report
    public function jobcards(Request $request)
    {
        //all repairs
        $pageTitle = 'All Requests';
        $emptyMessage = 'No Records found';
        $users = Repair::orderBy('id','desc')
        ->with('assigned','operator','equipment','statuses');
        if(isset($request->search))
        {
$users=$users->where("reference_number",'LIKE',$request->search);
        }
        if(isset($request->status))
        {
$users=$users->where("status",'=',$request->status);
        }


        $admin=Auth::guard("admin")->user();
        $role=$admin->role_id;
        $users = $users
        ->orderBy("id","desc")
        ->paginate(getPaginate()); 
        //return $users;
        return view('admin.vehicles.repairsprint', compact('pageTitle', 'emptyMessage', 'users'));
    }


  
    

     

     public function create()
     {
         $pageTitle = 'Equipment Repair';     
         $roles = Role::orderBy('id','desc')->get();
         $divisions = Division::orderBy('name','desc')->get();
         $departments = Department::orderBy('name','desc')->get();
         $districts=  District::orderBy('districtid','desc')->get();
         $units=[];
         $company_id= Auth::guard('admin')->user()->company_id;
       
         $estates = Estate::orderBy('name','desc')
         ->where("company_id",$company_id)
         ->get();

         $makes = Make::orderBy('id','desc')->get();
         $models = Models::orderBy('name','desc')->get();
         $users = Admin::orderBy('name','asc')
         ->where("role_id","=",4)
         ->where("available",1)
         ->get();
         $vehicles = Vehicle::orderBy('name','asc')
         ->with('make','model')
         ->get();

         $checklists = Checklist::orderBy('name','asc')->get();
         $service_types=Servicetype::orderBy("name","asc")->get();

         $operators = Admin::orderBy('name','asc')
         ->where("role_id","=",2)
         ->where("available",1)
         ->get();


         return view('admin.vehicles.crm', compact('pageTitle','roles','divisions',
         'departments','districts','estates','units','models','makes','users','vehicles','checklists','service_types','operators'));
     }

     public function storedraft(Request $request)
    {
        try{
            DB::beginTransaction();
        $request->validate([
           
            'delivered_by.required'=>'Delivery Man required',
            'vehicle_id.required'=>'Select Equipment  required'
            
        ]);
        //include last serviced on
        $pnr_number = getTrx(10);
        $admin=Auth::guard("admin")->user();
        $repair=Repair::find(trim($request->id));
        $repair->odometer_in=$request->odometer_in;
        $repair->vehicle_id=$request->vehicle_id;
        $repair->delivered_by=$request->delivered_by;
        $repair->defects_reported=$request->defects_reported;
        $repair->status=1;
        $repair->draft=0;
        $repair->engineer_assigned=$engineer;
        $repair->service_type=$request->service_type; 
        $repair->reference_number=$pnr_number;
        $repair->created_by=$admin->id;
        $repair->save();
        
        //save Log
        $log=new Log();
        $log->comment="Field Request Completed";
        $log->repair_id=$repair->id;
        $log->status=1;
        $log->user_id=$admin->id;
        $log->save();
        DB::commit();
        $notify[] = ['success', 'New Job Successfully Added'];
       return redirect()->back()->withNotify($notify);
     
    }

    catch(Exception $e)
    {
        DB::rollBack();
      $notify[] = ['danger', $e->getMessage()];
      return redirect()->back()->withNotify($notify);
    
    }
    }


     


    //create
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
        $request->validate([
          
           
            'delivered_by.required'=>'Delivery Man required',
            'vehicle_id.required'=>'Select Equipment  required'
            
        ]);
        //include last serviced on
        $pnr_number = getTrx(10);
        $admin=Auth::guard("admin")->user();
        $repair=new Repair();
        $repair->odometer_in=$request->odometer_in;
        $repair->vehicle_id=$request->vehicle_id;
        $repair->delivered_by=$request->delivered_by;
        $repair->defects_reported=$request->defects_reported;
        $repair->status=1;
        $repair->draft=0;
        $repair->service_category=$request->service_category; 
        $repair->reference_number=$pnr_number;
        $repair->created_by=$admin->id;
        if(isset($request->service_type))
        {
            $repair->service_type=$request->service_type;
        
        }
       // $repair->checklists=json_encode($request->checklist); 
        $repair->save();

        //save the checklists
/*        foreach($request->checklist as $checklist)
        {
            //include them here
        }*/

         //save Log

         $engineer=0;
        foreach($request->engineer_assigned as $eng)
        {
            $ass=new Assigned();
            $ass->user_id=$eng;
            $ass->repair_id=$repair->id;
            $ass->completed=0;
            $ass->save();
            $engineer=$eng;
            $repair->engineer_assigned=$request->engineer_assigned; 
        }

        $repair->update();
         $log=new Log();
         $log->comment="New Defect Reported";
         $log->repair_id=$repair->id;
         $log->status=1;
         $log->user_id=$admin->id;
         $log->save();


        DB::commit();
        $notify[] = ['success', 'Record Successfully Added'];
       return redirect()->back()->withNotify($notify);
     
    }

    catch(Exception $e)
    {
        DB::rollBack();
      $notify[] = ['danger', $e->getMessage()];
      return redirect()->back()->withNotify($notify);
    
    }
    }

    
    public function jobcard($id)
    {
        $admin=Auth::guard("admin")->user();
        $role=$admin->role_id;
        $emptyMessage = 'No Records found';
        $pageTitle = 'Defect Form Details';     
        $users = Repair::where("id",$id)
        ->with('assigned','operator','equipment','diognised','completedby','issuedby','testedBy','VerifiedBy')
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
            $extra_diognosis=json_decode($user->extra_diognosis);
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

        $employees = Admin::orderBy('name','asc')->get();
        return view('admin.vehicles.jobcard', compact('pageTitle','users','id','emptyMessage','checklists',
        'extra_diognosis','orderdetails','workdone','employees','checklisttypes','user','intervention'));
    }
   


    public function details($id)
    {
        $admin=Auth::guard("admin")->user();
        $role=$admin->role_id;
        $emptyMessage = 'No Records found';
        $pageTitle = 'Defect Form Details';  

        $users = Repair::where("id",$id)
        ->with('assigned','operator','equipment','diognised',
        'completedby','issuedby','persons_assigned')
        ->paginate(getPaginate());
        $extra_diognosis=[];
        $status=0;
        $checklisttypes = Checklist::orderBy('name','asc')
        ->with('type')
        ->get();
        $draft=0;

         $vehicle=Vehicle::where("id",$users[0]->vehicle_id)->first();
         $checklistraw=json_decode($vehicle->checklist);
         $chkk=true;
         if($vehicle->checklist=="")
         {
           return $chkk=false;
         }
        foreach($users as $user)
        {
            $checklists=[];
           if($chkk)
           {
            foreach($checklistraw as $check)
            {

                $c=Checklist::find($check->item);
                $output['name']=$c->name;
                $output['checked']=$check->checked;
                $output['recommendation']=$check->recommendation;
                array_push($checklists,$output);
            }
        }
       
            $extra_diognosis=json_decode($user->extra_diognosis);
            $status=($user->status);
            $draft=$user->draft;
        }
        $logs=Log::where("repair_id",$id)->get();
        $orderdetails=[];
        $order=Order::where("repair_id",$id)->get();
        if(sizeof($order)>0)
        {
        $orderdetails=Orderdetails::where("order_number",$order[0]->order_number)
        ->leftjoin("product_stocks as p","p.product_id","orderdetails.product_id")
        ->select("orderdetails.*",
        DB::raw('sum(p.quantity) as available')
        )
        ->with('product')
        ->groupBy("orderdetails.issued","orderdetails.id")
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
        $employees = Admin::orderBy('name','asc')->get();

        


        $names=[];
        $ids=[];

        $mechanics_assigned="";
        
        foreach($users as $user)
        {
            $split=json_decode($user->persons_assigned);
            foreach($split as $assigned)
            {
 
            array_push($ids,$assigned->user_id);
            }
        }

        $uu=Admin::wherein("id",$ids)->get();
        $y=sizeof($uu);
        $p=1;
        foreach($uu as $u)
        {
            array_push($names,$u->name);
            if($p!=$y)
            {
                $mechanics_assigned.=$u->name.",";
            }
            else{
                $mechanics_assigned.=$u->name;
            }

            $p++;
        }


        $staffs_assigned=Assigned::where("repair_id",$id)
        ->leftjoin("admins as a","a.id","assigneds.user_id")
        ->select("a.*")->get();





        if($draft==1)
        {
            $pageTitle = 'Complete Draft Defect';    
            $vehicles = Vehicle::orderBy('name','asc')
            ->with('make','model')
            ->get();
   
            //$employees = Admin::orderBy('name','asc')->get();
            return view('admin.vehicles.finishdraft', compact('pageTitle','users','id','emptyMessage','checklists',
            'extra_diognosis','orderdetails','workdone','employees','checklisttypes','vehicles','logs',
            'mechanics_assigned','staffs_assigned'));
        }
        else{
        return view('admin.vehicles.repairdetails', compact('pageTitle','users','id','emptyMessage','checklists',
        'extra_diognosis','orderdetails','workdone','employees','checklisttypes','logs',
        'mechanics_assigned','staffs_assigned'));
        }
    }
   


    public function update(Request $request, $id)
    {
        $request->validate([
          
           
            'name.required'=>'Name required',
            'make_id.required'=>'Make  required'
            
        ]);
        //include last serviced on
        $vehicle=Vehicle::findorFail($id);
        $vehicle->name=$request->name;
        $vehicle->chasis=$request->chasis;
        $vehicle->make_id=$request->make_id;
        $vehicle->model_id=$request->model_id;
        $vehicle->number_plate=$request->number_plate;
        $vehicle->engine_no=$request->engine_no;
        $vehicle->update();

        $notify[] = ['success', 'Equipment Details Updated'];
        return redirect()->back()->withNotify($notify);
    }



    public function approvespares(Request $request, $id)
    {
        $request->validate([
            'comment.required'=>'Name required',
            
        ]);
        $admin=Auth::guard("admin")->user();
        $repair=Repair::findorFail($id);
        $repair->spares_approvedby=$admin->id;
        $repair->spares_approval_comment=$request->comment;
        $repair->spares_approvedon=time();
        $repair->status=5;
        $repair->update();
        $notify[] = ['success', 'Spare Parts Approved Successfully'];
        //return redirect()->back()->withNotify($notify);
        return redirect()->route('admin.repair.list',0)->withNotify($notify);
    }


    
    public function workdone(Request $request, $id)
    {
        $request->validate([
            'comment.required'=>'Name required',
        ]);
        $admin=Auth::guard("admin")->user();
        $x=0;

        foreach($request->workdone as $w)
        {
        $workdone= new Workdone();
        $workdone->description=$w;
        $workdone->time_started=$request->time_started[$x];
        $workdone->time_finished=$request->time_finished[$x];
        $workdone->hours_worked=$request->hours_worked[$x];
        $workdone->repair_id=$id;
        $workdone->user_id=$request->operator[$x];
        $workdone->save();
        $x++;
        }
        $notify[] = ['success', 'Spare Parts Approved Successfully'];
        return redirect()->back()->withNotify($notify);
    }


    //diognosis

    public function diognosis(Request $request, $id)
    {
        $request->validate([
            //'diagnosis.required'=>'diagnosis required', 
        ]);
        try{
        DB::beginTransaction();
        //include last serviced on
        $admin=Auth::guard("admin")->user();
        $repair=Repair::findorFail($id);
        $repair->extra_diognosis=json_encode($request->diagnosis);
        $repair->diognised_on=time();
        $repair->diognised_by=$admin->id;
        //logic of can submit
        $assigned=Assigned::where("repair_id",$id)
        ->where("user_id",$admin->id)->first();
        $assigned->completed=1;
        $assigned->update();

        if($assigned==null)
        {
            $notify[] = ['warning', 'You arent assigned to handle this job'];
            //return redirect()->back()->withNotify($notify);
        }


        //check if there is anything pending

        $pending=Assigned::where("repair_id",$id)
        ->where("completed",0)
        ->get();

        if(sizeof($pending)>0)
        {

        }
        else{
            $repair->status=2;
            //move to the next stage
        }


        //pending approval
 

      
        $log=new Log();
        $log->comment="Diognosis Completed";
        $log->repair_id=$id;
        $log->status=2;
        $log->user_id=$admin->id;
        $log->save();


        $x=0;
        $json=[];

        $cancheck=false;
        foreach($request->ids as $ids)
        {
           // print ($ids);
            $item=$request->ids[$x];
            $checked="";
            if(isset($request->selected_item[$x]))
            {
                 $checked=$request->selected_item[$x];
            }

            $recommendation="";
            
            if(isset($request->recommendation[$x]))
            {
                $recommendation=$request->recommendation[$x];
            }
            else{
                $recommendation="";
            }
           // $recommendation=$request->recommendation[$x];
            if(!$checked=="")
            {
            $output['item']=$item;
            $output['checked']=$checked;
            $output['recommendation']=$recommendation;
            array_push($json,$output);
            }
            $x++;
            $cancheck=true;
        }
        $repair->update();

        if($cancheck)
        {
        $vehicle=Vehicle::find($repair->vehicle_id);
        $vehicle->checklist=json_encode($json);
        $vehicle->state=0;
        $vehicle->save();

        }
        DB::commit();
        /*
        $notify[] = ['success', 'Diognosis Complete'];
        //return redirect()->back()->withNotify($notify);
        return redirect()->route('admin.repair.list',0)->withNotify($notify);
        */

        $notify[] = ['success', 'Diognosis Complete'];
        //return redirect()->back()->withNotify($notify);
        return redirect()->route('admin.repair.list',0)->withNotify($notify);
       
    
    }

    catch(Exception $e)
    {
        DB::rollBack();
       $notify[] = ['danger', $e->getMessage()];
      return redirect()->back()->withNotify($notify);
    
    }
        
    }
    public function approvedefects(Request $request, $id)
    {
        $request->validate([
          
            'diagnosis.required'=>'diagnosis required',
            
        ]);
        //include last serviced on
        $admin=Auth::guard("admin")->user();
        $repair=Repair::findorFail($id);
        $repair->approved_on=time();
        $repair->approved_by=$admin->id;
        $repair->approved_by=$admin->id;
        $repair->approved_remark=$request->remark;
        
        
        $repair->status=3;
        //pending approval
        $repair->update();

      
        $log=new Log();
        $log->comment="Defect Form approved by worksop manager";
        $log->repair_id=$id;
        $log->remark=$request->remark;
        $log->user_id=$admin->id;
        $log->save();
        $notify[] = ['success', 'Defect Form Approved'];
          //return redirect()->back()->withNotify($notify);
        return redirect()->route('admin.repair.list',0)->withNotify($notify);
    }
    
    public function issuestock(Request $request, $id)
    {
        $request->validate([
          
            'issued.required'=>'diagnosis required',
            
        ]);
        try{
            DB::beginTransaction();
        $admin=Auth::guard("admin")->user();
        $repair=Repair::findorFail($id);
       $repair->issued_by=$admin->id;
       $repair->issued_on=time();
       if(isset($request->final)=="Issue & Submit")
       {
        //yes send to next stage
        $repair->status=6;
        $log=new Log();
        $log->comment="Spare Parts Issued by Stores";
        $log->remark=$request->remark;
        $log->repair_id=$id;
        $log->status=3;
        $log->user_id=$admin->id;
        $log->save();
       
       }
       else{
        $log=new Log();
        $log->comment="Spare Parts Partially Issued by Stores";
        $log->remark=$request->remark;
        $log->repair_id=$id;
        $log->status=3;
        $log->user_id=$admin->id;
        $log->save();
       }
        
        //update order items
        $x=0;
        foreach($request->issued as $issue)
        {
            //get the details id
            $orderline=$request->ids[$x];
            $line=Orderdetails::findOrFail($orderline);

            $prevbal=$line->issued;
            $line->issued=$issue;
            $line->issued_by=$admin->id;
            $line->issued_on=time();
           
            //reduce stock
            $product=Product::findOrFail($line->product_id);
            $product->stock_balance=$product->stock_balance-$line->quantity;
          

            //start with getting top 2 where product in etc
            //do the same for warehousing
            $warehouse_balances=ProductStock::where("product_id",$line->product_id)
            ->where("quantity",">",0)
            ->orderBy("quantity","asc")
            ->get();

            //can go to next
            $can_next_warehouse=true;
            $y=0;
            //up to the nth warehouse
            foreach($warehouse_balances as $warehouse)
            {
                if($can_next_warehouse)
                {
                $pstock=ProductStock::findOrFail($warehouse->id);
                $qty=$warehouse->quantity;
                /*if($qty<$line->quantity && sizeof($warehouse_balances)>1)
                {
                    if($prevbal=="")
                    {
                    //fully use up stock
                    
                    $pstock->quantity=$pstock->quantity-$pstock->quantity;
                    $pstock->update();
                    //then go to next with balance bf
                    $next=$line->quantity-$qty;
                    if($next<0)
                    {
                        //then we are issuing more than what we have
                    }
                    $pstock2=ProductStock::findOrFail($warehouse_balances[$y+1]->id);
                    $pstock2->quantity=$pstock2->quantity-$next;
                    $pstock2->update();
                    $can_next_warehouse=false;
                    }
                }
                /*
                else  if($qty<$line->quantity && sizeof($warehouse_balances)==1)
                {
                 
                 return "ehh";
                 
                    //there is going to be a problem
                }*/
               // else{
                    //dont reissue
                    if($prevbal=="")
                    {
                        $ai=$pstock->quantity-$issue;
                        if($ai<0)
                        {
                            $ai=0;
                        }
                    $pstock->quantity=$ai;
                    $pstock->update();
                    //reduce stock
                    $can_next_warehouse=false;
                    $line->update();
                    }
               // }
                $y++;
            }
            else{

            }
              
            }
            $x++;
        }
        $line->update();
        $product->update();
        $repair->update();
        DB::commit();
        $notify[] = ['success', 'Stock Issue Complete'];
        return redirect()->route('admin.repair.list',0)->withNotify($notify);
       // return redirect()->back()->withNotify($notify);
    }

    catch(Exception $e)
    {
        DB::rollBack();
       $notify[] = ['danger', $e->getMessage()];
      return redirect()->back()->withNotify($notify);
    
    }
    }


    public function stockreturned(Request $request, $id)
    {
        $request->validate([
          
            'issued.required'=>'Field required',
            
        ]);
        $admin=Auth::guard("admin")->user();
        $repair=Repair::findorFail($id);
        $repair->completed_on=time();
        $repair->completed_by=$admin->id;
        $repair->status=9;
        $repair->odometer_out=$request->odometer_out; 
        $repair->tested_by=$request->tested_by; 
        $repair->certified_by=$request->certified_by; 
        $repair->bodycondition=$request->remark;
        $vehicle=Vehicle::findOrFail($repair->vehicle_id);
        $vehicle->odometer=$request->odometer_out;
       
        if($repair->service_category==2)
        {
            try{
            $servicetype=Servicetype::findOrFail($repair->service_type);
            $vehicle->next_service=$vehicle->odometer+$servicetype->next_service;
            }
            catch(Exception $er)
            {

            }
        }
        $vehicle->update();


        //update order items
        $x=0;
        foreach($request->issued as $issue)
        {
            //get the details id
            $orderline=$request->ids[$x];
            $line=Orderdetails::findOrFail($orderline);
            $line->returned=$issue;
            //$line->issued_by=$admin->id;
            //$line->issued_on=time();
            $line->update();
            $x++;
        }
        $repair->update();
        $notify[] = ['success', 'Job Completed'];



        $log=new Log();
        $log->comment="Job Completed";
        $log->repair_id=$id;
        $log->status=7;
        $log->user_id=$admin->id;
        $log->save();

        //flag car as healthy

        $vehicle=Vehicle::find($repair->vehicle_id);
        $vehicle->state=1;
        $vehicle->update();

        return redirect()->route('admin.repair.list',0)->withNotify($notify);
        //return redirect()->back()->withNotify($notify);
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (User::where('status', 1)->cursor() as $user) {
            sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        }

        $notify[] = ['success', 'All users will receive an email shortly.'];
        return back()->withNotify($notify);
    }
    public function delete(Request $request)
    {
        $id=$request->id;
        $user = Repair::findOrFail($id);
        
        $notify[] = ['warning',  'User Removed.'];
        $user->delete();
         return back()->withNotify($notify);
    }


    //logs here
    public function logs($id)
    {
        $emptyMessage = 'No Records found';
        $pageTitle = 'Defect Form Details';  

       $logs=Log::where("repair_id",$id)
       ->with('user')
       ->get();
        $employees = Admin::orderBy('name','asc')->get();
        return view('admin.vehicles.logs', compact('pageTitle','logs'));
        
    }


    //testing
    public function Test(Request $request, $id)
    {
        $request->validate([
          
            'remark.required'=>'remark required',
            
        ]);
        //include last serviced on
        $admin=Auth::guard("admin")->user();
        $repair=Repair::findorFail($id);
        $repair->tested_by=$admin->id; 
        $repair->bodycondition=$request->remark;
        //$repair->certified_by=$request->certified_by; 
        $repair->status=8;
        //pending approval
        $repair->update();

      
        $log=new Log();
        $log->comment="Tested";
        $log->repair_id=$id;
        $log->remark=$request->remark;
        $log->user_id=$admin->id;
        $log->save();
        $notify[] = ['success', 'Successfully Tested'];
          //return redirect()->back()->withNotify($notify);
        return redirect()->route('admin.repair.list',0)->withNotify($notify);
    }

    //quanlity control
    public function certify(Request $request, $id)
    {
        $request->validate([
          
            'remark.required'=>'remark required',
            
        ]);
        //include last serviced on
        $admin=Auth::guard("admin")->user();
        $repair=Repair::findorFail($id);
        $repair->certified_by=$admin->id; 
        $repair->status=9;
        $repair->update();

      
        $log=new Log();
        $log->comment="Certified and verified";
        $log->repair_id=$id;
        $log->remark=$request->remark;
        $log->user_id=$admin->id;
        $log->save();
        $notify[] = ['success', 'Successfully Completed'];
          //return redirect()->back()->withNotify($notify);
        return redirect()->route('admin.repair.list',0)->withNotify($notify);
    }
   

}
