<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookedTicket;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\Gateway;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\Unit;

use App\Models\ProductStock;
use App\Models\Product;


use App\Models\Estate;
use App\Models\Repair;
use App\Models\Checklist;
use App\Models\Vehicle;
use App\Models\Servicetype;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Auth;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    
    public function pendingjobs(Request $request){
        $pageTitle = 'Pending Jobs';
        $emptyMessage = 'There is no Jobs';
        $jobs = Repair::orderBy('id','desc')
        ->with('assigned','operator','equipment');
     

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

        

        /*
        if ($start) {
            $invoices = Invoice::whereDate('created_at',Carbon::parse($start));
        }
        if($end){
            $invoices = Invoice::whereDate('created_at','>=',
            Carbon::parse($start))->whereDate('created_at','<=',Carbon::parse($end));
        }*/
        }
        
         if(isset($request->search))
        {
         $jobs=$users->where("reference_number",'LIKE',$request->search);
        }

        $jobs = $jobs
        ->orderBy("id","desc")
        ->paginate(getPaginate());
        $completed=0;
        $pending=0;

        foreach($jobs as $job)
        {

            if($job->status==9)
            {
                $completed++;
            }
            else{
                $pending++;
            }
        }

       if(isset($request->export))
        {
            /*
         $invoiceexport=$invoices
         ->whereIn("invoices.estate_id",$estates)
        ->select("invoices.*")->with('estate','unit','user')
        ->get();
        $this->exportInvoice($invoiceexport);*/
        }
        else{  
            
       return view('admin.reports.pendingjobs', compact('pageTitle', 'emptyMessage', 'jobs','pending','completed'));
       
        }
    }


    public function pending_per_status(Request $request)
    {
        //all repairs
        $pageTitle = 'All Requests';
        $emptyMessage = 'No Records found';
        $jobs = Repair::leftjoin("statuses as s", "s.id","repairs.status");
       // ->with('assigned','operator','equipment','statuses');
/*  
*/
          $jobs = $jobs
        ->select('s.status as name','s.id as status',
        DB::raw('count(*) as total')
         )
         ->groupBy("s.status")
        ->orderBy("s.status","desc")
       
        ->paginate(getPaginate());
       
        return view('admin.reports.status', compact('pageTitle', 'emptyMessage', 'jobs'));
      
    }


    //warehousestockbalance
    public function warehousebalance(Request $request)
    {
    
        //all repairs
        $pageTitle = 'Warehouse Stock Report';
        $emptyMessage = 'No Records found';
         $items = ProductStock::
        leftjoin("warehouses as w", "w.id","product_stocks.warehouse_id")
        ->leftjoin("products as p", "p.id","product_stocks.product_id")
        ->select('w.name as warehouse','p.name as product',
        DB::raw('sum(product_stocks.quantity) as total')
         )
         ->groupBy("p.name","w.name")
        ->orderBy("p.name","asc");
        if(isset($request->search))
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

        if ($start && sizeof($date)==1) {
            $items = $items->whereDate('product_stocks.created_at',Carbon::parse($start));
        }
      
        if(sizeof($date)>1){
           
             $items = $items->whereDate('product_stocks.created_at','>=',
            Carbon::parse($start))->whereDate('product_stocks.created_at','<=',Carbon::parse($end));
        }
        }
        $items=$items->paginate(getPaginate());

        if(isset($request->export))
        {
          
        $this->exportWarehouse($items);
        }
        else{ 
        return view('admin.reports.warehousestock', compact('pageTitle', 'emptyMessage', 'items'));
        }
      
    }

    //warehousestockbalance
    public function productbalance(Request $request)
    {
        //all repairs
        $pageTitle = 'Warehouse Stock Report';
        $emptyMessage = 'No Records found';
         $items = ProductStock::
        leftjoin("warehouses as w", "w.id","product_stocks.warehouse_id")
        ->leftjoin("products as p", "p.id","product_stocks.product_id")
        ->select('w.name as warehouse','p.name as product',
        DB::raw('sum(product_stocks.quantity) as total')
         )
         ->groupBy("p.name")
        ->orderBy("p.name","asc");
        if(isset($request->search))
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

        if ($start && sizeof($date)==1) {
            $items = $items->whereDate('product_stocks.created_at',Carbon::parse($start));
        }
      
        if(sizeof($date)>1){
           
             $items = $items->whereDate('product_stocks.created_at','>=',
            Carbon::parse($start))->whereDate('product_stocks.created_at','<=',Carbon::parse($end));
        }
        }
        $items=$items->paginate(getPaginate());

        if(isset($request->export))
        {
          
        $this->exportWarehouse($items);
        }
        else{ 
        return view('admin.reports.productstock', compact('pageTitle', 'emptyMessage', 'items'));
        }
      
    }

    public function service_tracker()
    {
        $pageTitle = 'Service Tracking';
        $emptyMessage = 'No user found';
        $users = Vehicle::orderBy('id','desc')
        ->with('make','model','operator')
        ->select("vehicles.*");
         $users = $users
        ->paginate(getPaginate());
        //company_id
        return view('admin.reports.service_tracking', compact('pageTitle', 'emptyMessage', 'users'));
    }

   


    public function exportWarehouse($data)
{
    
$delimiter = ","; 
$filename = "Warehouse" . date('Y-m-d') . ".csv"; 
$f = fopen('php://memory', 'w'); 
// Set column headers 
$fields = array('Warehouse','Product','Balance'); 
fputcsv($f, $fields, $delimiter); 
foreach($data as $row){ 
     $lineData = array($row->warehouse,$row->product,$row->total); 
    fputcsv($f, $lineData, $delimiter); 
}
$this->export($f,$filename);

}

public function exportInvoice($data)
{
    
 $delimiter = ","; 
$filename = "Invoices" . date('Y-m-d') . ".csv"; 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 
// Set column headers 
$fields = array('Invoice Code','Date','Estate','Unit','Tenant','Amount','Period','Status'); 
fputcsv($f, $fields, $delimiter); 
// Output each row of the data, format line as csv and write to file pointe
foreach($data as $row){ 
    $statusid=$row->status;
    $status="";
    if($statusid==0)
    {
$status="Pending";
    }
    else  if($statusid==1)
    {
$status="Paid";
    }
    else  if($statusid==3)
    {
$status="Rejected";
    }
     $lineData = array($row->invoice_code,$row->created_at,$row->estate->name
     , $row->unit->name,$row->user->name,$row->total,$row->period,$status); 
    fputcsv($f, $lineData, $delimiter); 
}
$this->export($f,$filename);

}
    public function export($f,$filename){
           
        // Move back to beginning of file 
        fseek($f, 0); 
        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
         
        //output all remaining data on a file pointer 
        fpassthru($f); 
        }

       
}
