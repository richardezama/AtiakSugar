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
use App\Models\Expensetype;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; 


class ProductController extends Controller
{
    public function list(){
        $pageTitle = 'All Products';
        $emptyMessage = 'No Records Found';
        $items = Product::orderBy('id','desc')
        ->with('category')
        ->paginate(getPaginate());
        $types = Category::orderBy('id','desc')->get();
        return view('admin.products.list', compact('pageTitle', 'emptyMessage',
        'items','types'));
    }
    public function store(Request $request){   
    
        $request->validate([
            'description' => 
            'required',
            'amount'        => 
            'required'
        ]);
        $admin=\Auth::guard("admin")->user();
        $product = new Product();
        $product->amount=$request->amount;
        $product->stock_balance=0;
 
        $product->category_id=$request->type;
        $product->product_code=$request->code;
        $product->description=$request->description;
        $product->created_by=$admin->id;
        $product->name=$request->name;
    
    $uploaded_file="";
    $filename="";
    $isImage=false;
    $filePath="";
    /*
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
    }*/

$file_name=$uploaded_file;
if($isImage)
{
    $product->photo=$filePath;
}
        $product->save();
        $notify[] = ['success','Product Saved'];
        return back()->withNotify($notify);
    }

    public function listtypes(){
       
        $pageTitle = 'Product Categories';
        $emptyMessage = 'No Records Found';
        $items = Category::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.products.categories', compact('pageTitle', 'emptyMessage','items'));
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

   
}
