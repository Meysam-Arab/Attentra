<?php
/**
 * Created by PhpStorm.
 * User: Hooman
 * Date: 5/24/2017
 * Time: 7:22 PM
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;
use App\Utility;
use App\Repositories\UserRepository;
use App\User;

use App\Repositories\LogEventRepository;
use Exception;
use Route;

class ForgetPasswordController extends Controller
{
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
    public function forget_password(Request $request)
    {
        /////////////////////
        //no token exist here
        //////////////////////////////
//        return response()->json([
//            'success' => true,
//            'message'=>['success.login','نام کاربری یا رمز عبور شما اشتباه وارد شده']
//        ]);
        //validation
        if (!$request->has('email'))
         {
            return response()->json([
                'success' => false,
                'message'=>['لطفا ایمیل خود را وارد کنید']
            ]);
        }
        /////////
        try
        {

            $request['password'] =  Utility::randomPassword();
            $userRep = new UserRepository(new User());
            if(!UserRepository::existByEmail($request['email'],null,null))
            {
                return response()->json([
                    'success' => true,
                    'message'=>['یک ایمیل حاوی رمز عبور جدید برای شما ارسال شد لطفا ایمیل خود را بررسی کنید.']
                ]);
            }

            $userRep->findByEmail($request['email']);
            $request['user_id'] = $userRep->get_id();
            $request['user_guid'] = $userRep->get_guid();
            $userRep->updatePassword($request);

            Utility::sendMail($userRep->get_email(), $request['password'] );

            return response()->json([
                'success' => true,
                'message'=>['یک ایمیل حاوی رمز عبور جدید برای شما ارسال شد لطفا ایمیل خود را بررسی کنید.']
            ]);
        } catch (\Exception $ex) {
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }
    }
}