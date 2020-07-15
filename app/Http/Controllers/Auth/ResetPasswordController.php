<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }
	
	/**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
		return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $this->get_email_by_token($token)]
        );
    }
	
	/**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|regex:/^(?=.*[\w])(?=.*[\W])[\w\W]{6,}$/|confirmed',
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
		   'password.regex' => "Password contains <li>At least one lowercase</li><li>At least one uppercase</li><li>At least one digit</li><li>At least one special character</li><li>At least it should have 6 characters long</li>",
		];
    }
	
	private function get_email_by_token($token){
		$records =  DB::table('password_resets')->get();
		foreach ($records as $record) {
			if (Hash::check($token, $record->token) ) {
			   return $record->email;
			}
		}
	}
}
