<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

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
    protected $redirectTo = '/index';
//
    protected $username = 'user_name';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'user_name';
    }

//    protected function guard()
//    {
//        return Auth::guard('guard-name');
//    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
//    protected function validateLogin(Request $request)
//    {
//
//        Log::info('in ligin .....dddd');
//        //Meysam Changed/////
//
//        $this->validate($request, [
//            $this->username() => 'required', 'password' => 'required',
//        ]);
//
//        return var_dump('hooooooooooooooooora');
//    }



    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
//    protected function authenticated(\Illuminate\Http\Request $request, $user)
//    {
//        Log::info('in ath');
//        Log::info('in ath $_REQUEST[\'user_name\']:'.json_encode($request['user_name']));
//
////        if ($request->ajax()){
////
////            return response()->json([
////                'auth' => auth()->check(),
////                'user' => $user,
////                'intended' => $this->redirectPath(),
////            ]);
////
////        }
//        Log::info('Auth::user:'.json_encode(Auth::user()));
//
//
//    }

    public function login(Request $request)
    {

        //Validation as needed or form request
        if (Auth::attempt(['user_name' => $request->input('user_name'), 'password' => $request->input('password')])) {
            // The user is active, not suspended, and exists.
            return response()->json([
                'success' => true
            ]);
        }else{

            return response()->json([
                'success' => false,
                'message'=>['fail.login','نام کاربری یا رمز عبور شما اشتباه وارد شده']
            ]);
        }

        // else user is logged in
//        return redirect()->intended();
    }

}
