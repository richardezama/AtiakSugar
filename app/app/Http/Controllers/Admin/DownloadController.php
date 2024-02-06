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

class DownloadController extends Controller
{

public function exportBySellers($data)
    {
        
     $delimiter = ","; 
    $filename = "orders_by_sellers_" . date('Y-m-d') . ".csv"; 
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
    // Set column headers 
    $fields = array('Seller','Customer','Order No', 'Order Date', 'Route','Total Amount','Total Paid','Balance','Payment Status','Phone'); 
    fputcsv($f, $fields, $delimiter); 
    // Output each row of the data, format line as csv and write to file pointe
    foreach($data as $row){ 
         $lineData = array($row->salesman->name,$row->custname,$row->code, $row->created_at,$row->road->name,$row->grand_total,$row->amount_paid,$row->grand_total-$row->amount_paid,$row->payment_status,$row->phone); 
        fputcsv($f, $lineData, $delimiter); 
    } 
    
    $this->export($f,$filename);
  
    }

   

}