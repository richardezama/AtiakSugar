<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Log;
use App\Models\Orderdetails;
use App\Models\Order;
use App\Models\Checking;
use App\Models\Repair;
use App\Models\Checklist;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; 
use Auth;
class PosController extends Controller
{
    public function home(Request $request,$checkid){

          $repair = Repair::findOrFail($checkid)
        ->with('assigned','operator','equipment','diognised')
        ->paginate(getPaginate());

        $checklists=[];
        $extra_diognosis=[];
        foreach($repair as $user)
        {
            $checks=json_decode($user->checklists);
            $extra_diognosis=json_decode($user->extra_diognosis);

            //$checklists=Checklist::whereIn("id",$checks)->get();
        }


         $admin=Auth::guard("admin")->user();
        $pageTitle = 'Spare Parts Requisition '.$repair[0]->reference_number;
        $emptyMessage = 'No Records Found';
      
        $types = Category::orderBy('id','desc')->get();
         $cart = Cart::orderBy('quantity','desc')
        ->where("user_id",$admin->id)
        ->where("draft",1)
        ->with('product');
      
        $total=0;
        $estates = Estate::orderBy('id','desc')->with('type')
        ->where("estate_type",3)
        ->get();

        if($checkid>0)
        {
            $cart=$cart->where("repair_id",$checkid);
        }

        $cart= $cart->paginate(getPaginate());
        $cart_products=[];
        foreach($cart as $c)
        {
            $total+= $c->quantity*$c->unit_price;
            array_push($cart_products,$c->product_id);
        }

         $items = Product::orderBy('id','desc')
        ->with('category')
        ->whereNotin("id",$cart_products)
        ->paginate(getPaginate());

       
        return view('admin.products.pos', compact('pageTitle', 'emptyMessage',
        'items','types','cart','total','estates','checkid','checklists','extra_diognosis'));
    }
    public function cart(Request $request){   
        $request->validate([
            'quantity'        => 
            'required'
        ]);
        $id=$request->id;
        $admin=Auth::guard("admin")->user();
        $product = Product::find($request->id);
        //check if exists
        $exists=Cart::where("product_id",$id)->where("repair_id",$request->repair_id)->get();
        if(sizeof($exists)>0)
        {
            $cart = Cart::where("product_id",$id)->where("userepair_idr_id",$request->repair_id)->first();
            $cart->quantity =  $cart->quantity+$request->quantity;
            $cart->user_id=$admin->id;
            $cart->draft=1;
            $cart->update();
        }
        else{
            $cart = new Cart();
            $cart->product_id=$id;
            $cart->unit_price=$product->amount;
            $cart->repair_id=$request->repair_id;
            $cart->quantity=$request->quantity;
            $cart->part_number=$request->part_number;
            $cart->remark=$request->remark;
            $cart->user_id=$admin->id;
            $cart->draft=1;
            $cart->save();
        }
      
       
     

        $notify[] = ['success','Item Added'];
        return back()->withNotify($notify);
    }
    
    public function storecategory(Request $request){
       
        $name=$request->name;
        $type =new Category();
        $type->name=$name;
        $type->save();
        $notify[] = ['success','category created successfully'];
        return back()->withNotify($notify);
    }

    //update category
    public function editCategory(Request $request,$id){
        $request->validate(['id' => 'required|integer']);
        //return $request->id;
        $data = Category::find($request->id);
        $data->name=$request->name;
        $data->update();
        $notify[] = ['success', 'active successfully.'];
        return back()->withNotify($notify);
    }
    
    public function deleteCategory(Request $request){
        $request->validate(['id' => 'required|integer']);
        $data = Category::find($request->id);
        $data->delete();
        $notify[] = ['success', 'active successfully.'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id){
        $request->validate([
            'amount'        => 'required',
        ]);
      
        $admin=\Auth::guard("admin")->user();
        $product =  Product::find($id);
        $product->amount=$request->amount;
        $product->category_id=$request->type;
        $product->description=$request->description;
        $product->name=$request->name;
    
        
    $uploaded_file="";
    $filename="";
    $isImage=false;
    $filePath="";
    if ($request->has('uploadfile')) {
        // Get image file
        $file = $request->file('uploadfile');
        $filename=$file->getClientOriginalName();
        // Define folder path
        $folder = 'products/';
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
    $oldphoto=$product->photo;
    $product->photo=$filePath;
    try{
        //unlink($oldphoto);
        File::delete("uploads/".$oldphoto);
    }
    catch(\Exception $er)
    {

    }
}
        $product->update();
       
        $notify[] = ['success','Update Product'];
        return back()->withNotify($notify);


    }

    public function remove(Request $request){
        $request->validate(['id' => 'required|integer']);
        $product = Product::find($request->id);
        $oldphoto=$product->photo;
        try{
            //unlink($oldphoto);
            File::delete("uploads/".$oldphoto);
        }
        catch(\Exception $er)
        {
    
        }
        $product->delete();
        $notify[] = ['success', 'Product removed successfully.'];    
        return back()->withNotify($notify);
    }

    public function cartdelete(Request $request){
        $request->validate(['id' => 'required|integer']);
        $cart = Cart::find($request->id);
        $cart->delete();
        $notify[] = ['success', 'deleted successfully.'];
        return back()->withNotify($notify);
    }
   
    public function checkout(Request $request){
       try{
            DB::beginTransaction();  
        //$request->validate(['id' => 'required|integer']);
       /* $checkid=$request->id;
        if($checkid>0)
        {
        $checking = Checking::find($request->id);
        $checking->status=1;
        $checking->save();
        }*/
        $admin=Auth::guard("admin")->user();
        //
        $pnr_number = getTrx(10);
        //$payment_method=$request->payment_method;
        $cart = Cart::orderBy('quantity','desc')
        ->where("user_id",$admin->id)
       // ->where("checking_id",$checkid)
        ->with('product')->get();
        
        $total=0;
        foreach($cart as $c)
        {
            $total+= $c->quantity*$c->unit_price;
        }
        $amountpaid=$total;
       
        $order=new Order();
        $order->total= $total;
        $order->status=1;
        $order->order_number= $pnr_number;
        $order->amountpaid= $total;
        $order->balance= 0;
        $order->created_by= $admin->id;
        $order->repair_id=$request->id;
        $order->comment= $request->description;
        $order->save();
        foreach($cart as $c)
        {
            $details=new Orderdetails();
            $details->product_id=$c->product_id;
            $details->quantity=$c->quantity;
            $details->unit_price=$c->unit_price;
            $details->total=$c->unit_price* $c->quantity;
            $details->order_number=$pnr_number;

            $details->remark=$c->remark;
            $details->part_number=$c->part_number;

            $details->save();
           
        }
        $repair=Repair::findOrFail($request->id);
        $repair->status=4;
        $repair->update();

        $log=new Log();
        $log->comment="Spare Parts Request Submitted";
        $log->remark=$request->description;
        $log->repair_id=$order->id;
        $log->status=4;
        $log->user_id=$admin->id;
        $log->save();


        //then 
        DB::table('carts')->where('repair_id', '=', $request->id)->delete();
        //insert into orders
        //start processing the invoice
        DB::commit();
        $notify[] = ['success', 'Spare Parts Requisition Successfull'];    
        return redirect()->route('admin.repair.list',0)->withNotify($notify);
   }
    catch (\Exception $e) {
       DB::rollBack();
       $notify[] = ['danger', 'Error.'.  $e->getMessage()];    
     
       return back()->withNotify($notify);
   
    }
    }
}
