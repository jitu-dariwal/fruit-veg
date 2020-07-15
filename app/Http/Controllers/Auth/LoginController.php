<?php

namespace App\Http\Controllers\Auth;

use App\Shop\Admins\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;

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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/accounts?tab=account';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {   
        if(isset(auth()->user()->id) && !empty(auth()->user()->id)) {
            
            return redirect($this->redirectTo);
        }
        return view('auth.login');
    }

    /**
     * Login the admin
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    { 
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $details = $request->only('email', 'password');
        
        $email_verified = DB::table('customers')->select('customers_emailvalidated')->where('email', $details['email'])->first();
        
        if(!empty($email_verified) && $email_verified->customers_emailvalidated == 1) {
            $details['status'] = 1;
        } else {
            
            $details['status'] = 0;
        }
        
        
        
        if (auth()->attempt($details)) {
            
            if(!empty(auth()->user()->id)) {
                DB::table('customers_temp_basket')->where('customers_id', auth()->user()->id)->delete();
            }
            
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
		
		\Session::flash('warning', 'Please correct below errors.');
        
        return $this->sendFailedLoginResponse($request);
    }
    
    
}
