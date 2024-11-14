<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\RequestResponseAPI;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use JWTAuth;
use Log;
use Hash;
use App\Repositories\LogEventRepository;
use Mockery\Exception;
use Route;


class API_ResetPasswordController extends Controller
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
    public function apiReset(Request $request)
    {
        /////////////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////

        //validation
        if (!$request->has('user_id') ||
            !$request->has('user_guid')||
            !$request->has('tag')||
            !$request->has('old_password')||
            !$request->has('new_password')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_RESET_PASSWORD]);

        }
        /////////

      if($user->user_id != $request->input('user_id'))
        return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_RESET_PASSWORD]);

        try
        {

            $userRep = new UserRepository(new User());
            $userTmp = $userRep->findByIdAndGuid($request->input('user_id'), $request->input('user_guid'));



            if (!Hash::check($request['old_password'], $userTmp->password))
            {
                // The passwords do not match...
                return json_encode(['error' => RequestResponseAPI::ERROR_INVALID_PASSWORD_CODE, 'tag' => RequestResponseAPI::TAG_RESET_PASSWORD]);

            }
            else
            {
                $request['old_password'] = bcrypt($request['old_password']);

            }

            $request['password'] =  $request['new_password'];
            $userRep = new UserRepository(new User());
            $userRep->updatePassword($request);

            try{
                Utility::sendMail($userTmp->email, $request['new_password']);

            }
            catch (Exception $exx)
            {
                //do nothing
            }


            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_RESET_PASSWORD]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_UPDATE_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_RESET_PASSWORD]);

        }
    }
}
