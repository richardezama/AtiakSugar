<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Estatetype;
use App\Models\Subscription;
use App\Models\Subscriptiontype;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;

class SubscriptionController extends Controller
{

    public function list(){
        $pageTitle = 'Subscription History';
        $emptyMessage = 'No Records Found';
        $company_id= Auth::guard('admin')->user()->company_id;
        $user_id= Auth::guard('admin')->user()->id;
        $items = Subscription::orderBy('id','desc')
        ->where("user_id",$user_id)
        ->with('type');
        $items = $items->paginate(getPaginate()); 
        $types = Subscriptiontype::orderBy('id','desc')->get();
        return view('admin.subscription.list', compact('pageTitle', 'emptyMessage','items','types'));
    }


    public function store(Request $request){
        $request->validate([
            'subscription_type'        => 
            'required',
        ]);
        $uid= Auth::guard('admin')->user()->id;
        $com= Auth::guard('admin')->user()->company_id;
        $estate = new Subscription();
        $estate->user_id = $uid;
        $estate->company_id = $com;
        $estate->year = date("Y",time());
        $estate->month = date("F",time());
        //get selected type
        $type=$request->subscription_type;
        $package=Subscriptiontype::find($type);
        $amount=$package->price;
        $nextyear = strtotime(date('d-m-Y', strtotime('+1 year')));
        //add 365 days now
        $estate->expiry=$nextyear;
        $estate->subscription_type=$type;
        //initiate easypay here


        try{
        $curl = curl_init();
        $txref = Str::random(12);
        $payload=array(
            "username"=>"d79da5e247a808df",
            "password" =>"ed2ac087a46b6141",
            "action"=>"mmdeposit",
            "amount"=>$amount,
            "currency"=>"UGX",
            "phone" =>$request->telephone,
            "reference"=>$txref,
            "reason"=>"Deposit"
        );
        $amount=500;
         //open connection
         $ch = curl_init();
         //set the url, number of POST vars, POST data
         $url="https://www.easypay.co.ug/api/";
         curl_setopt($ch,CURLOPT_URL, $url);
         curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($payload));
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,15);
         curl_setopt($ch, CURLOPT_TIMEOUT, 300); //timeout in seconds
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         //execute post
         $server_output = curl_exec($ch);
         curl_close ($ch);
        // Further processing ...
         $resp=json_decode($server_output);
         //return $server_output;
        if($resp =="" || $resp =="")
       {
        $notify[] = ['warning', 'An error has occured'];
        return back()->withNotify($notify);
       }
       else{
        $success=intval($resp->success);
        $msg="";
        if($success==1)
        {
            //flag user verified
            /*$user = User::find(Auth::user()->id);
            $user->verified=1;
            $user->subscription_date=time();
            $user->save();
            $estate->save();*/
            $notify[] = ['success','Successfully Subscribed'];
            return back()->withNotify($notify);
           
        }
        else {
            $notify[] = ['warning', 'An error has occured'];
            return back()->withNotify($notify);
        }
    }
    }
    catch(\Exception $e)
    {
        $notify[] = ['error', $e->getMessage()];
        return back()->withNotify($notify);
    }


      
      
    }
}
