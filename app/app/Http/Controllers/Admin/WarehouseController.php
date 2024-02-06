<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Unit;
use App\Models\Make;
use App\Models\Warehouse;
use App\Models\ProductStock;
use App\Models\Product;
use App\Models\Models;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
class WarehouseController extends Controller
{
    public function list(){
        $pageTitle = 'Warehouses';
        $emptyMessage = 'No Records Found';
        $items = Warehouse::orderBy('id','desc')
        ->paginate(getPaginate());
        return view('admin.warehousing.index', compact('pageTitle', 'emptyMessage',
        'items'));
    }
    public function store(Request $request){
       
        $request->validate([
            'name'        => 
            'required|unique:warehouses,name,'.$request->name,
        ]);
        $estate = new Warehouse();
        $estate->name = $request->name;
        $estate->save();
        $notify[] = ['success','Warehouse successfully Saved'];
        return back()->withNotify($notify);
    }


    public function update(Request $request, $id){
        $request->validate([
            'name'        => 'required|unique:units,name,'.$id,
        ]);
        // return $request;
        $estate = Warehouse::find($id);
        $estate->name = $request->name;
        $estate->save();
        $notify[] = ['success','updated successfully'];
        return back()->withNotify($notify);
    }

    public function EnableDisabled(Request $request){
        $request->validate(['id' => 'required|integer']);
        $unit = Warehouse::find($request->id);
        /*$estate->status = $estate->status == 1 ? 0 : 1;
        $estate->save();
        if($estate->status == 1){
            $notify[] = ['success', 'active successfully.'];
        }else{
            $notify[] = ['success', 'disabled successfully.'];
        }*/
        $unit->delete();
        $notify[] = ['success', 'active successfully.'];
          return back()->withNotify($notify);
    }


    //stock taking
    public function stocktaking(){
        $pageTitle = 'Stock Taking';
        $emptyMessage = 'No Records Found';
        $warehouses = Warehouse::orderBy('id','asc')
        ->paginate(getPaginate());

        //get the products
        $products=Product::orderBy("name","asc")->get();
        return view('admin.warehousing.stocktaking', compact('pageTitle', 'emptyMessage',
        'warehouses','products'));
    }
   

    public function storestock(Request $request)
    {
        try{
            DB::beginTransaction();
        $request->validate([
            'product_id.required'=>'Product Required',
            'warehouse_id.required'=>'Warehouse required',
            'quantity.required'=>'Quantity required'
        ]);
        //include last serviced on
        $pnr_number = getTrx(10);
        $admin=Auth::guard("admin")->user();
        $stock=new ProductStock();
        $stock->warehouse_id=$request->warehouse_id;
        $stock->product_id=$request->product_id;
        $stock->quantity=$request->quantity;
       

        $exists=ProductStock::where("product_id",$request->product_id)
        ->where("warehouse_id",$request->warehouse_id)
        ->get();
        if(sizeof($exists)>0)
        {
            $stock=ProductStock::where("product_id",$request->product_id)
            ->where("warehouse_id",$request->product_id)->first();
            $stock->quantity=$request->quantity+$stock->quantity;
            $stock->update();
        }
        else{
            $stock->createdby=$admin->id;
            $stock->save();
        }

        $product=Product::findOrfail($request->product_id);
        $product->stock_balance=$product->stock_balance+$request->quantity;
        $product->save();


        
        DB::commit();
        $notify[] = ['success', 'Record Successfully Added'];
       return redirect()->back()->withNotify($notify);
    }

    catch(Exception $e)
    {
        DB::rollBack();
      $notify[] = ['danger', $e->getMessage()];
      return redirect()->back()->withNotify($notify);
    
    }
     
    }
}
