<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 2/25/2017
 * Time: 10:34 AM
 */

namespace App;

use Log;
use DB;

class RequestResponseAPI
{

    //tag codes

    //public
    const TAG_UNDEFINED = "undefined";

    //home
    const TAG_INDEX_HOME = "index_home";
    const TAG_GET_VERSION_HOME = "get_version_home";
    const TAG_GET_PUBLIC_KEY_HOME = "get_public_key";

    // tag user
    const TAG_LOGIN_USER = "login_user";
    const TAG_REGISTER_USER = "register_user";
    const TAG_EDIT_USER = "edit_user";
    const TAG_LOGOUT_USER = "logout_user";
    const TAG_DELETE_USER = "delete_user";
    const TAG_REMOVE_PHONE_CODE_USER = "remove_phone_code";

    //tag password
    const TAG_RESET_PASSWORD = "reset_password";
    const TAG_FORGET_PASSWORD= "forget_password";

    //company
    const TAG_INDEX_COMPANY = "index_company";
    const TAG_STORE_COMPANY = "store_company";
    const TAG_ADD_MEMBER_COMPANY = "add_member_company";
    const TAG_LIST_MEMBERS_COMPANY = "list_members_company";
    const TAG_EDIT_COMPANY = "edit_company";
    const TAG_DELETE_COMPANY = "delete_company";
    const TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY = "change_self_roll_call_user_company";

    //mission
    const TAG_INDEX_MISSION = "index_mission";
    const TAG_STORE_MISSION = "store_mission";
    const TAG_EDIT_MISSION = "edit_mission";
    const TAG_DELETE_MISSION = "delete_mission";
    const TAG_LIST_MEMBERS_MISSION = "list_members_mission";

    //track
    const TAG_STORE_TRACK = "store_track";
    const TAG_INDEX_TRACK = "index_track";
    const TAG_LIST_TRACK  = "list_track";
    const TAG_DELETE_TRACK  = "delete_track";
    const TAG_GENERATE_TRACK  = "generate_track";
    const TAG_STORE_BULK_TRACK = "store_bulk_track";

    //attendance
    const TAG_STORE_ATTENDANCE = "store_attendance";
    const TAG_STORE_MANUAL_ATTENDANCE = "store_attendance_manual";
    const TAG_EDIT_ATTENDANCE = "edit_attendance";
    const TAG_DELETE_ATTENDANCE = "delete_attendance";
    const TAG_INDEX_ATTENDANCE = "index_attendance";
    const TAG_STORE_AUTO_CEO_ATTENDANCE = "store_attendance_ceo_auto";
    const TAG_STORE_SELF_LOCATION_ATTENDANCE = "store_attendance_self_location";


    //module
    const TAG_INDEX_COMPANY_MODULE = "index_company_module";
    const TAG_INDEX_USER_MODULE = "index_user_module";

    //company user module
    const TAG_STORE_COMPANY_USER_MODULE = "store_company_user_module";

    //country
    const TAG_INDEX_COUNTRY = "index_country";


    //payment
    const TAG_INDEX_PAYMENT = "index_payment";
    const TAG_KEY_PAYMENT = "key_payment";
    const TAG_STORE_PAYMENT = "store_payment";

    //Language
    const TAG_INDEX_LANGUAGE = "index_language";

    // ERROR codes
    //public
    const ERROR_UNDEFINED_CODE = -1;
    const ERROR_ITEM_EXIST_CODE = 7;
    const ERROR_INSERT_FAIL_CODE = 8;
    const ERROR_TOKEN_MISMACH_CODE = 4;
    const ERROR_DEFECTIVE_INFORMATION_CODE = 5;
    const ERROR_TOKEN_BLACKLISTED_CODE = 6;
    const ERROR_INVALID_FILE_SIZE_CODE = 12;
    const ERROR_UPDATE_FAIL_CODE = 14;
    const ERROR_DELETE_FAIL_CODE = 16;
    const ERROR_OPERATION_FAIL_CODE = 17;
    const ERROR_ITEM_NOT_EXIST_CODE = 23;
    const ERROR_WRONG_PHONE_CODE = 25;

    //user
    const ERROR_USER_EXIST_CODE = 1;
    const ERROR_REGISTER_FAIL_CODE = 2;
    const ERROR_LOGIN_FAIL_CODE = 3;
    const ERROR_UNAUTHURIZED_USER_CODE = 15;
    const ERROR_EMAIL_EXIST_CODE = 22;
    const ERROR_NOT_ENOUGH_CHARGE_CODE = 24;



    //password
    const ERROR_INVALID_PASSWORD_CODE = 20;
    const ERROR_MISMATCH_PASSWORD_CODE = 21;


    //company
    const ERROR_COMPANY_EXIST_CODE = 9;
    const ERROR_COMPANY_STORE_CODE = 10;
    const ERROR_COMPANY_NOT_EXIST = 27;

    //module
    const ERROR_MODULE_EXPIRE_CODE = 11;

    //track
    const ERROR_TRACK_STORE_CODE = 13;
    const ERROR_TRACK_INDEX_CODE = 18;
    const ERROR_TRACK_LIST_CODE = 19;


    //home
    const ERROR_INVALID_PUBLIC_KEY_CODE = 26;


    //mission
    ///////////////////////////////

    //attendance
    ///////////////////////////////
    const ERROR_LOCATION_NOT_IN_ZONE_CODE = 28;
    const ERROR_USER_NOT_ALLOWED_TO_SELF_ROLL_CODE = 29;
    const ERROR_COMPANY_ZONE_NOT_DEFINED_CODE = 30;
    /// //module
    ///////////////////////////////

    /////////////////////////////Public Keys////////////////////////////////
    const PUBLIC_KEY_CAFE_BAZAR = "cafe_bazar";
    const PUBLIC_KEY_AVAL_MARKET = "aval_market";
    const PUBLIC_KEY_CHARKHONE = "charkhone";
    /// ///////////////////////////////////////////////////////////
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
            case self::ERROR_LOGIN_FAIL_CODE:
                $this->Text =  trans('messages.msg_ErrorLoginFail');
                break;
            case self::ERROR_REGISTER_FAIL_CODE:
                $this->Text =  trans('messages.msg_ErrorRegisterFail');
                break;
            case self::ERROR_USER_EXIST_CODE:
                $this->Text = trans('messages.msg_ErrorUserExist');
                break;
            case self::ERROR_TOKEN_MISMACH_CODE:
                $this->Text = trans('messages.msg_ErrorTokenMismach');
                break;
            case self::ERROR_DEFECTIVE_INFORMATION_CODE:
                $this->Text = trans('messages.msg_ErrorDefectiveInformation');
                break;
            case self::ERROR_TOKEN_BLACKLISTED_CODE:
                $this->Text = trans('messages.msg_ErrorTokenBlaklisted');
                break;
            case self::ERROR_INSERT_FAIL_CODE:
                $this->Text =  trans('messages.msg_ErrorRegisterFail');
                break;
            case self::ERROR_ITEM_EXIST_CODE:
                $this->Text = trans('messages.msg_ErrorUserExist');
                break;
            case self::ERROR_COMPANY_EXIST_CODE:
                $this->Text = trans('messages.msg_ErrorCompanyExist');
                break;
            case self::ERROR_COMPANY_STORE_CODE:
                $this->Text = trans('messages.msg_ErrorCompanyStore');
                break;
            case self::ERROR_MODULE_EXPIRE_CODE:
                $this->Text = trans('messages.msg_ErrorCompanyStore');
                break;
            case self::ERROR_INVALID_FILE_SIZE_CODE:
                $this->Text = trans('messages.msg_InvalidFileSize');
                break;
            case self::ERROR_TRACK_STORE_CODE:
                $this->Text = trans('messages.msg_ErrorTrackStore');
                break;
            case self::ERROR_UPDATE_FAIL_CODE:
                $this->Text = trans('messages.msg_ErrorUpdateFail');
                break;
            case self::ERROR_UNAUTHURIZED_USER_CODE:
                $this->Text = trans('messages.msg_ErrorUnauthorizedUser');
                break;
            case self::ERROR_DELETE_FAIL_CODE:
                $this->Text = trans('messages.msg_ErrorDeleteFail');
                break;
            case self::ERROR_OPERATION_FAIL_CODE:
                $this->Text = trans('messages.msg_ErrorOperationFail');
                break;
            case self::ERROR_TRACK_INDEX_CODE:
                $this->Text = trans('messages.msg_ErrorTrackIndex');
                break;
            case self::ERROR_TRACK_LIST_CODE:
                $this->Text = trans('messages.msg_ErrorTrackList');
                break;
            case self::ERROR_INVALID_PASSWORD_CODE:
                $this->Text = trans('messages.msg_ErrorInvalidPassword');
                break;
            case self::ERROR_MISMATCH_PASSWORD_CODE:
                $this->Text = trans('messages.msg_ErrorMismatchPassword');
                break;
            case self::ERROR_ITEM_NOT_EXIST_CODE:
                $this->Text = trans('messages.msg_ErrorItemNotExist');
                break;
            case self::ERROR_NOT_ENOUGH_CHARGE_CODE:
                $this->Text = trans('messages.msg_ErrorNotEnoughCharge');
                break;
            case self::ERROR_WRONG_PHONE_CODE:
                $this->Text = trans('messages.msg_ErrorWrongPhoneCode');
                break;
            case self::ERROR_INVALID_PUBLIC_KEY_CODE:
                $this->Text = trans('messages.msg_ErrorInvalidPublicKey');
                break;
            case self::ERROR_LOCATION_NOT_IN_ZONE_CODE:
                $this->Text = trans('messages.msg_LocationNotInZone');
                break;
            case self::ERROR_USER_NOT_ALLOWED_TO_SELF_ROLL_CODE:
                $this->Text = trans('messages.msg_ErrorUserNotAllowedToSelfRoll');
                break;
            case self::ERROR_COMPANY_ZONE_NOT_DEFINED_CODE:
                $this->Text = trans('messages.msg_ErrorCompanyZoneIsNotSet');
                break;
            default:
                $this->Text = trans('messages.msg_ErrorUndefined');
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
            case self::ERROR_LOGIN_FAIL_CODE:
                $this->Text =  trans('messages.msg_ErrorLoginFail');
                break;
            case self::ERROR_REGISTER_FAIL_CODE:
                $this->Text =  trans('messages.msg_ErrorRegisterFail');
                break;
            case self::ERROR_USER_EXIST_CODE:
                $this->Text =  trans('messages.msg_ErrorUserExist');
                break;
            default:
                $this->Text =  trans('messages.msg_ErrorUndefined');
                break;
        }
    }

    public static function getPublicKeyByName($name)
    {
        switch ($name)
        {
            case self::PUBLIC_KEY_CAFE_BAZAR:
                return "MIHNMA0GCSqGSIb3DQEBAQUAA4G7ADCBtwKBrwCdZI9uypEYCKrTx5g8G/wbLldY05GA1tz32gIW68d/Zq9KGiqC/dG7yHIXnsf1cY3ZKQ1ssnUDgbHonun6D6YCV18gaFCLfsE+7o5iLHWlYVzu6beqWYHWnTTFWPH30p1dQAzvK1EL2PjrZ1WUQfBXTlppwVA6vrqnASjgYvfTDDszsYJsxlrt+c0VAOod1zqqBSKCL1eLad81Z8u/DR8aPahKp4QSfGw3toME8akCAwEAAQ==";
            case self::PUBLIC_KEY_AVAL_MARKET:
                return "aval market public key";
            case self::PUBLIC_KEY_CHARKHONE:
                return "charkhone public key";
            default:
                return "";
        }
    }

}