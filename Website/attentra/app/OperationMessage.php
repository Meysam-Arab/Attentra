<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 2/2/2017
 * Time: 10:18 AM
 */

namespace App;


use Illuminate\Support\Facades\Lang;
use Log;



class OperationMessage
{
//    public $Code;
//    public $Text;

    /////////////Texts/////////////
    //public
//    public static $OperationNotDefinedText ='';
//    public static $OperationErrorText= trans('message.txt_OperationError');
//    public static $OperationFailText = trans('message.txt_OperationFail');
//    public static $OperationSuccessText = trans('message.txt_OperationSuccess');
    ////////////////////////////////
    //////////////Codes/////////////
    //public
    const OperationNotDefinedCode= -1;
    const OperationErrorCode= 0;
    const OperationFailCode = 1;
    const OperationSuccessCode = 2;
    const OperationUnauthorizedCode = 9;

    ////////////////////////////////
    const UserNotAllowed = 3;
    ///////////////////////////////
    const DeadLineReached = 4;
    const ItemNotFind = 5;
    const ChangeDenied = 6;

    const NotEnoughBalance = 7;
    const AlreadyExist = 8;
    const InsufficientStorage = 10;
    const InsufficientLink = 11;
    const NotActiveThisModule=12;
    const EndTimeActiveThisModule=13;
    const NotExistCompanyYet=14;
    const NotEnouphMoney=15;
    const NotValidDateFormat=16;

    ////user related message///


    ////////////////////////////




    const RedMessages = [-1,0,1,3,4,5,6,7,8,9,10,11,12,13,14,15,16];
    const GreenMessages = [2];


    public function initialize()
    {
        $this->Code = null;
        $this->Text = null;
    }

    public function initializeByCode($code)
    {
        $this->Code = $code;
        switch ($this->Code)
        {
            case self::OperationErrorCode:
                $this->Text =  trans('messages.txt_OperationError');
                break;
            case self::OperationFailCode:
                $this->Text =  trans('messages.txt_OperationFail');
                break;
            case self::OperationSuccessCode:
                $this->Text =  trans('messages.txt_OperationSuccess');
                break;
            case self::UserNotAllowed:
                $this->Text =  trans('messages.txt_UserNotAllowed');
                break;
            case self::DeadLineReached:
                $this->Text =  trans('messages.txt_DeadLineReached');
                break;
            case self::ItemNotFind:
                $this->Text =  trans('messages.txt_ItemNotFind');
                break;
            case self::ChangeDenied:
                $this->Text =  trans('messages.txt_ChangeDenied');
                break;
            case self::NotEnoughBalance:
                $this->Text =  trans('messages.txt_NotEnoughBalance');
                break;
            case self::AlreadyExist:
                $this->Text =  trans('messages.txt_AlreadyExist');
                break;
            case self::OperationUnauthorizedCode:
                $this->Text =  trans('messages.txt_OperationUnauthorized ');
                break;
            case self::InsufficientStorage:
                $this->Text =  trans('messages.txt_InsufficientStorage ');
                break;
            case self::InsufficientLink:
                $this->Text =  trans('messages.txt_InsufficientLink ');
                break;
            case self::NotActiveThisModule:
                $this->Text =  trans('messages.txt_NotActiveThisModule');
                break;
            case self::EndTimeActiveThisModule:
                $this->Text =  trans('messages.txt_EndTimeActiveThisModule');
                break;
            case self::NotExistCompanyYet:
                $this->Text =  trans('messages.txt_NotExistCompanyYet');
                break;
            case self::NotEnouphMoney:
                $this->Text =  trans('messages.txt_NotEnophMoney');
                break;
            case self::NotValidDateFormat:
                $this->Text =  trans('messages.txt_NotValidDateFormat');
                break;

            default:
                $this->Text =  trans('messages.txt_OperationNotDefined');
                break;
        }
    }

    public function getMessage($code = null)
    {
        if($code != null)
        {
            $this->Code = $code;
        }

        switch ($this->Code)
        {
            case self::OperationErrorCode:
                $this->Text =  trans('messages.txt_OperationError');
                break;
            case self::OperationFailCode:
                $this->Text =  trans('messages.txt_OperationFail');
                break;
            case self::OperationSuccessCode:
                $this->Text =  trans('messages.txt_OperationSuccess');
                break;
            case self::OperationUnauthorizedCode:
                $this->Text =  trans('messages.txt_OperationUnauthorized');
                break;
            default:
                $this->Text =  trans('message.txt_OperationNotDefined');
                break;
        }
    }
}