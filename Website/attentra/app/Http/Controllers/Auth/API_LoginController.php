<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\UserTypeRepository;
use App\RequestResponseAPI;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Http\Request;
use Log;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class API_LoginController extends Controller
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
    protected $redirectTo = '/user';
    protected $username = 'user_name';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function username()
    {
        return 'user_name';
    }
    public function apiLogIn(Request $request)
    {
        $credentials = $request->only('user_name','password');

        if(! $token=JWTAuth::attempt($credentials))
        {
            return json_encode(['error'=>RequestResponseAPI::ERROR_LOGIN_FAIL_CODE]);
        }

        $user = new UserRepository(new User());
        $user->initialize();
        $user->setUserName($request['user_name']);
        $users =$user->select();
        $user = new UserRepository($users[0]);


        $phone_registered = false;
        if($user->get_user_type_id() != UserTypeRepository::CEO &&
            $user->get_user_type_id() != UserTypeRepository::Admin)
        {
            /////validation
            if (!$request->has('phone_code')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_WRONG_PHONE_CODE, 'tag' => RequestResponseAPI::TAG_LOGIN_USER]);

            }


            if($user->get_phone_code() == null)
            {
                UserRepository::updatePhoneCode($user->get_id(),$user->get_guid(),$request['phone_code']);
                $phone_registered = true;
            }
            else if($user->get_phone_code() != $request['phone_code'])
            {
                return json_encode(['error' => RequestResponseAPI::ERROR_WRONG_PHONE_CODE, 'tag' => RequestResponseAPI::TAG_LOGIN_USER]);

            }
            else
            {
                $phone_registered = false;
            }
        }

        $destinationPath = storage_path() . '/app/avatars';
        $allFiles = scandir($destinationPath);

        $filename = $user->get_guid();

        $file_length = strlen($filename);
        foreach ($allFiles as $key => $value) {
            if (substr($value, 0, $file_length) == $filename) {
                $contents = File::get($destinationPath.'/'.$value);
                $user -> set_image(base64_encode($contents));
                break;
            }
        }



//        Log::info(json_encode($user->get_user()));
        return json_encode(['token'=>$token,'error'=>0,'user'=>$user->get_user(), 'phone_registered' => $phone_registered, 'tag'=>RequestResponseAPI::TAG_LOGIN_USER]);
    }

    public function apilogout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

    }

}
