<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/19/2017
 * Time: 6:17 PM
 */

namespace App\Http\Controllers;

use App\Payment;
use App\User;
use DateTime;
use DateTimeZone;
use App\Repositories\PaymentRepository;
use Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\OperationMessage;
use Validator;
use Exception;
use App\Repositories\LogEventRepository;
use Route;
use Log;
use App\Repositories\UserRepository;

class PaymentController extends Controller
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
    public function index()
    {
        try {
            $payments = DB::table('payment')
                ->where('user_id', '=', Auth::user()->user_id)
                ->where('deleted_at',null)
                ->get();

            return view('payment.index', ['payments' =>$payments]);

        } catch (Exception $e) {
            $logEvent = new LogEventRepository((Auth::check() == true ? Auth::user()->user_id : -1), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }

    }

    public function store(Request $request)
    {

//        $validation = Validator::make($request->all(), [
//            'title' => 'required|max:30',
//            'description' => 'required|max:200',
//            'email'=>'required|email',
//            'tel'=>'required|digits_between:7,15',
//            'mobile'=>'required|digits_between:7,15'
//        ]);

        $this->validate($request, [
            'cost' => 'required',
        ]);

        try {



            //get Authority from zarinpal site...
            $MerchantID = "688c5170-42a5-11e7-a557-005056a205be";//get from zarinpal ...
            $Amount = $request->input('cost');
            $Description = "خرید اعتبار درون سایت آتنترا";
            $Email = Auth::user()->email;
            $Mobile = "ندارد";
            $CallbackURL = "http://www.attentra.ir/payment/verification";

            $data = array('MerchantID' => $MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Email' => $Email,
                'Mobile' => $Mobile,
                'CallbackURL' => $CallbackURL);
            $jsonData = json_encode($data);
            $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
            curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ));
            $result = curl_exec($ch);

            $err = curl_error($ch);
            $result = json_decode($result, true);
            curl_close($ch);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                if ($result["Status"] == 100) {
                    /////store Authority in session////
                    // Via the global helper...
                    if(strlen($result["Authority"]) == 36)
                    {
                        ////store in database and retrieve in verification...
                        $payment = new Payment();
                        $payment->user_id = Auth::user()->user_id;
                        $payment->currency_id = 1;
                        $payment->amount = $Amount;
                        $payment->description = $Description;
                        $payment->authority = $result["Authority"];
                        $payment->followup = -1;
                        $payment->status = $result["Status"];
                        $payment->from_app = 0;
                        $this->paymentRepository->initializeByObject($payment);
                        $this->paymentRepository->store();
                        /// ////////////////////////////////
                        return  new RedirectResponse('https://www.zarinpal.com/pg/StartPay/' . $result["Authority"]);
                    }
                    else
                    {
                        //return error
                        ////store in database and retrieve in verification...
                        $payment = new Payment();
                        $payment->user_id = Auth::user()->user_id;
                        $payment->currency_id = 1;
                        $payment->amount = $Amount;
                        $payment->description = $Description;
                        $payment->status = $result["Status"];
                        $payment->authority = -1;
                        $payment->followup = -1;
                        $payment->from_app = 0;
                        $this->paymentRepository->initializeByObject($payment);
                        $this->paymentRepository->store();
                        /// ////////////////////////////////
                        ///    /// ////////////////////////////////
                        $message = new OperationMessage();
                        $message->Code = $result["Status"];
                        $pr = new PaymentRepository(new Payment());
                        $message->Text = $pr->getMessage($result["Status"]);
                        return redirect()->back()->with('message', $message);
                    }
                } else {
                    ////store in database and retrieve in verification...
                    $payment = new Payment();
                    $payment->user_id = Auth::user()->user_id;
                    $payment->currency_id = 1;
                    $payment->amount = $Amount;
                    $payment->description = $Description;
                    $payment->status = $result["Status"];
                    $payment->authority = -1;
                    $payment->followup = -1;
                    $payment->from_app = 0;
                    $this->paymentRepository->initializeByObject($payment);
                    $this->paymentRepository->store();
                    /// ////////////////////////////////
                    $message = new OperationMessage();
                    $message->Code = $result["Status"];
                    $pr = new PaymentRepository(new Payment());
                    $message->Text = $pr->getMessage($result["Status"]);
                    return redirect()->back()->with('message', $message);
                }
            }

        } catch (Exception $e) {
            $logEvent = new LogEventRepository((Auth::check() == true ? Auth::user()->user_id : -1), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }

    }

    public function verification()
    {

        try {

            $Status = $_GET['Status'];
            $Authority = $_GET['Authority'];

            /// /////////////////////////////////////////////////////
            //get stored payment...
            $payment = DB::table('payment')
                ->where('authority', '=', $Authority)
                ->where('deleted_at',null)
                ->get()->first();
            ///////////////////////////
            //////set created_at manualy wrt company time_zone////////
            $company = UserRepository::getCompany($payment->user_id,null);
            $data = array('MerchantID' => '688c5170-42a5-11e7-a557-005056a205be', 'Authority' => $Authority, 'Amount' => $payment->amount);
            $jsonData = json_encode($data);
            $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentVerification.json');
            curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ));

            $result = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);
            $result = json_decode($result, true);

            $paymentTmp = new Payment();
            $paymentTmp->authority = $Authority;
            $paymentTmp->amount = $payment->amount;
            $paymentTmp->payment_guid = $payment->payment_guid;
            $paymentTmp->date_time = $payment->created_at;
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $paymentTmp->date_time);
            $date->setTimezone(new DateTimeZone($company[0]->time_zone));
            $temp=\Morilog\Jalali\jDateTime::strftime('Y-m-d H:i:s', strtotime($date->format('Y-m-d H:i:s')));
            $paymentTmp->date_time = $temp;

            if ($err) {
                $message = new OperationMessage();
                $message->Text = $err;
                return view('payment.result', ['payment' =>$paymentTmp])->with('message', $message);

            } else {
                if ($result['Status'] == 100) {
                    $paymentTmp->status = $result['Status'];
                    $paymentTmp->followup = $result['RefID'];
                } else {
                    if($Status == "NOK")
                    {
                        //transaction failed.... insert to payment and return to payment page
                        $paymentTmp->status = -2;
                        $paymentTmp->followup = "0";
                    }
                    else
                    {
                        $paymentTmp->status = $result['Status'];
                        $paymentTmp->followup = $result['RefID'];
                    }
                }
            }
            ////////////////////////////////////////////////////////////

            //edit new payment here...
            PaymentRepository::edit($paymentTmp);
            ///////////////////////////////////////
            $user =  User::find($payment->user_id);
            if ($result['Status'] == 100) {
                UserRepository::IncreaseCharge($user->user_id,$user->user_guid,$payment->amount);
            }


            return view('payment.result', ['payment' =>$paymentTmp]);

        } catch (Exception $e) {
            $logEvent = new LogEventRepository((Auth::check() == true ? Auth::user()->user_id : -1), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return view('payment.result')->with('message', $message);
        }

    }

}
