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
use App\Models\Estate;
use App\Models\Company;
use App\Models\Vehicle;
use App\Models\Department;
use App\Models\Division;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class VehicleController extends Controller
{
    public function list()
    {
        $pageTitle = 'All Cars';
        $emptyMessage = 'No user found';
        $users = Vehicle::orderBy('id','desc')
        ->with('make','model','operator');
        $users = $users
        ->paginate(getPaginate());
        //company_id
        return view('admin.vehicles.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
    
   
     

     public function addCar()
     {
         $pageTitle = 'Add New Equipment';     
         $roles = Role::orderBy('id','desc')->get();
         $divisions = Division::orderBy('name','desc')->get();
         $departments = Department::orderBy('name','desc')->get();
         $districts=  District::orderBy('districtid','desc')->get();
         $units=[];
         $company_id= Auth::guard('admin')->user()->company_id;
       
         $estates = Estate::orderBy('name','desc')
         ->where("company_id",$company_id)
         ->get();
         $users = Admin::orderBy('name','asc')
         ->where('role_id',2)
         ->get();


         $makes = Make::orderBy('id','desc')->get();
         $models = Models::orderBy('name','desc')->get();

         return view('admin.vehicles.create', compact('pageTitle','roles','divisions',
         'departments','districts','estates','units','models','makes','users'));
     }



    //create
    public function storecar(Request $request)
    {
        try{
            DB::beginTransaction();
        $request->validate([
          
           
            'name.required'=>'Name required',
            'make_id.required'=>'Make  required'
            
        ]);
        //include last serviced on
        $vehicle=new Vehicle();
        $vehicle->name=$request->name;
        $vehicle->chasis=$request->chasis;
        $vehicle->make_id=$request->make_id;
        $vehicle->model_id=$request->model_id;
        $vehicle->operator_id=$request->operator;
        $vehicle->number_plate=$request->number_plate;
        $vehicle->engine_no=$request->engine_no;

        
        $vehicle->save();

        DB::commit();
        $notify[] = ['success', 'New Equipment Successfully Added'];
       return redirect()->back()->withNotify($notify);
     
    }

    catch(Exception $e)
    {
        DB::rollBack();
      $notify[] = ['danger', $e->getMessage()];
      return redirect()->back()->withNotify($notify);
    
    }
    }

    

    public function details($id)
    {
        
 
        $pageTitle = 'Edit Equipment';     
        $roles = Role::orderBy('id','desc')->get();
        $divisions = Division::orderBy('name','desc')->get();
        $departments = Department::orderBy('name','desc')->get();
        $districts=  District::orderBy('districtid','desc')->get();
        $units=[];
        $company_id= Auth::guard('admin')->user()->company_id;
      
        $estates = Estate::orderBy('name','desc')
        ->where("company_id",$company_id)
        ->get();

        $users = Admin::orderBy('name','asc')
        ->where('role_id',2)
        ->get();

        $makes = Make::orderBy('id','desc')->get();
        $models = Models::orderBy('name','desc')->get();
        $item = Vehicle::where("id",$id)->with('make','model')->get()[0];
     
        return view('admin.vehicles.detail', compact('pageTitle','roles','divisions',
        'departments','districts','estates','units','models','makes','item','users'));

          
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
        $vehicle->operator_id=$request->operator;
        $vehicle->chasis=$request->chasis;
        $vehicle->make_id=$request->make_id;
        $vehicle->model_id=$request->model_id;
        $vehicle->number_plate=$request->number_plate;
        $vehicle->engine_no=$request->engine_no;
        $vehicle->update();

        $notify[] = ['success', 'Equipment Details Updated'];
        return redirect()->back()->withNotify($notify);
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
        $user = Vehicle::findOrFail($id);
        $notify[] = ['warning',  'User Removed.'];
        $user->delete();
         return back()->withNotify($notify);
    }

   

}
