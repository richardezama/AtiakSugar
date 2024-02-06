<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Callcategory;
use App\Models\Callsource;
use App\Models\Priorities;
use App\Models\Department;
use App\Models\District;
use App\Models\Division;
use App\Models\Call;
use App\Models\Job;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;


class CallsController extends Controller
{

    public function list(Request $request){
        $pageTitle = 'Manage Calls';
        $emptyMessage = 'No Records Found';
        $user=Auth::guard("admin")->user();
        $department=$user->department_id;
        $division=$user->division_id;
        $role=$user->role_id;


        $calls = Call::orderBy('id','desc')->with('status','district','county','subcounty',
        'parish','division','department','priority','category','job','village');

        if(!$role==1)
        {
            //get for the user department
            $calls=$calls->where('department_id',$department);
        }
        if(isset($request->status))
        {
            $status=$request->status;
            if($status==1)
            {
                $calls=$calls->where('status_id','!=',3);
            }
            else{
            $calls=$calls->where('status_id',$request->status);
            }
       
        }
        $calls =$calls->paginate(getPaginate());
        //return $calls;
        return view('admin.calls.calls', compact('pageTitle', 'emptyMessage','calls'));
    }

    //cats
    public function cats(Request $request){
        $pageTitle = 'Categories';
        $emptyMessage = 'No Records Found';
        $types = Callcategory::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.calls.list', compact('pageTitle', 'emptyMessage','types'));
    }
    public function manage($id){
        $pageTitle = 'Manage Calls';
        $emptyMessage = 'No Records Found';
        $calls = Call::orderBy('id','desc')->with('status','district','county','subcounty',
        'parish','division','department','priority','category','job','village')->where('id',$id)->get();
       //return $calls;

       $department=$calls[0]->department_id;
       //return $department;
       //get users
       $users=Admin::where('department_id',$department)->get();
       $job=Job::where('call_id',$id)->with('completed','accepted','staff')->get();
       // return $job;
        return view('admin.calls.manage', compact('pageTitle', 'emptyMessage','calls','users','job'));
    }

    

    public function addcall(){
        $pageTitle = 'Add New Call';
        $emptyMessage = '';
        $categories = Callcategory::orderBy('name','desc')->get();
        $sources = Callsource::orderBy('name','desc')->get();
        $priorities = Priorities::orderBy('name','desc')->get();
        $divisions = Division::orderBy('name','desc')->get();
        $departments = Department::orderBy('name','desc')->get();

        $districts = District::orderBy('districtname','asc')->get();
        return view('admin.calls.addcall', compact('pageTitle', 'emptyMessage','categories',
        'sources','priorities',
        'divisions','departments','districts'));
    }

    

    public function store(Request $request){
       
        $request->validate([
            'caller_type'        => 'required',
            'name'        => 'required',
            'phone'        => 'required',
            'source'        => 'required',
            'category'        => 'required',
            'priority'        => 'required',
            'description'        => 'required',
            'district'        => 'required',
            'county'        => 'required',
            'subcounty'        => 'required',
            'parish'        => 'required',
            'division'        => 'required',
            'department'        => 'required',
            'villagename'        => 'required',
            
        ]);
       // return $request->villagename;
       
        $logged=Auth::guard("admin")->user()->id;
        $call = new Call();
        $call->status_id = 1;
        $call->name =   $request->name;
        $call->phone =   $request->phone;
        $call->calltype_id =   $request->caller_type;
        
        $call->source_id=   $request->source;
        $call->category_id =   $request->category;
        $call->priority_id =   $request->priority;
        $call->description =   $request->description;
        $call->district_id =   $request->district;
        $call->county_id =   $request->county;
        $call->subcounty_id =   $request->subcounty;
        $call->parish_id =   $request->parish;
        $call->village_id =   $request->villagename;
        $call->division_id =   $request->division;
        $call->department_id =   $request->department;
        $call->memberid =   $request->memberid;
        $call->capturedby =   $logged;
        $call->save();
        $notify[] = ['success','Call Successfully Logged'];
        return back()->withNotify($notify);
    }
    //accept
    public function accept(Request $request){
       
        $request->validate([
            'id'        => 'required',
            'comment'        => 'required',
        ]);
       
        $logged=Auth::guard("admin")->user()->id;
        $job = new Job();
        $job->call_id = $request->id;
        $job->acceptcomment = $request->comment;
        $job->staffassigned = $request->staffassigned;
        $job->acceptedby =   $logged;
        $job->save();

        $call = Call::find($request->id);
        $call->status_id = 2;
        $call->save();


        $notify[] = ['success','Job Successfully Accepted'];
        return back()->withNotify($notify);
    }

    public function complete(Request $request){
       
        $request->validate([
            'id'        => 'required',
            'comment'        => 'required',
        ]);
       
        $logged=Auth::guard("admin")->user()->id;
        $job =Job::where('call_id',$request->id)->first();
        $job->completionremark = $request->comment;
        $job->completiondate = time();
        $job->completedby =   $logged;
        $job->save();

        $call = Call::find($request->id);
        $call->status_id = 3;
        $call->save();


        $notify[] = ['success','Job Successfully Completed'];
        return back()->withNotify($notify);
    }

    public function booktypeUpdate(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:booktypes,name,'.$id,
        ]);
        // return $request;
        $steering = Callcategory::find($id);
        $steering->name = $request->name;
        $steering->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $steering = Callcategory::find($request->id);
        $steering->status = $steering->status == 1 ? 0 : 1;
        $steering->save();
        if($steering->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }
        return back()->withNotify($notify);
    }


    public function storecat(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:callcategories,name,'.$request->name,
        ]);
        $steering = new Callcategory();
        $steering->name = $request->name;
        $steering->status = 1;
        $steering->save();
        $notify[] = ['success','Call Category successfully Saved'];
        return back()->withNotify($notify);
    }
   
}
