<?php

namespace App\Http\Controllers\Admin;

use App\Shop\Admins\Requests\LoginRequest;
use App\Shop\Admins\Requests\PasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Mail\sendForgotPasswordToAdminuserMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use DB;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';


    /**
     * Shows the admin login form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    { 
        if (auth()->guard('employee')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin.login');
    }

    /**
     * Login the employee
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
        $details['status'] = 1;
        if (auth()->guard('employee')->attempt($details)) {
			$users=auth()->guard('employee')->user();
            if($users->hasRole('packer')){
                return redirect()->route('admin.packer.index');
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
	
	/**
	* admin reset password
	*/
	public function showResetForm()
    { 
        return view('auth.admin.passwords.email');
    }
	
	/**
	* admin reset password email
	*/
	public function resetPassword(PasswordRequest $request)
    { 	
		$data = $request->all();
		$admin_details = DB::table('employees')->where('email', $data['email'])->get();
		
		if(isset($admin_details[0]->id) && !empty($admin_details[0]->id)) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+?";
			$new_password = substr( str_shuffle( $chars ), 0, 7 );
			$h_newspassword = Hash::make($new_password);
			$update_pass = DB::table('employees')->where('id', $admin_details[0]->id)->update(['password' => $h_newspassword]);
			Mail::to($data['email'],'Password details')
					 ->send(new sendForgotPasswordToAdminuserMailable($admin_details[0], $new_password));
			
			return redirect()->route('admin.resetpassword')->with('message', 'Mail sent to your Email');
		} else {
			$request->session()->flash('error', 'Email not exists');
			return redirect()->route('admin.resetpassword');
		}
		
		
    }
    

}
