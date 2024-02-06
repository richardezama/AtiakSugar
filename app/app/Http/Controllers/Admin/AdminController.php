<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\Call;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Invoice;
use App\Models\UserLogin;
use App\Models\Expense;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {
        $pageTitle = 'Dashboard';
        $role=\Auth::guard("admin")->user()->role_id;
        $companyid=\Auth::guard("admin")->user()->company_id;
        $userid=\Auth::guard("admin")->user()->id;
        $estates=Estate::orderby('name','asc')
        ->where("created_by",$userid)
        ->get();
        $est=[];
        $est1=[];
        foreach($estates as $estate)
        {
            $output['name']=$estate->name;
            $output['expenses']=Expense::where("estate_id",$estate->id)->sum("amount");
            $output['pendinginvoices']=Invoice::where("estate_id",$estate->id)->sum("balance");
            array_push($est,$output);
            array_push($est1,$estate->id);
        }
       


        // User Info
        $widget['est'] = $est;
        $widget['expense'] =Expense::
        whereIn("estate_id",$est1)
        ->sum("amount");;
        $widget['total_users'] = User::
        where("company_id",$companyid)
        ->count();
        $widget['verified_users'] = User::where('status', 1)->count();
        $widget['email_unverified_users'] = User::where('ev', 0)->count();
        $widget['sms_unverified_users'] = User::where('sv', 0)->count();
        $widget['calls'] = Call::count();
       $widget['successful_payment'] = 0;
        $widget['pending_payment'] = 0;
        $widget['rejected_payment'] = 0;
        $widget['total_counter'] = 0;

        $widget['estates'] = Estate::count();
        $widget['units'] = Unit::count();
        $widget['tenants'] = User::count();
        $widget['tenants'] = User::count();
      
        if($role==2)
        {
            $widget['estates'] = Estate::
            where("created_by",$userid)
            ->count();
            $widget['units'] = Unit::
            whereIn("estate_id",$est1)
            ->count();
            $widget['tenants'] = User::
            where("created_by",$userid)
            ->count();
            $widget['verified_users'] = User::where('status', 1)
            ->  where("created_by",$userid)
            ->count();
        }

        $widget['invoicespending'] = Invoice::where("status",0)
        ->whereIn("estate_id",$est1)
        ->count();
        $widget['invoicesrejected'] = Invoice::where("status",3)
        ->whereIn("estate_id",$est1)
        ->count();
        $widget['invoicespaid'] = Invoice::where("status",1)
        ->whereIn("estate_id",$est1)
        ->count();

        //amount
        $widget['totalpaid'] = Invoice::where("status","!=",3)
        ->whereIn("estate_id",$est1)
        ->sum("amountpaid");
      
        $soldTickets = [];
        $roleid=\Auth::guard("admin")->user()->role_id;
        $userid=\Auth::guard("admin")->user()->id;
       
        $books=[];
        $deposits=[];
        $userLoginData = UserLogin::/*where('created_at', 
        '>=', \Carbon\Carbon::now()->subDay(30))->*/get(['browser', 'os', 'country']);


        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);

       // return $chart;
        return view('admin.dashboard', compact('pageTitle', 'widget','chart','deposits'));
    }


    public function profile()
    {
        $pageTitle = 'Profile';
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);
        $user = Auth::guard('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = uploadImage($request->image, imagePath()['profile']['admin']['path'], imagePath()['profile']['admin']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin = Auth::guard('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = Auth::guard('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password do not match !!'];
            return back()->withNotify($notify);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return redirect()->route('admin.password')->withNotify($notify);
    }

    public function notifications(){
        $notifications = AdminNotification::orderBy('id','desc')->with('user')->paginate(getPaginate());
        $pageTitle = 'Notifications';
        return view('admin.notifications',compact('pageTitle','notifications'));
    }


    public function notificationRead($id){
        $notification = AdminNotification::findOrFail($id);
        $notification->read_status = 1;
        $notification->save();
        return redirect($notification->click_url);
    }

    public function requestReport()
    {
        $pageTitle = 'Your Listed Report & Request';
        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASE_CODE');
        $url = "https://license.viserlab.com/issue/get?".http_build_query($arr);
        $response = json_decode(curlContent($url));
        if ($response->status == 'error') {
            return redirect()->route('admin.dashboard')->withErrors($response->message);
        }
        $reports = $response->message[0];
        return view('admin.reports',compact('reports','pageTitle'));
    }

    public function reportSubmit(Request $request)
    {
        $request->validate([
            'type'=>'required|in:bug,feature',
            'message'=>'required',
        ]);
        $url = 'https://license.viserlab.com/issue/add';

        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASE_CODE');
        $arr['req_type'] = $request->type;
        $arr['message'] = $request->message;
        $response = json_decode(curlPostContent($url,$arr));
        if ($response->status == 'error') {
            return back()->withErrors($response->message);
        }
        $notify[] = ['success',$response->message];
        return back()->withNotify($notify);
    }

    public function systemInfo(){
        $laravelVersion = app()->version();
        $serverDetails = $_SERVER;
        $currentPHP = phpversion();
        $timeZone = config('app.timezone');
        $pageTitle = 'System Information';
        return view('admin.info',compact('pageTitle', 'currentPHP', 'laravelVersion', 'serverDetails','timeZone'));
    }

    public function readAll(){
        AdminNotification::where('read_status',0)->update([
            'read_status'=>1
        ]);
        $notify[] = ['success','Notifications read successfully'];
        return back()->withNotify($notify);
    }


}
