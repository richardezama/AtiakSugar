<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\SendSms;
use App\Models\Invoice;
use App\Models\User;
use App\Models\GeneralSetting;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Twilio\Rest\Client;



class SmsTemplateController extends Controller
{
    public function index()
    {
        $pageTitle = 'SMS Templates';
        $emptyMessage = 'No templates available';
        $sms_templates = SmsTemplate::get();
        return view('admin.sms_template.index', compact('pageTitle', 'emptyMessage', 'sms_templates'));
    }

    public function edit($id)
    {
        $sms_template = SmsTemplate::findOrFail($id);
        $pageTitle = $sms_template->name;
        $emptyMessage = 'No shortcode available';
        return view('admin.sms_template.edit', compact('pageTitle', 'sms_template','emptyMessage'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sms_body' => 'required',
        ]);

        $sms_template = SmsTemplate::findOrFail($id);

        $sms_template->sms_body = $request->sms_body;
        $sms_template->sms_status = $request->sms_status ? 1 : 0;
        $sms_template->save();

        $notify[] = ['success', $sms_template->name . ' template has been updated'];
        return back()->withNotify($notify);
    }

    public function storetemplete(Request $request)
    {
        $request->validate([
            'sms_body' => 'required',
        ]);
        $sms_template = new SmsTemplate();
        $sms_template->sms_body = $request->sms_body;
        $sms_template->name = $request->name;
        $sms_template->subj = $request->name;
        $sms_template->shortcodes = $request->shortcode;
        $sms_template->act = str_replace(" ","_",$request->name);
        $sms_template->sms_status =1;
        $sms_template->email_status =0;
        $sms_template->sms_status =1;
        $sms_template->rental_status =1;
        $sms_template->save();
        $notify[] = ['success', $sms_template->name . ' template added'];
        return back()->withNotify($notify);
    }


    public function smsTemplate()
    {
        $pageTitle = 'SMS API';
        return view('admin.sms_template.sms_template', compact('pageTitle'));
    }


    public function createtemplate(){
        $pageTitle = 'Create Template';
        return view('admin.sms_template.create',compact('pageTitle'));
    }


    public function smsTemplateUpdate(Request $request)
    {
        $request->validate([
            'sms_api' => 'required',
        ]);
        $general = GeneralSetting::first();
        $general->sms_api = $request->sms_api;
        $general->save();

        $notify[] = ['success', 'SMS template has been updated'];
        return back()->withNotify($notify);
    }

    //load the settings
    public function smsSetting(){
        $pageTitle = 'SMS Setting';
        return view('admin.sms_template.sms_setting',compact('pageTitle'));
    }


    //saving settings
    public function smsSettingUpdate(Request $request){
        $request->validate([
            'sms_method' => 'required|in:twilio',
            'account_sid' => 'required_if:sms_method,twilio',
            'auth_token' => 'required_if:sms_method,twilio',
            'from' => 'required_if:sms_method,twilio',
        ]);
        $request->merge(['name'=>$request->sms_method]);
        $data = array_filter($request->except('_token','sms_method'));
        $general = GeneralSetting::first();
        $general->sms_config = $data;
        $general->save();
        $notify[] = ['success', 'Sms configuration has been updated.'];
        return back()->withNotify($notify);
    }

     public function sendsms(Request $request)
        {
          
            $tid=$request->template;
            $sms_template = SmsTemplate::findOrFail($tid);
            $companyid=\Auth::guard("admin")->user()->company_id;
       
            $users=User::orderby('name','asc')
            ->where("company_id",$companyid)
            ->get();
            $us=[];
            foreach($users as $u)
            {
                  array_push($us,$u->id);
            }
    
//reminders
            if($tid==223)
            {
            $invoices = Invoice::
                leftjoin("users as u","u.id","invoices.user_id")
                ->select("invoices.*",'u.name','u.mobile')->with('estate','unit','user')
                ->whereIn("u.id",$us)
                ->where("invoices.status",0)
                ->take(1)
                ->get();
                foreach($invoices as $invoice)
                {
                    $amount=number_format($invoice->balance);
                    $name=$invoice->name;
                    $period=$invoice->month." ".$invoice->year;
                    $phone=trim($invoice->mobile);

                    if(strlen($phone)==10)
                    {
                        $phone="+256".substr($phone,1,strlen($phone)-1);
                    }
                    else if(strlen($phone)==12){
                        $phone="+".$phone;
                    
                    }
                    $msg=$sms_template->sms_body;
                    $msg=str_replace("{{amount}}"," ".$amount,$msg);
                    $msg=str_replace("{{period}}"," ".$period.",",$msg);
                    $msg=str_replace("{{tenant}}"," ".$name,$msg);
                    //echo $msg;
                   $this->startsending($msg,$phone);
                }
            }
            else{

            }
            $notify[] = ['success', 'Sms successfully sent'];
            return back()->withNotify($notify);
        }

        //start sending
        public function startsending($body,$telephone)
        {
             $general = GeneralSetting::first(['sn', 'sms_config','sms_api','sitename'])->sms_config;
             $sid=$general->account_sid;
             $token=$general->auth_token;
             $from=$general->from;
           
   $client = new Client($sid, $token);
   // Use the Client to make requests to the Twilio REST API
   $client->messages->create(
       // The number you'd like to send the message to
       $telephone,
       [
           // A Twilio phone number you purchased at https://console.twilio.com
           'from' => $from,
           // The body of the text message you'd like to send
           'body' => $body
       ]
   );
   $msg= "sms sent";
        }

        //send load
        public function sendIndex(){
            $pageTitle = 'Send SMS';
            $templates=SmsTemplate::where('rental_status',1)->get();
            $general = GeneralSetting::first(['sn', 'sms_config','sms_api','sitename'])->sms_config;
            return view('admin.sms_template.send',compact('pageTitle','templates'));
        }
}
