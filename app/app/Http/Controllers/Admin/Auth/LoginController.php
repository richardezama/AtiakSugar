<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Models\GeneralSetting;
use App\Models\Admin;
use App\Models\Verification;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $pageTitle = "Admin Login";
        return view('admin.auth.login', compact('pageTitle'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);
        $lv = @getLatestVersion();
        $general = GeneralSetting::first();
        if (@systemDetails()['version'] < @json_decode($lv)->version) {
            $general->sys_version = $lv;
        } else {
            $general->sys_version = null;
        }
        $general->save();

//

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function logout(Request $request)
    {
        $this->guard('admin')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/admin');
    }

    public function resetPassword()
    {
        $pageTitle = 'Account Recovery';
        return view('admin.reset', compact('pageTitle'));
    }





    public function registered()
    {
        return redirect()->route('admin.dashboard');
    }

    //create admin
    public function admincreate(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
             'email' => 'required|email|max:90|unique:admins,email,',
            'telephone' => 'required|unique:admins,telephone,',   
        ]);

       // $this->validator($request->all())->validate();
        $exist = Admin::where('email',$request->email)->first();
        if ($exist) {
            $notify[] = ['error', 'Email Already Teaken'];
            return back()->withNotify($notify)->withInput();
        }

        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }
        $user = new Admin();  
        $user->telephone = $request->telephone;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->company_id = $request->company_id;
        $user->password= bcrypt($request->password);
        $user->username = $request->email;
        $user->verified=0;
        $user->role_id = 2;//patient registration
        $user->company_id=0;
        $user->save();
       // event(new Registered($user = $this->createAdmin($request->all())));
        $this->guard('admin')->login($user);
        //here we need to send mail
        //send a link pastable
       /* return $this->registered($request, $user)
            ?: redirect($this->redirectPath());*/


            $email=$request->email;
            $token=encrypt(time());
            $link=route("admin.confirm",$token);
            //save this
            $confirm=new Verification();
            $confirm->token=$token;
            $confirm->link=$link;
            $confirm->email=$email;
            $confirm->status=0;
            $confirm->save();
            $notify[] = ['error','Your Account isnt verified a verification mail has been sent'];
            $msg="Dear ".$request->name.", Kindly paste this link on your browser to activate your account ".$link;
            $this->sendEmail($msg,$email,$request->name);
            return back()->withNotify($notify);
    }


    public function authenticated(Request $request, $user)
    {
        if ($user->verified == 0) {
            $this->guard('admin')->logout();
            $email=$user->email;
            $token=encrypt(time());
            $link=route("admin.confirm",$token);
            //save this
            $confirm=new Verification();
            $confirm->token=$token;
            $confirm->link=$link;
            $confirm->email=$email;
            $confirm->status=0;
            $confirm->save();
            $notify[] = ['error','Your Account isnt verified a verification mail has been sent'];
            $msg="Dear ".$user->name.", Kindly paste this link on your browser to activate your account ::".$link;
            $this->sendEmail($msg,$email,$user->name);
            return back()->withNotify($notify);
        }
        else{
        return redirect()->route('admin.dashboard');
        }
    }
    public function sendEmail($message,$email,$name)
    {

        sendSmtpMail2($email, $name, "Account Verification", $message);

    }

    
    public function confirm($token)
    {
            $confirm=Verification::where("token",$token)
           // ->where("status",0)
            ->first();
            if($confirm->status!=0)
            {
                return redirect()->route('user.login');
            }


            $confirm->status=1;
            $confirm->save();
            $user=Admin::where("username",$confirm->email)->first();
            $user->verified=1;
            $user->save();
            $this->guard()->login($user);
           return redirect()->route('admin.dashboard');
    }

}
