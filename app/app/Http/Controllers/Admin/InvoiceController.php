<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookedTicket;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\Estate;
use App\Models\Subscription;
use App\Models\Subscriptiontype;
use App\Models\Gateway;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class InvoiceController extends Controller
{
    
    public function all(Request $request){
        $pageTitle = 'All Invoices';
        $emptyMessage = 'There is no invoice';
        $userid= Auth::guard('admin')->user()->id;
        $company_id= Auth::guard('admin')->user()->company_id;
        $e=Estate::where("created_by",$userid)->get();
        $estates=[];
        foreach($e as $i)
        {
            array_push($estates,$i->id);
        }
        $invoices = Invoice::
        leftjoin("users as u","u.id","invoices.user_id");
    
        if(isset($request->search))
        {
            $search=$request->search;
            $invoices=$invoices->where("name","like","%".$search."%");
        }

        if(isset($request->date))
        {
            $search=$request->date;
            $date = explode('-',$search);
        $start = @$date[0];
        $end = @$date[1];
        // date validation
        $pattern = "/\d{2}\/\d{2}\/\d{4}/";
        if ($start && !preg_match($pattern,$start)) {
            $notify[] = ['error','Invalid date format'];
            return redirect()->route('admin.deposit.list')->withNotify($notify);
        }
        if ($end && !preg_match($pattern,$end)) {
            $notify[] = ['error','Invalid date format'];
            return redirect()->route('admin.deposit.list')->withNotify($notify);
        }


        if ($start) {
            $invoices = Invoice::whereDate('created_at',Carbon::parse($start));
        }
        if($end){
            $invoices = Invoice::whereDate('created_at','>=',
            Carbon::parse($start))->whereDate('created_at','<=',Carbon::parse($end));
        }
        }
        
        $invoices=$invoices
        ->whereIn("invoices.estate_id",$estates)
        ->select("invoices.*")->with('estate','unit','user')
        ->paginate(getPaginate()); 
        /*
        $roleid=\Auth::guard("admin")->user()->role_id;
        $userid=\Auth::guard("admin")->user()->id;
        if($roleid!=1)
        {
            //only tickets belonging to cars of this user
            //$tickets=$tickets->where('vehicle_id',$userid);
            Deposit::whereIn('vehicle_id', function($query) use($userid){
                $query->select('id')
                ->from(with(new Vehicle)->getTable())
                ->whereIn('user_id', [$userid]);
            })->get();
        }*/
         $pending = Invoice::pending()
       ->whereIn("invoices.estate_id",$estates)
      
       ->get()
       
        ->sum("balance");
        $paid = Invoice::paid()
        ->whereIn("invoices.estate_id",$estates)
        ->get()
        ->sum("amountpaid");
        return view('admin.invoices.log', compact('pageTitle', 'emptyMessage', 'invoices','pending','paid'));
    }

    public function dateSearch(Request $request,$scope = null){
        $search = $request->date;
        if (!$search) {
            return back();
        }
        $date = explode('-',$search);
        $start = @$date[0];
        $end = @$date[1];
        // date validation
        $pattern = "/\d{2}\/\d{2}\/\d{4}/";
        if ($start && !preg_match($pattern,$start)) {
            $notify[] = ['error','Invalid date format'];
            return redirect()->route('admin.deposit.list')->withNotify($notify);
        }
        if ($end && !preg_match($pattern,$end)) {
            $notify[] = ['error','Invalid date format'];
            return redirect()->route('admin.deposit.list')->withNotify($notify);
        }


        if ($start) {
            $deposits = Deposit::where('status','!=',0)->whereDate('created_at',Carbon::parse($start));
        }
        if($end){
            $deposits = Deposit::where('status','!=',0)->whereDate('created_at','>=',Carbon::parse($start))->whereDate('created_at','<=',Carbon::parse($end));
        }
        
    }

    

    //create
    public function create()
    {
         $general = GeneralSetting::first();
         $company_id= Auth::guard('admin')->user()->company_id;


         $sub=Subscription::where("user_id",Auth::guard("admin")->user()->id)
         ->where("expiry",">",time())->get();
 
         if(sizeof($sub)==0)
         {
             $notify[] = ['warning', 'Invoice Module Only Available for Subcribed Users'];
             return redirect()->back()->withNotify($notify);
         }
 
       
        $tenants=User::orderBy('id','desc')
        ->where("company_id",$company_id)
        ->get();
         $pageTitle="Invoice Tenants";
         $months=["January","February","March","April","May","June","July","August","September","October","November","December"];
         $years=[];
         $year=date("Y",time());
         $current=date("Y",time())-2;
         for($x=$current; $x<=$year;$x++)
         {
            array_push($years,$x);
         }
        return view('admin.invoices.create', compact('pageTitle', 'general','tenants','months','years'));
    }

    public function details($id)
    {
         $general = GeneralSetting::first();
          $invoice = Invoice::where("id",$id)->with('estate','unit','user')->first();
         $pageTitle = $invoice->user->name.' '. showAmount($invoice->total) . ' '.$general->cur_text;
        return view('admin.invoices.detail', compact('pageTitle', 'invoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'month' => 'required',
            'tenant' => 'required',
        ]);
        $sub=Subscription::where("user_id",Auth::guard("admin")->user()->id)
         ->where("expiry",">",time())->get();
 
         if(sizeof($sub)==0)
         {
             $notify[] = ['warning', 'Invoice Module Only Available for Subcribed Users'];
             return redirect()->back()->withNotify($notify);
         }

         $tenant=$request->tenant;
         $year=$request->year;
         $month=$request->month;
        $exists=Invoice::where("user_id",$tenant)
        ->where("month",$month)
        ->where("year",$year)->get();
        
    
        if(sizeof($exists)>0)
        {
            $notify[] = ['danger', 'Invoice Already Exists'];
            return back()->withNotify($notify);
        }
        else{
            $admin=\Auth::guard("admin")->user();
            $user=User::findOrFail($tenant);
            $user->unit_id;
            $pnr_number = getTrx(10);
            $cost=Unit::findOrFail($user->unit_id)->rent;
            $invoice=new Invoice();
            $invoice->user_id=$request->tenant;
            $invoice->month=$request->month;
            $invoice->year=$request->year;
            $invoice->estate_id=$user->estate_id;
            $invoice->unit_id=$user->unit_id;
            $invoice->total=$cost;
            $invoice->amountpaid=0;
            $invoice->balance=$cost;         
            $invoice->invoice_code=$pnr_number;
            $invoice->period=$request->month."-".$request->year;
            $invoice->invoicedBy=$admin->id;
            $invoice->save();
            return redirect()->route('admin.invoice.list');
            $notify[] = ['success', 'Invoice Created'];
            return back()->withNotify($notify);
        }
    }


    //pay invoice
    public function pay(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'amount' => 'required',
        ]);

        $id=$request->id;
        $amount=$request->amount;
        $invoice=Invoice::findOrFail($id);
        $invoice->amountpaid+=$amount;
        $invoice->balance-=$amount;
        $invoice->save();

        if($invoice->balance<=0)
        {
            $invoice->status=1;
        }
        //add this record to deposits if your big head wants
            $invoice->update();
            $notify[] = ['success', 'Invoice Updated'];
            return back()->withNotify($notify);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'message' => 'required|max:250'
        ]);
        $id=$request->id;
        $msg=$request->message;
        $invoice=Invoice::findOrFail($id);
        $invoice->status=3;
        $invoice->cancelreason=$msg;
        $invoice->save();
        $notify[] = ['success', 'Invoice Cancelled'];
        return back()->withNotify($notify);
    }
     
    public function delete(Request $request){
        $request->validate(['id' => 'required|integer']);
        $data = Invoice::find($request->id);
        $data->delete();
        $notify[] = ['success', 'active successfully.'];
          return back()->withNotify($notify);
    }
}
