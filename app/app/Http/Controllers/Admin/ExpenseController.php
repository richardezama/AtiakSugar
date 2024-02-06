<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Expense;
use App\Models\Expensetype;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;

class ExpenseController extends Controller
{

    public function list(){
        $pageTitle = 'Expenses';
        $emptyMessage = 'No Records Found';

        $company_id= Auth::guard('admin')->user()->company_id;
        $userid= Auth::guard('admin')->user()->id;
       
        $e=Estate::where("created_by",$userid)->get();
        $estates=[];
        foreach($e as $i)
        {
            array_push($estates,$i->id);
        }

        $items = Expense::orderBy('id','desc')
        ->whereIn("estate_id",$estates)
        ->with('type','staff','estate','unit')
        ->paginate(getPaginate());
        $types = Expensetype::orderBy('id','desc')->get();
        $estates = Estate::orderBy('id','desc')
        ->where("company_id",$company_id)
        ->get();
        return view('admin.expenses.list', compact('pageTitle', 'emptyMessage',
        'items','types','estates'));
    }


    public function store(Request $request){
       
    
        $request->validate([
            'description' => 
            'required',
            'amount'        => 
            'required'
        ]);
        $admin=\Auth::guard("admin")->user();
        $expense = new Expense();
        $expense->amount=$request->amount;
        $expense->expense_type=$request->type;
        $expense->unit_id=$request->unit_id;
        $expense->estate_id=$request->estate_id;
        $expense->description=$request->description;
        $expense->created_by=$admin->id;

        $expense->receipt_no=$request->receipt;
        $expense->expense_date=$request->date;
      

    $uploaded_file="";
    $filename="";
    $isImage=false;
    $filePath="";
    if ($request->has('uploadfile')) {
        // Get image file
        $file = $request->file('uploadfile');
        $filename=$file->getClientOriginalName();
        // Define folder path
        $folder = 'receipts/';
        $file_name = null;
        // Make a file path where image will be stored [ folder path + file name + file extension]
          $new_file_name =time().".".$file->getClientOriginalExtension();
           if ($file->storeAs($folder, $new_file_name)) {
                $filePath =$folder.$new_file_name;
                $isImage=true;
                 }
            else{
                //echo "error";
            }
            $uploaded_file=$filePath;
    }
    else{
        //echo "no uploads file";
    }

$file_name=$uploaded_file;
if($isImage)
{
    $expense->photo=$filePath;
}
        $expense->save();
        $notify[] = ['success','Expense Saved'];
        return back()->withNotify($notify);
    }

    public function listtypes(){
       
        $pageTitle = 'Expense Types';
        $emptyMessage = 'No Records Found';
        $items = Expensetype::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.expenses.types', compact('pageTitle', 'emptyMessage','items'));
    }

    
    public function storetype(Request $request){
       
        $name=$request->name;
        $type =new Expensetype();
        $type->name=$name;
        $type->save();
        $notify[] = ['success','type created successfully'];
        return back()->withNotify($notify);
    }

    
    public function deleteType(Request $request){
        $request->validate(['id' => 'required|integer']);
        $data = Expensetype::find($request->id);
        /*$estate->status = $estate->status == 1 ? 0 : 1;
        $estate->save();
        if($estate->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }*/
        $data->delete();
        $notify[] = ['success', 'active successfully.'];
          return back()->withNotify($notify);
    }


    public function update(Request $request, $id){
        $request->validate([
            'amount'        => 'required',
        ]);
        // return $request;
        $expense = Expense::where("id",$id)->first();
        $expense->amount=$request->amount;
        $expense->expense_type=$request->type;
        $expense->unit_id=$request->unit_id;
        $expense->estate_id=$request->estate_id;
       // $expense->description=$request->description;
       // $expense->created_by=$admin->id;
        $expense->receipt_no=$request->receipt;
        $expense->expense_date=$request->date;
        $expense->update();
        //no uploads
       $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function remove(Request $request){
        $request->validate(['id' => 'required|integer']);
        $expense = Expense::find($request->id);
        $expense->delete();
        $notify[] = ['success', 'active successfully.'];    
        return back()->withNotify($notify);
    }

   
}
