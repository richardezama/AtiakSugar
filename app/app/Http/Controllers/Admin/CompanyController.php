<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\Company;
use App\Models\Estatetype;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CompanyController extends Controller
{
    
    public function list(){
        $pageTitle = 'Hospitals';
        $emptyMessage = 'No Records Found';
        $items = Company::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.estates.companies', compact('pageTitle', 'emptyMessage','items'));
    }

    public function store(Request $request){
        $request->validate([
            'name'        => 
            'required|unique:companies,name,'.$request->name,
        ]);
        $type = new Company();
        $type->name = $request->name;
        $type->telephone = $request->telephone;
        $type->address = $request->address;
        $type->save();
        $notify[] = ['success','Data successfully Saved'];
        return back()->withNotify($notify);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'        => 
            'required|unique:companies,name,'.$request->name,
        ]);
        $type = Company::find($id);
        $type->name = $request->name;
        $type->telephone = $request->telephone;
        $type->address = $request->address;
        $type->save();
        $notify[] = ['success','Data successfully Saved'];
        return back()->withNotify($notify);
    }

    
    public function delete(Request $request){
        $request->validate(['id' => 'required|integer']);
        $estate = Company::find($request->id);
    
        $estate->delete();
        $notify[] = ['success', 'deleted successfully.'];
        return back()->withNotify($notify);
    }
   
   
}
