<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/19/2017
 * Time: 6:40 PM
 */


namespace App\Repositories;

use App;
use App\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use DB;
use Log;
use Input;

class PaymentRepository implements PaymentRepositoryInterface
{
    protected $payment;

    const AT_1000 = "at_1000";
    const AT_2000 = "at_2000";
    const AT_5000 = "at_5000";
    const AT_10000 = "at_10000";
    const AT_20000 = "at_20000";
    const AT_30000 = "at_30000";
    const AT_50000 = "at_50000";
    const AT_100000 = "at_100000";

    const MESSAGE_PAYMENT_ZARINPAL_DEFECTIVE_INFORMATION = -1;
    const MESSAGE_PAYMENT_ZARINPAL_INCORRECT_IP_OR_MERCHANT_CODE = -2;
    const MESSAGE_PAYMENT_ZARINPAL_SHAPARAK_RESTRICTION_PAYMENT = -3;
    const MESSAGE_PAYMENT_ZARINPAL_BELOW_SILVER_LEVEL = -4;
    const MESSAGE_PAYMENT_ZARINPAL_REQUEST_NOT_FIND = -11;
    const MESSAGE_PAYMENT_ZARINPAL_CAN_NOT_EDIT_REQUEST = -12;
    const MESSAGE_PAYMENT_ZARINPAL_FINANCIAL_OPERATION_NOT_FIND = -21;
    const MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_FAILED = -22;
    const MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_PAYMENT_MISMATCH = -33;
    const MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_COUNT_EXCEEDED = -34;
    const MESSAGE_PAYMENT_ZARINPAL_METHOD_ACCESS_NOT_ALLOWED = -40;
    const MESSAGE_PAYMENT_ZARINPAL_INCORRECT_ADDETIONAL_DATA = -41;
    const MESSAGE_PAYMENT_ZARINPAL_ID_VALIDATION_TIME = -42;
    const MESSAGE_PAYMENT_ZARINPAL_REQUEST_ARCHIVED= -54;
    const MESSAGE_PAYMENT_ZARINPAL_OPERATION_SUCCESSFUL = 100;
    const MESSAGE_PAYMENT_ZARINPAL_OPERATION_SUCCESSFUL_PAYMENT_VERIFICATION_ALREDY_DONE = 101;
    const MESSAGE_PAYMENT_BAZAR_OPERATION_SUCCESSFUL = 0;
    const MESSAGE_UNDEFINED_ERROR= -2;




    public function __construct(Payment $payment)
    {

        $this->payment = $payment;
    }

    public function initialize()
    {
        $this->payment->payment_id=null;
        $this->payment->payment_guid=null;
        $this->payment->user_id=null;
        $this->payment->currency_id=null;
        $this->payment->amount=null;
        $this->payment->description=null;
        $this->payment->authority=null;
        $this->payment->status=null;
        $this->payment->followup=null;
        $this->payment->from_app=null;
        $this->payment->deleted_at=null;
    }
    public function initializeByObject(Payment $payment)
    {
        if( $payment->payment_id != null){
            $this->payment->payment_id = $payment->payment_id;
        }
        if($payment->payment_guid != null){
            $this->payment->payment_guid = $payment->payment_guid;
        }
        if($payment->user_id != null){
            $this->payment->user_id = $payment->user_id;
        }
        if($payment->currency_id != null){
            $this->payment->currency_id = $payment->currency_id;
        }
        if( $payment->amount != null){
            $this->payment->amount = $payment->amount;
        }
        if( $payment->description != null){
            $this->payment->description = $payment->description;
        }
        if( $payment->authority != null){
            $this->payment->authority = $payment->authority;
        }
        if( $payment->status != null){
            $this->payment->status = $payment->status;
        }
        if( $payment->followup != null){
            $this->payment->followup = $payment->followup;
        }
        if( $payment->from_app != null){
            $this->payment->from_app = $payment->from_app;
        }

    }
    public function initializeByRequest($request)
    {

        $this->payment->payment_id=$request->input('payment_id');
        $this->payment->payment_guid=$request->input('payment_guid');
        $this->payment->user_id=$request->input('user_id');
        $this->payment->currency_id=$request->input('currency_id');
        $this->payment->amount=$request->input('amount');
        $this->payment->description=$request->input('description');
        $this->payment->authority=$request->input('authority');
        $this->payment->status=$request->input('status');
        $this->payment->followup=$request->input('followup');
        $this->payment->from_app=$request->input('from_app');

    }

    public function getFullDetailPayment( $params1,$params2,$params3,$distinct=null)
    {

        $query = $this->payment->newQuery();
        //
        if($params1!=null) {

            $query=\App\Utility::fillQueryAlias($query,$params1,$distinct);
        }
        $query =Self::makeWhere($query);

        //
        if($params2!=null) {
            $query=\App\Utility::fillQueryJoin($query,$params2);

        }
        //filtering
        if($params3!=null) {
            $query=\App\Utility::fillQueryFilter($query,$params3);
        }
        $payments = $query->get();
        return $payments;

//        return $query->get();
    }

    public function makeWhere($query){
        if($this->payment->payment_id != null){
            $query->where('	payment.'.'payment_id', '=', $this->payment->payment_id);
        }
        if($this->payment->payment_guid != null){
            $query->where('payment.'.'payment_guid', '=', $this->payment->payment_guid);
        }
        if($this->payment->user_id != null){
            $query->where('payment.'.'user_id', '=', $this->payment->user_id);
        }
        if($this->payment->currency_id != null){
            $query->where('	payment.'.'currency_id', '=', $this->payment->currency_id);
        }
        if( $this->payment->amount != null){
            $query->where('	payment.'.'amount', '=', $this->payment->amount);
        }
        if( $this->payment->description != null){
            $query->where('	payment.'.'description', 'like', '%'.$this->payment->description.'%');
        }

        if( $this->payment->authority != null){
            $query->where('	payment.'.'authority', '=', $this->payment->authority);
        }

        if( $this->payment->status != null){
            $query->where('	payment.'.'status', '=', $this->payment->status);
        }

        if( $this->payment->followup != null){
            $query->where('	payment.'.'followup', '=', $this->payment->followup);
        }

        if( $this->payment->from_app != null){
            $query->where('	payment.'.'from_app', '=', $this->payment->from_app);
        }


        return $query;
    }

    public function select()
    {
        $query = $this->payment->newQuery();
        if($this->payment->payment_id != null){
            $query->where('payment_id', '=', $this->payment->payment_id);
        }
        if($this->payment->payment_guid != null){
            $query->where('payment_guid', 'like', $this->payment->payment_guid);
        }

        $query->where('deleted_at', null);
        $country = $query->get();

        return $country;
    }

    public function all()
    {
        // no code all() method.
    }

    public function paginate()
    {
        // no code paginate() method.
    }

    public function store()
    {
        $this->payment->payment_guid = uniqid('',true);
        $this->payment->currency_id = CurrencyRepository::IRR;
        $this->payment->save();
    }

    public function findBy($field, $value)
    {
        // no code findBy() method.
    }

    public function exist($id, $guid)
    {
        // no code exist() method.
    }

    public function find($id)
    {
        return $this->payment->find($id);
    }

    public function findByIdAndGuid($id, $guid)
    {
        try
        {
            $query = $this->payment->newQuery();
            $query->where('payment_id', '=', $id);
            $query->where('payment_guid', 'like', $guid);
            $payments = $query->get();
            if(count($payments)==0)
                return false;
            return $payments[0];
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function set($id,$guid)
    {
        $this->payment->payment_id = $id;
        $this->payment->payment_guid = $guid;

    }

    public static function edit(Payment $payment)
    {
        DB::table('payment')
            ->where('authority', $payment->authority)
            ->where('deleted_at',null)
            ->update(['payment.status' => $payment->status,
                'payment.followup' => $payment->followup]);
    }

    public function delete()
    {
        // no code delete() method.
    }

    public static function getMessage($code = null)
    {

        switch ($code)
        {
            case self::MESSAGE_PAYMENT_BAZAR_OPERATION_SUCCESSFUL:
                return trans('messages.msg_PaymentBazarOperationSuccessful');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_BELOW_SILVER_LEVEL:
                return  trans('messages.msg_PaymentZarinPalBelowSilverLevel');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_DEFECTIVE_INFORMATION:
                return  trans('messages.msg_PaymentZarinPalDefectiveInformation');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_CAN_NOT_EDIT_REQUEST:
                return  trans('messages.msg_PaymentZarinPalCanNotEditRequest');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_FINANCIAL_OPERATION_NOT_FIND:
                return  trans('messages.msg_PaymentZarinPalFinancialOperationNotFind');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_ID_VALIDATION_TIME:
                return  trans('messages.msg_PaymentZarinPalIdValidationTime');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_INCORRECT_ADDETIONAL_DATA:
                return  trans('messages.msg_PaymentZarinPalIncorrectAdditionalData');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_INCORRECT_IP_OR_MERCHANT_CODE:
                return trans('messages.msg_PaymentZarinPalIncorrectIpOrMerchantCode');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_METHOD_ACCESS_NOT_ALLOWED:
                return  trans('messages.msg_PaymentZarinPalMethodAccessNotAllowed');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_OPERATION_SUCCESSFUL:
                return  trans('messages.msg_PaymentZarinPalOperationSuccessful');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_OPERATION_SUCCESSFUL_PAYMENT_VERIFICATION_ALREDY_DONE:
                return trans('messages.msg_PaymentZarinPalOperationSuccessfulPaymentVerificationAlredyDone');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_REQUEST_ARCHIVED:
                return  trans('messages.msg_PaymentZarinPalRequestArchived');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_REQUEST_NOT_FIND:
                return  trans('messages.msg_PaymentZarinPalRequestNotFind');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_SHAPARAK_RESTRICTION_PAYMENT:
                return trans('messages.msg_PaymentZarinPalShaparakRestrictionPayment');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_COUNT_EXCEEDED:
                return  trans('messages.msg_PaymentZarinPalTransactionCountExceeded');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_FAILED:
                return  trans('messages.msg_PaymentZarinPalTransactionFailed');
                break;
            case self::MESSAGE_PAYMENT_ZARINPAL_TRANSACTION_PAYMENT_MISMATCH:
                return  trans('messages.msg_PaymentZarinPalTransactionPaymentMismatch');
                break;
            case self::MESSAGE_UNDEFINED_ERROR:
                return  trans('messages.msg_ErrorUndefined');
                break;
            default:
                return  trans('messages.msg_ErrorItemNotExist');
                break;
        }
    }

    public function update($request)
    {
        // no code here....
    }

    public static function getAmount($productCode)
    {
        switch ($productCode)
        {
            case self::AT_1000:
                return "1000";
                break;
            case self::AT_2000:
                return "2000";
                break;
            case self::AT_5000:
                return "5000";
                break;
            case self::AT_10000:
                return "10000";
                break;
            case self::AT_20000:
                return "20000";
                break;
            case self::AT_30000:
                return "30000";
                break;
            case self::AT_50000:
                return "50000";
                break;
            case self::AT_100000:
                return "100000";
                break;

            default:
                return  "0";
                break;
        }
    }

    public static function isTokenExist($token)
    {
        $payments = DB::table('payment')
            ->where('authority', $token)
            ->get();
        if(sizeof($payments) > 0 )
            return true;
        return false;
    }
}