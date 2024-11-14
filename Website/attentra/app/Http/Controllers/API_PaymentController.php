<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/19/2017
 * Time: 6:17 PM
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\User;
use SoapClient;
use App\Payment;
use App\Repositories\PaymentRepository;
use App\Repositories\LogEventRepository;
use Illuminate\Http\Request;
use App\RequestResponseAPI;
use JWTAuth;


class API_PaymentController extends Controller
{
    protected $paymentRepository;

    /**
     * PaymentController constructor.
     * @param PaymentRepository $payment
     */
    public function __construct(PaymentRepository $payment)
    {
        $this->paymentRepository = $payment;
    }


    public function apiIndex(Request $request)
    {
        ///////////////////check token validation/////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        ////////////////////////////////////////////////////////
        ///  //validation
        if (!$request->has('tag') ||
            !$request->has('user_id')||
            !$request->has('user_guid')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_PAYMENT]);

        }
        ///////////////////////
        ///
        $userRep = new UserRepository(new User());
        if(!$userRep->exist($request->input('user_id'),$request->input('user_guid')))
        {
            return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_PAYMENT]);

        }
        try {

//        select track data with
            $paramsObj1 = array(
                array("se", "payment", "payment_id"),
                array("se", "payment", "amount"),
                array("se", "payment", "description"),
                array("se", "payment", "authority"),
                array("se", "payment", "status")
            );


            $paramsObj3 = array(
                array("whereRaw",
                    "payment.user_id = ".$request->input('user_id')
                )

            );
            /////add deleted at condition to query/////////

            $paramsObj3[] =   array("whereRaw",
                "payment.deleted_at is null"
            );
            $paramsObj3[] = array("orderBy",
                "payment.payment_id", "DESC"
            );
            /// ///////////////////////////////////////

            $this->paymentRepository->initialize();

            $payments = $this->paymentRepository->getFullDetailPayment($paramsObj1, null, $paramsObj3);



            return json_encode(['token' => $token, 'payments' => $payments, 'error' => 0, 'tag' => RequestResponseAPI::TAG_INDEX_PAYMENT]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_PAYMENT]);
        }

    }

    public function apiKey(Request $request)
    {
        ///////////////////check token validation/////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        ////////////////////////////////////////////////////////

        /////validation
        if (!$request->has('tag')||
            !$request->has('name')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_KEY_PAYMENT]);

        }

        try {

            $publicKey = RequestResponseAPI::getPublicKeyByName($request->input('name'));
            if($publicKey == "")
            {
                return json_encode(['error' => RequestResponseAPI::ERROR_INVALID_PUBLIC_KEY_CODE, 'tag' => RequestResponseAPI::TAG_KEY_PAYMENT]);
            }

            return json_encode(['token' => $token, 'public_key' => $publicKey, 'error' => 0, 'tag' => RequestResponseAPI::TAG_KEY_PAYMENT]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_KEY_PAYMENT]);
        }
    }

    public function apiStore(Request $request)
    {
        ///////////////////check token validation/////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        ////////////////////////////////////////////////////////

        /////validation
        if (!$request->has('tag')||
            !$request->has('product_id')||
            !$request->has('token')||
            !$request->has('payload')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_PAYMENT]);

        }

        try {

            if(PaymentRepository::isTokenExist($request->input('token')))
                return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_STORE_PAYMENT]);

            $payment = new Payment();
            $payment->amount = PaymentRepository::getAmount($request->input('product_id'));
            $payment->description = "خرید اعتبار درون اپ آتنترا";
            $payment->user_id = $user->user_id;
            $payment->currency_id = 1;
            $payment->payment_guid = uniqid('',true);
            $payment->authority = $request->input('token');
            $payment->status = 100;
            $payment->followup = $request->input('payload');
            $payment->from_app = 1;

            $payment->save();

            UserRepository::IncreaseCharge($user->user_id,$user->user_guid,$payment->amount);


            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_STORE_PAYMENT]);
        } catch (\Exception $ex) {
            DB::rollback();
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_STORE_PAYMENT]);
        }
    }


}
