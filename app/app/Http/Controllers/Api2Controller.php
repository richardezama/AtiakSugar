<?php

namespace App\Http\Controllers;
use App\Models\Orderdetails;
use App\Models\Order;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Digging;
use App\Models\Repair;
use App\Models\Vehicle;
use App\Models\Farm;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Mail;

class Api2Controller extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function index()
    {
        return "it works";
        $password = Hash::make($request->password);
              
    }
    public function login(Request $request)
    {
        //get the json
         $content = json_decode($request->getContent());

        if(!isset($content->email))
        {
          $output['message']="username missing";
          $output['data']=[];
          return $output;

        }
        else if(!isset($content->password))
        {
          $output['message']="password missing";
          $output['data']=[];
          return $output;
        }
        else if(!isset($content))
        {
          $output['message']="json body missing";
          $output['data']=[];
          return $output;
        }
        $email=$content->email;
        $pass=trim($content->password);
        $password = Hash::make($pass);
        $checklogin=Admin::where('username',$email)->get();
        $products=Product::orderBy('name','asc')->get();
       if(sizeof($checklogin)==0)
       {
            $output['message']="wrong username and password combination";
            $output['data']=[];
       }
       else{
        
       // return  $checklogin[0]->password;
        $output=array();
        if (Hash::check($pass,   $checklogin[0]->password)) {
            $output['message']="success";
            $output['data']= $checklogin[0];
            $output['farms']=Farm::get();
            //$output['products']= $products;
        }
        else{
            $output['message']="passwords not the same";
            $output['data']=[];
        }
       }
        return $output;
    }


    public function products(Request $reques)
    {
         return Product::orderBy("name","asc")->get();
    }

    public function addcart(Request $request){ 
      $content = json_decode($request->getContent());
      $id=$content->product;
      $user=$content->userid;
      $quantity=$content->quantity;

      $product = Product::find($id);
      $exists=Cart::where("product_id",$id)->where("user_id",$user)->get();
      if(sizeof($exists)>0)
      {
          $cart = Cart::where("product_id",$id)->where("user_id",$user)->first();
          $cart->quantity =  $cart->quantity+$quantity;
          $cart->user_id=$user;
          $cart->draft=1;
          $cart->update();
      }
      else{
          $cart = new Cart();
          $cart->product_id=$id;
          $cart->unit_price=$product->amount;
           $cart->quantity=$quantity;
          $cart->user_id=$user;
          $cart->draft=1;
          $cart->save();
      }
      $carts=[];

      //get the cart products
          $cartprods = Cart::join('products','carts.product_id', '=',
        'products.id')
        ->where('carts.user_id',$user)
        ->select('products.*',
         'carts.id as cart_id','carts.quantity as quantity','products.id as product_id')
         ->orderBy('products.created_at', 'desc')->get();
       
         foreach($cartprods as $productcart){
          $output['name']=$productcart['name'];
          $output['quantity']=$productcart['quantity'];
          $output['cart_id']=$productcart['cart_id'];
          $output['description']=$productcart['description'];
          $output['product_id']=$productcart['pid'];
          $output['price']=($productcart['amount']);
          array_push($carts,$output);

         }

            $output['message']="item added add another";
            $output['products']=$carts;
            return $output;
  }


  //checkout here

  public function checkout(Request $request){
   try{  
    DB::beginTransaction();  
    $content = json_decode($request->getContent());
    //$id=$content->product;
    $user=$content->user_id;
    $pnr_number = getTrx(10);
    $cart = Cart::orderBy('quantity','desc')
     ->where("user_id",$user)
     ->with('product')->get();

     if(sizeof($cart)==0)
     {
 $output["message"]="Empty Cart";
 return $output;
     }
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
     $order->created_by= $user;
     $order->comment= $content->comment;

     $order->latitude= $content->latitude;
     $order->longitude= $content->longitude;

     $order->save();
     foreach($cart as $c)
     {
         $details=new Orderdetails();
         $details->product_id=$c->product_id;
         $details->quantity=$c->quantity;
         $details->unit_price=$c->unit_price;
         $details->total=$c->unit_price* $c->quantity;
         $details->order_number=$pnr_number;
         $details->save();
        
     }
     DB::table('carts')->where('user_id', '=', $user)->delete();
     DB::commit();
     $output['message']="Checkout Successful";
     return $output;
}
 catch (\Exception $e) {
    DB::rollBack();
    $output['message']= $e->getMessage();
    return $output;
 }

 }



 public function deleteCartItem(Request $request){
  try{  
   DB::beginTransaction();  
   $content = json_decode($request->getContent());
   $id=$content->id;
    DB::table('carts')->where('id', '=', $id)->delete();
    DB::commit();
    $output['message']="Item Successfully Removed";
    return $output;
}
catch (\Exception $e) {
   DB::rollBack();
   $output['message']= $e->getMessage();
   return $output;
}

}



 public function getcart(Request $request){ 
  $content = json_decode($request->getContent());
  $user=$content->userid;
  $carts=[];

  //get the cart products
      $cartprods = Cart::join('products','carts.product_id', '=',
    'products.id')
    ->where('carts.user_id',$user)
    ->select('products.*',
     'carts.id as cart_id','carts.quantity as quantity','products.id as product_id')
     ->orderBy('products.created_at', 'desc')->get();
   
     foreach($cartprods as $productcart){
      $output['name']=$productcart['name'];
      $output['quantity']=$productcart['quantity'];
      $output['cart_id']=$productcart['cart_id'];
      $output['description']=$productcart['description'];
      $output['product_id']=$productcart['pid'];
      $output['price']=($productcart['amount']);
      array_push($carts,$output);

     }
        return $carts;
}


public function orders(){
  $items = Order::orderBy('id','desc')
  ->with('user')
  ->paginate(getPaginate());

  $list=[];
  foreach($items as $order)
  {

    $output["comment"]=$order->comment;
    $status=$order->status;
    $st="";
    if($status=="1")
    {
      $st="Pending Supervisor";
    }
    else if($status=="2")
    {
      $st="Pending Supervisor 2";
    }
    else if($status=="3")
    {
      $st="Pending Supervisor 3";
    }
    else if($status=="4")
    {
      $st="Approved";
    }
    else if($status=="5")
    {
      $st="Rejected";
    }
    $output["st"]=$st;
    $output["status"]=$status;
    $output["order_id"]=$order->id;
    $output["date"]=  /*showDateTime($order->created_at)." ".*/diffForHumans($order->created_at);
                             
    array_push($list,$output);
  }

  return $list;
}




//report issue
public function reportError(Request $request){
  try{  
   DB::beginTransaction();  
   $content = json_decode($request->getContent());
   $user=$content->user_id;
   $comment=trim($content->comment);
   $odometer=$content->odometer;
   $pnr_number = getTrx(10);

   if($comment=="")
   {
    $output['message']="Error Comment Missing";
    return $output;
   }


   $users = Admin::where("id",$user)
   ->paginate(getPaginate());
    $vehicle=Vehicle::where("operator_id",$users[0]->id)->first();
   $repair=new Repair();
   $repair->odometer_in=$odometer;
   $repair->vehicle_id=$vehicle->id;
   $repair->delivered_by=$user;
   $repair->defects_reported=$comment;
   $repair->status=1;
   $repair->draft=1;
   $repair->engineer_assigned=0; 
   $repair->service_type=0; 
   $repair->reference_number=$pnr_number;
   $repair->created_by=0;
   $repair->save();


    DB::commit();
    $output['message']="Defect Reporting Successful";
    return $output;
}
catch (\Exception $e) {
   DB::rollBack();
   $output['message']= $e->getMessage();
   return $output;
}
}
public function farminput(Request $request){
  try{  
   DB::beginTransaction();  
   $content = json_decode($request->getContent());
   $user=$content->user_id;
   $farm_id=trim($content->farm_id);

   $latitude=trim($content->latitude);
   $longitude=trim($content->longitude);


   $pnr_number = getTrx(10);
   $action=$content->action;

   if($farm_id=="")
   {
    $output['message']="Error Farm Missing";
    return $output;
   }


   $users = Admin::where("id",$user)
   ->paginate(getPaginate());
   $vehicle=Vehicle::where("operator_id",$users[0]->id)->first();
   $farm=new Digging();
   $farm->vehicle_id=$vehicle->id;
   $farm->date=date("d/m/Y",time());
   $farm->user_id=$user;
   $farm->farm_id=$farm_id;
   $farm->latitude=$latitude;
   $farm->longitude=$longitude;
   

   $msg="";
   //check if record already exists
   $exists=Digging::where("user_id",$user)
   ->where("farm_id",$farm_id)
   ->where("started","=",1)
   ->get();
   if(sizeof($exists)>0)
   {
      $state=$exists[0]->started;
       $farm=Digging::where("user_id",$user)
       ->where("farm_id",$farm_id)->first();
       if($action=="0")
       {
        if($state==1)
        {
        $farm->stopped=1;
        $farm->started=0;
        $farm->stopped_date=time();
        $msg="Farm work ended successfully";
        }
        else{
          $output['message']="Field work already stopped";
          return $output;
        }
       
       }
       else{
        if($state==1)
        {
        $output['message']="Field work in progress cant restart";
        return $output;
        }
        else{
          $msg="Farm work successfully started";
        }

       }
      
   }

   else{
    if($action==0)
    {
      $output['message']="Field work can not be stopped without starting";
      return $output;
    }
    else{
$farm->started=1;
$msg="Farm work started successfully";
    }

   }
   $farm->save();
   DB::commit();
    $output['message']=$msg;

    return $output;
}
catch (\Exception $e) {
   DB::rollBack();
   $output['message']= $e->getMessage();
   return $output;
}
}

    
}
