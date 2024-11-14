<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\RequestResponseAPI;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use JWTAuth;
use Log;
use App\Repositories\LogEventRepository;
use Illuminate\Support\Facades\Route;

class API_ForgotPasswordController extends Controller
{
      /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
      */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apiForget(Request $request)
    {
        /////////////////////
       //no token exist here
        //////////////////////////////

        //validation
        if (!$request->has('email') ||
            !$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_FORGET_PASSWORD]);
        }
        /////////
        try
        {
            $request['password'] =  Utility::randomPassword();
            $userRep = new UserRepository(new User());
            if(!UserRepository::existByEmail($request['email'],null,null))
            {
                return json_encode(['error' => 0, 'tag' => RequestResponseAPI::TAG_FORGET_PASSWORD]);
            }
            $userRep->findByEmail($request['email']);
            $request['user_id'] = $userRep->get_id();
            $request['user_guid'] = $userRep->get_guid();
            $userRep->updatePassword($request);

            Utility::sendMail($request['email'], $request['password'] );
            return json_encode(['error' => 0, 'tag' => RequestResponseAPI::TAG_FORGET_PASSWORD]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository(0, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();
            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_FORGET_PASSWORD]);
        }
    }
}
