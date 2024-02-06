<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\User;
use App\Models\Admin;
use App\Models\Counter;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Subscriptiontype;
use App\Models\Unit;
use App\Models\Estate;
use App\Models\Company;
use App\Models\Unithistory;
use App\Models\Department;
use App\Models\Division;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ManageUsersController extends Controller
{

    //reset
    public function reset($id)
    {
        $pageTitle = 'User Detail';
        $user = Admin::findOrFail($id);
        $user->reset=1;
        $user->password= bcrypt('admin.123');
        $user->save();
        $notify[] = ['success', 'Password reset to admin.123'];
        return redirect()->back()->withNotify($notify);
    }


    public function allUsers()
    {
        $pageTitle = 'Manage Tenants';
        $emptyMessage = 'No user found';
        $users = User::orderBy('id','desc')
        ->with('district');
        $company_id= Auth::guard('admin')->user()->company_id;
        if($company_id!=0)
        {
            $users=$users
            ->where("company_id",$company_id);
            //get things of that company

        }
        $users = $users
        ->paginate(getPaginate());
        //company_id
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
    //admins
    public function allAdmins()
    {
        $pageTitle = 'Manage Users';
        $emptyMessage = 'No user found';
        $users = Admin::orderBy('id','desc')->with('role')->paginate(getPaginate());
       // return $users;
      return view('admin.users.adminlist', compact('pageTitle', 'emptyMessage', 'users'));
    }
     //admin detail
     public function admindetail($id)
     {
         $pageTitle = 'User Detail';
         $user = Admin::findOrFail($id);
         $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));     
          $roles = Role::orderBy('id','desc')->get();
          $divisions = Division::orderBy('name','desc')->get();
          $departments = Department::orderBy('name','desc')->get();
 
         return view('admin.users.admindetail', compact('pageTitle', 'user','roles','divisions','departments'));
     }

     //new admin
     public function addAdmins()
     {
         $pageTitle = 'Create New Admin';     
         $roles = Role::orderBy('id','desc')->get();
         $divisions = Division::orderBy('name','desc')->get();
         $estates = Estate::orderBy('name','desc')->get();
         $departments = Department::orderBy('name','desc')->get();
         $companies = Company::orderBy('id','desc')->paginate(getPaginate());
      
         return view('admin.users.admincreate', compact('pageTitle','roles','divisions','departments',
         'estates','companies'));
     }
     //create
    public function admincreate(Request $request)
    {
        $user = new Admin();
        $request->validate([
            'fullname' => 'required|max:50',
             'email' => 'required|email|max:90|unique:users,email,',
            'telephone' => 'required|unique:users,mobile,',
            'username' => 'required|max:90|unique:admins,username,',
            
        ]);
        $user->telephone = $request->telephone;
        $user->name = $request->fullname;
        $user->email = $request->email;

        $user->verified =1;
        $user->available =1;

        $user->department_id = $request->department_id;
        $user->password= bcrypt('admin.123');
        $user->username = $request->username;
        $user->role_id = $request->role_id;
        $user->save();

        $notify[] = ['success', 'Admin detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

     

     public function addTenant()
     {
         $pageTitle = 'Create New Tenant';     
         $roles = Role::orderBy('id','desc')->get();
         $divisions = Division::orderBy('name','desc')->get();
         $departments = Department::orderBy('name','desc')->get();
         $districts=  District::orderBy('districtid','desc')->get();
         $units=[];
         $company_id= Auth::guard('admin')->user()->company_id;
       
         $estates = Estate::orderBy('name','desc')
         ->where("company_id",$company_id)
         ->get();
       
         return view('admin.users.tenantcreate', compact('pageTitle','roles','divisions','departments','districts','estates','units'));
     }



     //login here
     public function adminlogin($id){
        $user = Admin::findOrFail($id);
        Auth::guard("admin")->login($user);
        return redirect()->route('user.home');
    }
    //admin update
    public function adminupdate(Request $request)
    {
       $id=$request->id;
        $user = Admin::findOrFail($id);
        $request->validate([
            'fullname' => 'required|max:50',
             'email' => 'required|email|max:90|unique:admins,email,' . $user->id,
        ]);
        $user->telephone = $request->telephone;
        $user->name = $request->fullname;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->company_id = $request->company_id;
        $user->available =  $request->available ? 1 : 0;
       
        //$user->division_id = $request->division;

        $user->save();

        $notify[] = ['success', 'Admin detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    //create
    public function tenantcreate(Request $request)
    {
        try{
            DB::beginTransaction();
        $user = new User();
        $request->validate([
          
             'email' => 'required|email|max:90|unique:users,email,',
             'username' => 'required|unique:users,username,',
            'telephone' => 'required|unique:users,mobile,',
          //  'company_id' => 'required',
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required'
            
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
        
        $users = User::orderBy('id','desc')
        ->with('district');
        $company_id= Auth::guard('admin')->user()->company_id;
        if($company_id!=0)
        {
            $users=$users
            ->where("company_id",$company_id);
            //get things of that company

        }
        $users = $users->get();
        //check the type


        if(sizeof($users)>=$tlimit)
        {
            $notify[] = ['warning', 'Package Limit Reached Maximum Tenants Supported'.$tlimit];
            return redirect()->back()->withNotify($notify);
        }






        $company_id= Auth::guard('admin')->user()->company_id;
        $user->mobile = $request->telephone;
        $user->company_id = $company_id;
        $user->created_by =Auth::guard('admin')->user()->id;
        $user->name = $request->firstname." ".$request->lastname;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->start_date = $request->start_date;
        $user->end_date = $request->end_date;
        $user->email = $request->email;
        $user->nin = $request->nin;
        $user->password= bcrypt($request->username);
        $user->username = $request->username;
        $user->districtid = $request->placeoforigin;
        $user->unit_id = $request->unit_id;
        $user->estate_id = $request->estate_id;
        $user->Active = 1;
        $user->ev = 1;//email verified
        $user->sv = 1;//sms verified
        $user->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($request->country) ? $request->country : null,
            'city' => ''
        ];
        $user->save();
        $unit=Unit::findOrFail($request->unit_id);
        $unit->tenant_id=$user->id;
        $unit->status=1;
        $unit->save();
        DB::commit();
        $notify[] = ['success', 'New Tenant Successfully Added'];
       return redirect()->back()->withNotify($notify);
     
    }

    catch(Exception $e)
    {
        DB::rollBack();
      $notify[] = ['success', $e->getMessage()];
      return redirect()->back()->withNotify($notify);
    
    }
    }

    public function adminsearch(Request $request, $scope)
    {
        $search = $request->search;
        $users = Admin::where(function ($user) use ($search) {
            $user->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });
        $pageTitle = '';
       
        $users = $users->paginate(getPaginate());
        $pageTitle .= 'Admin Search - ' . $search;
        $emptyMessage = 'No search result found';
        return view('admin.users.adminlist', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'users'));
    }

    public function activeUsers()
    {
        $pageTitle = 'Manage Active Users';
        $emptyMessage = 'No active user found';
       
        $users = User::orderBy('id','desc')
        ->active()
        ->with('district');
        $company_id= Auth::guard('admin')->user()->company_id;
        if($company_id!=0)
        {
            $users=$users
            ->where("company_id",$company_id);
            //get things of that company

        }

        $users = $users
        ->paginate(getPaginate());


        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function bannedUsers()
    {
        $pageTitle = 'Banned Users';
        $emptyMessage = 'No banned user found';
        $users = User::banned()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Users';
        $emptyMessage = 'No email unverified user found';
        $users = User::emailUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Users';
        $emptyMessage = 'No email verified user found';
        $users = User::emailVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }


    public function smsUnverifiedUsers()
    {
        $pageTitle = 'SMS Unverified Users';
        $emptyMessage = 'No sms unverified user found';
        $users = User::smsUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }


    public function smsVerifiedUsers()
    {
        $pageTitle = 'SMS Verified Users';
        $emptyMessage = 'No sms verified user found';
        $users = User::smsVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }


    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $users = User::where(function ($user) use ($search) {
            $user->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });
        $pageTitle = '';
        if ($scope == 'active') {
            $pageTitle = 'Active ';
            $users = $users->where('status', 1);
        }elseif($scope == 'banned'){
            $pageTitle = 'Banned';
            $users = $users->where('status', 0);
        }elseif($scope == 'emailUnverified'){
            $pageTitle = 'Email Unverified ';
            $users = $users->where('ev', 0);
        }elseif($scope == 'smsUnverified'){
            $pageTitle = 'SMS Unverified ';
            $users = $users->where('sv', 0);
        }elseif($scope == 'withBalance'){
            $pageTitle = 'With Balance ';
            $users = $users->where('balance','!=',0);
        }

        $users = $users->paginate(getPaginate());
        $pageTitle .= 'User Search - ' . $search;
        $emptyMessage = 'No search result found';
        return view('admin.users.list', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'users'));
    }


    public function detail($id)
    {
        $pageTitle = 'Tenant Detail';
        $districts=  District::orderBy('districtid','desc')->get();
        $userid= Auth::guard('admin')->user()->id;
      
        $estates = Estate::orderBy('name','desc')
        ->where("created_by",$userid)
        ->get();

        $departments = Department::orderBy('name','desc')->get();
 
        $user = User::where("id",$id)->with('district','estate','unit')->get()[0];
         $user->estate_id;
       
         $units=Unit::where("estate_id",$user->estate_id)->get();
       
        $units = Unit::orderBy('name','desc')->where('estate_id',$user->estate_id)->get();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.detail', compact('pageTitle', 'user','countries','estates','units','departments'));
    }
   


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $request->validate([
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' => 'required|email|max:90|unique:users,email,' . $user->id,
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            'country' => 'required',
        ]);
        $countryCode = $request->country;
        $user->mobile = $request->mobile;
        $user->country_code = $countryCode;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->start_date = $request->start_date;
        $user->end_date = $request->end_date;
        $user->estate_id = $request->estate_id;
       
        $user->address = [
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip' => $request->zip,
                            'country' => @$countryData->$countryCode->country,
                        ];
        $user->status = $request->status ? 1 : 0;
        $user->ev = $request->ev ? 1 : 0;
        $user->sv = $request->sv ? 1 : 0;
        $user->save();
         $unit=Unit::find($request->unit_id);
           
        if($request->status==0)
        {
            //man is leaving
            $user->IsActive = $request->status ? 1 : 0;
            //release the house too
            $unit->status=0;
            $unit->tenant_id="";
           
            $history=new Unithistory();
            $history->estate_id=$user->estate_id;
            $history->unit_id=$user->unit_id;
            $history->tenant_id=$user->id;
           // $history->save();
            
            //add unit history here
        }

        if($user->unit_id!=$request->unit_id)
        {
         $user->unit_id= $request->unit_id; 
         $unit->tenant_id="";
         $unit->status=0;
        }
        $user->unit_id = $request->unit_id;
        $user->save();
       
        $unit->save();
   


        $notify[] = ['success', 'User detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }


    public function userLoginHistory($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'User Login History - ' . $user->username;
        $emptyMessage = 'No users login found.';
        $login_logs = $user->login_logs()->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.users.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }



    public function showEmailSingleForm($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'Send Email To: ' . $user->username;
        return view('admin.users.email_single', compact('pageTitle', 'user'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $user = User::findOrFail($id);
        sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        $notify[] = ['success', $user->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function showEmailAllForm()
    {
        $pageTitle = 'Send Email To All Users';
        return view('admin.users.email_all', compact('pageTitle'));
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

    public function login($id){
        $user = User::findOrFail($id);
        Auth::login($user);
        return redirect()->route('user.home');
    }

    public function emailLog($id){
        $user = User::findOrFail($id);
        $pageTitle = 'Email log of '.$user->username;
        $logs = EmailLog::where('user_id',$id)->with('user')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('admin.users.email_log', compact('pageTitle','logs','emptyMessage','user'));
    }

    public function emailDetails($id){
        $email = EmailLog::findOrFail($id);
        $pageTitle = 'Email details';
        return view('admin.users.email_details', compact('pageTitle','email'));
    }

    public function delete(Request $request)
    {
        $id=$request->id;
        $user = User::findOrFail($id);
        $notify[] = ['warning',  'User Removed.'];
        $user->delete();
         return back()->withNotify($notify);
    }

    
    public function deleteadmin(Request $request)
    {
        $id=$request->id;
        $user = Admin::findOrFail($id);
        $notify[] = ['warning',  'Staff Removed.'];
        $user->delete();
         return back()->withNotify($notify);
    }


}
