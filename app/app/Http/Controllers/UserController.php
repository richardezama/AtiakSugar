<?php

namespace App\Http\Controllers;

use App\Lib\GoogleAuthenticator;
use App\Models\GeneralSetting;
use App\Models\BookedTicket;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Invoice;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {
        $pageTitle = 'Dashboard';
        $emptyMessage = 'No booked ticket found';
        $user = auth()->user();
        $widget['booked'] = $user->tickets()->booked()->count();
        $widget['pending'] = $user->tickets()->pending()->count();
        $widget['rejected'] = $user->tickets()->rejected()->count();
        $user = User::findOrFail($user->id)->with('district','estate','unit')->get()[0];
        $widget['pending'] = BookedTicket::pending()->where('user_id', auth()->user()->id)->count();
        $widget['rejected'] = BookedTicket::rejected()->where('user_id', auth()->user()->id)->count();


        $widget['invoices'] = Invoice::where('user_id', auth()->user()->id)->count();
        $widget['invoicespaid'] = Invoice::pending()->where('user_id', auth()->user()->id)->count();




        $bookedTickets = BookedTicket::with(['trip.fleetType','trip.startFrom', 'trip.endTo', 'trip.schedule' ,'pickup', 'drop'])->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.dashboard', compact('user','pageTitle', 'bookedTickets', 'widget', 'emptyMessage'));
    }

    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = Auth::user();
        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'address' => 'sometimes|required|max:80',
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])]
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required'
        ]);

        $user = Auth::user();

        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;

        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];


        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        $user->fill($in)->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change password';
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$password_validation]
        ]);


        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password changes successfully'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }


    public function printTicket($id){
        $pageTitle = "Ticket Print";
        $ticket = BookedTicket::with(['trip.fleetType','trip.startFrom', 
        'trip.endTo', 'trip.schedule',
         'trip.assignedVehicle.vehicle' ,'pickup', 'drop', 'user','company','vehicle'])->where('user_id',
          auth()->user()->id)->findOrFail($id);
       //we can get deposit
       $deposit = Deposit::Successful()->where('booked_ticket_id',$id)->get()[0];
      // return $deposit;
         return view($this->activeTemplate.'user.print_ticket', compact('ticket', 'pageTitle','deposit'));
    }


    //invoices

    public function invoices(Request $request){
        $user = Auth::user();
        $pageTitle = 'All Invoices';
        $emptyMessage = 'There is no invoice';
        $invoices = Invoice::
        leftjoin("users as u","u.id","invoices.user_id")
        ->where("u.id",$user->id);

        $invoices=$invoices
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
        $fileName="invoice";
        $pending = Invoice::pending()->get()->count();
        $paid = Invoice::paid()->get()->count();
        return view($this->activeTemplate.'user.invoices',compact('fileName','pageTitle', 'emptyMessage', 'invoices','pending','paid'));
    }

}
