<?php

/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/13/2017
 * Time: 10:15 AM
 */

namespace App\Http\Controllers;

use App\Attendance;
use App\Repositories\AttendanceRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\UserCompanyRepository;
use App\RequestResponseAPI;
use App\User;
use App\Utility;
use Log;
use App\UserCompany;
use Carbon\Carbon;
use App\Company;
use Illuminate\Http\Request;
use App\Repositories\UserTypeRepository;
use JWTAuth;
use Validator;
use Redirect;
use Session;
use DB;
use File;
use App\Repositories\UserRepository;
use DateTimeZone;

class API_AttendanceController extends Controller
{
    protected $attendanceRepository;

    public function __construct(AttendanceRepository $attendance)
    {
            $this->attendanceRepository = $attendance;
    }

    public function apiIndex(Request $request)
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
            !$request->has('skip')||
            !$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_ATTENDANCE]);

        }

        try
        {
            //this variable count seconds of this module exist to access
            //this variable will get the seconds of this company access to attendane module
            //if this company end time to access to this module this variable is negative amount
            //if this company access deny t0 this module this variable is null
            $second=0;

            $userTemp = new User();
            $userTemp = $userTemp->find($request['user_id']);
            if(count($userTemp->userCompanies) == 0)
                return json_encode(['error' => RequestResponseAPI::ERROR_COMPANY_NOT_EXIST, 'tag' => RequestResponseAPI::TAG_INDEX_ATTENDANCE]);

            $request['user_company_id'] = $userTemp->userCompanies[0]->user_company_id;


//            Log::info('user_company_id: '.json_encode($request['user_company_id']));

            $user_company = new UserCompanyRepository(new UserCompany());
            $user_company = $user_company->find(intval($request->input('user_company_id')));

            $company = new CompanyRepository(new Company());
            $company = $company->find($user_company->company_id);
            $company_id = $user_company->company_id;

//            Log::info('$company_id: '.json_encode($company_id));


            if($user->user_type_id > UserTypeRepository::Admin) {
                //this variable count seconds of this module exist to access
                //this variable will get the seconds of this company access to attendane module
                //if this company end time to access to this module this variable is negative amount
                //if this company access deny t0 this module this variable is null

                list($second,$nothing)=app('App\Repositories\CompanyUserModuleRepository')->apiExist(ModuleRepository::attendanceModule,$company_id,null);
            }



            // select table
            $paramsObj1 = array(
                   array("se", "attendance", "attendance_id"),
                 array("se", "attendance", "attendance_guid"),
                 array("se", "attendance", "user_company_id"),
                array("se", "attendance", "start_date_time"),
                array("se", "attendance", "end_date_time"),
                array("se", "attendance", "is_mission"),
                array("se", "attendance", "coordinates"),
                array("se", "attendance", "type")

            );

            //join
            $paramsObj2 = array(
                array("join",
                    "user_company",
                    array("user_company.user_company_id", "=", "attendance.user_company_id")
                ),
                array("join",
                    "user",
                    array("user.user_id", "=", "user_company.user_id")
                ),

                array("join",
                    "company",
                    array("company.company_id", "=", "user_company.company_id")
                )
            );

            if($second>0){
                //for middle manager
                //for manager
                if($user->user_type_id ==UserTypeRepository::CEO)
                {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                           array("whereRaw",
                               "user_company.user_company_id='" . $user_company->user_company_id. "'"
                           ),
                    );
                }
                //foer middle manager
                elseif($user->user_type_id ==UserTypeRepository::MiddleCEO )
                {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                        array("whereRaw",
                            "user_company.user_company_id='" . $user_company->user_company_id. "'"
                        ),
//                    array("whereRaw",
//                        "user.user_type_id in (2,3)"
//                    )

                    );
                }
                //for employer
                elseif($user->user_type_id ==UserTypeRepository::Employee )
                {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                        array("whereRaw",
                            "user_company.user_company_id='" . $user_company->user_company_id. "'"
                        ),
                        array("whereRaw",
                            "user.user_id='".$user->user_id."'"
                        )
                    );
                }
            }
            else if($second<0 || $second===null){
                date_default_timezone_set($company->time_zone);
                $tomarow=Carbon::tomorrow();
                $oneMounthAgo=Carbon::now()->addDays(-31);
                //for middle manager
                //for manager
                if($user->user_type_id ==UserTypeRepository::CEO)
                {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                        array("whereRaw",
                            "user_company.user_company_id='" . $user_company->user_company_id. "'"
                        ),
                        array("between",
                            "attendance.start_date_time",$oneMounthAgo,$tomarow
                        )
                    );
                }
                //foer middle manager
                elseif($user->user_type_id ==UserTypeRepository::MiddleCEO )
                {
                     $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                         array("whereRaw",
                             "user_company.user_company_id='" . $user_company->user_company_id. "'"
                         ),
                        array("between",
                            "attendance.start_date_time",$oneMounthAgo,$tomarow
                        )

                    );
                }
                //for employer
                elseif($user->user_type_id ==UserTypeRepository::Employee )
                {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                        array("whereRaw",
                            "user.user_id='".$user->user_id."'"
                        ),
                        array("whereRaw",
                            "user_company.user_company_id='" . $user_company->user_company_id. "'"
                        ),
                        array("between",
                            "attendance.start_date_time",$oneMounthAgo,$tomarow
                        )

                    );
                }
            }
            else if($user->user_type_id ==UserTypeRepository::Admin)
            {
                //conditions
                //for super admin
                $paramsObj3=null;
            }


            if($request->has('start_date_time') || $request->has('end_date_time'))
            {
                if($request->input('start_date_time')!== '' || $request->input('end_date_time')!== '') {
                    if($request->has('start_date_time') && $request->has('end_date_time')){
                        $paramsObj3[] =  array("between",
                            "attendance.start_date_time",$request->input('start_date_time'),$request->input('end_date_time')
                        );
                    }
                    else if($request->has('start_date_time')){
                        $paramsObj3[] =   array("whereRaw",
                            "attendance.start_date_time >'".$request->input('start_date_time')."'"
                        );
                    }
                    else if($request->has('end_date_time')){
                        $paramsObj3[] =   array("whereRaw",
                            "attendance.end_date_time >'".$request->input('end_date_time')."'"
                        );
                    }
                }

            }

            /////add deleted at condition to query/////////

            $paramsObj3[] =   array("whereRaw",
                "attendance.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user_company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "company.deleted_at is null"
            );
            /// ///////////////////////////////////////

            $paramsObj3[] =   array("orderBy",
                "attendance.attendance_id", "DESC"
            );

            $paramsObj3[] =   array("skip",
                $request['skip']
            );
            $paramsObj3[] =   array("take",
                "20"
            );

            //GET LIST OF ATTENDANCCE
            list($provider,$Attendances)=$this->attendanceRepository->getFullDetailAttendace($paramsObj1,$paramsObj2,$paramsObj3,true);


            return json_encode(['token' => $token, 'attendances' => $Attendances,'error' => 0, 'tag' => RequestResponseAPI::TAG_INDEX_ATTENDANCE]);


        }
        catch (Exception $ex) {
            DB::rollback();

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_ATTENDANCE]);

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

//        $request['date_time'] = Carbon::now();
        //////set created_at manualy wrt company time_zone////////
        $company = UserRepository::getCompany($user->user_id,null);
        $now = Carbon::now(new DateTimeZone($company[0]->time_zone));
        $request['date_time'] = $now->toDateTimeString();
        /// /////////////////////////////////////////////////////

        //validation
        if (!$request->has('qr_code') ||
            !$request->has('date_time')||
            !$request->has('is_mission')||
            !$request->has('exiting')||
            !$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_ATTENDANCE]);

        }
        try {
//            //convert start and end time as persian to english date
//            $carbon  = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['date_time']);
//            $request['date_time'] = $carbon->format('Y/m/d H:i:s');

            $user_company_id = openssl_decrypt($request->input('qr_code'),
                "AES-128-ECB",
                AttendanceRepository::ENCRYPTION_PASSWORD);
            if($user_company_id == 'false' || $user_company_id == 'true')
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_ATTENDANCE]);

//            Log::info("ddd:".json_encode($user_company_id));
            $request['user_company_id'] = $user_company_id;
            $request['is_mission'] =intval($request->input('is_mission'));

            DB::beginTransaction();

            if($request->has('in_out_merge'))
            {
                $in_out_status = "2";//خروج و ورود با هم ثبت می شود

                $request['start'] = $request['date_time'];
                $request['end'] = $request['date_time'];
                if(!$request->has('type'))
                    $request['type'] = 0;
                $this->attendanceRepository = new AttendanceRepository(new Attendance());
                $this->attendanceRepository->initializeByRequest($request);
                $this->attendanceRepository->api_Store();
            }
            else
            {
                //Log::info('$user_company_id:'.json_encode($user_company_id));
                $this->attendanceRepository = AttendanceRepository::getLastAttendanceWithEmptyEndDate($user_company_id);
                if($this->attendanceRepository == null)
                {
                    $in_out_status = "0";//ورود ثبت می شود

                    $request['start'] = $request['date_time'];
                    if(!$request->has('type'))
                        $request['type'] = 0;
                    $this->attendanceRepository = new AttendanceRepository(new Attendance());
                    $this->attendanceRepository->initializeByRequest($request);
                    $this->attendanceRepository->api_Store();
                }
                else
                {
                    $in_out_status = "1";//خروج ثبت می شود

                    $this->attendanceRepository->updateEndDate($request['date_time'],$this->attendanceRepository->getId());
                }
            }



//            $storedUser = $storedUser->name.' '.$storedUser->family
            $storedUser = UserCompanyRepository::getUsers($user_company_id);
            $finalUser = new User();
            $finalUser -> name = $storedUser->name;
            $finalUser -> family = $storedUser->family;
            DB::commit();
            return json_encode(['status' => $in_out_status,'token' => $token,'user' => $finalUser,'error' => 0, 'tag' => RequestResponseAPI::TAG_STORE_ATTENDANCE]);

        }
        catch (Exception $ex) {
            DB::rollback();

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_STORE_ATTENDANCE]);

        }
    }

    public function apiStoreManual(Request $request)
    {
        ///////////////////check token validation/////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        ////////////////////////////////////////////////////////

        //validation
        if (!$request->has('start_date_time') ||
//            !$request->has('end_date_time')||
            !$request->has('is_mission')||
            !$request->has('user_id')||
            !$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_MANUAL_ATTENDANCE]);

        }
        try {
            $request['is_mission'] =intval($request->input('is_mission'));

            DB::beginTransaction();

            $userTemp = new User();
            $userTemp = $userTemp->find($request['user_id']);
            $request['user_company_id'] = $userTemp->userCompanies[0]->user_company_id;

            $request['start'] = $request['start_date_time'];
            if($request->has('end_date_time')) {

                $request['end'] = $request['end_date_time'];
            }
            if(!$request->has('type'))
                $request['type'] = 0;
            $this->attendanceRepository = new AttendanceRepository(new Attendance());
            $this->attendanceRepository->initializeByRequest($request);
            $this->attendanceRepository->api_Store();

            DB::commit();
            return json_encode(['token' => $token,'error' => 0, 'tag' => RequestResponseAPI::TAG_STORE_MANUAL_ATTENDANCE]);
        }
        catch (Exception $ex) {
            DB::rollback();

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_STORE_MANUAL_ATTENDANCE]);

        }
    }

    public function apiStoreAutoCeo(Request $request)
    {
        ///////////////////check token validation/////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        ////////////////////////////////////////////////////////

//        $request['date_time'] = Carbon::now();
        //////set created_at manualy wrt company time_zone////////
        $company = UserRepository::getCompany($user->user_id,null);
        $now = Carbon::now(new DateTimeZone($company[0]->time_zone));
        $request['date_time'] = $now->toDateTimeString();
        /// /////////////////////////////////////////////////////

        //validation
        if (!$request->has('company_id') ||
            !$request->has('is_mission')||
            !$request->has('exiting')||
            !$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_AUTO_CEO_ATTENDANCE]);

        }

        if($user->user_type_id != UserTypeRepository::CEO && $user->user_type_id != UserTypeRepository::MiddleCEO)
            return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_STORE_AUTO_CEO_ATTENDANCE]);

        try {
//            $company_rep = new CompanyRepository(new Company());
//            $company  = $company_rep->find($request->input('company_id'));

            $user_companies = DB::select( DB::raw('SELECT * from user_company where user_company.company_id = :mcompany_id and user_company.user_id = :muser_id and user_company.deleted_at IS null '), array(
                'mcompany_id' => $request->input('company_id'),
                'muser_id' => $user->user_id,
            ));

            if(count($user_companies) == 0)
                return json_encode(['error' => RequestResponseAPI::ERROR_ITEM_NOT_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_STORE_AUTO_CEO_ATTENDANCE]);

            $user_company_id = $user_companies[0]->user_company_id;
            $request['user_company_id'] = $user_company_id;
            $request['is_mission'] =intval($request->input('is_mission'));

            DB::beginTransaction();

            if($request->has('in_out_merge'))
            {
                $in_out_status = "2";//خروج و ورود با هم ثبت می شود

                $request['start'] = $request['date_time'];
                $request['end'] = $request['date_time'];
                if(!$request->has('type'))
                    $request['type'] = 1;
                $this->attendanceRepository = new AttendanceRepository(new Attendance());
                $this->attendanceRepository->initializeByRequest($request);
                $this->attendanceRepository->api_Store();
            }
            else
            {
                $this->attendanceRepository = AttendanceRepository::getLastAttendanceWithEmptyEndDate($user_company_id);
                if($this->attendanceRepository == null)
                {
                    $in_out_status = "0";//ورود ثبت می شود
                    $request['start'] = $request['date_time'];
                    if(!$request->has('type'))
                        $request['type'] = 1;
                    $this->attendanceRepository = new AttendanceRepository(new Attendance());
                    $this->attendanceRepository->initializeByRequest($request);
                    $this->attendanceRepository->api_Store();
                }
                else
                {
                    $in_out_status = "1";//خروج ثبت می شود
                    $this->attendanceRepository->updateEndDate($request['date_time'],$this->attendanceRepository->getId());
                }

            }

            DB::commit();
            return json_encode(['status' => $in_out_status, 'token' => $token,'error' => 0, 'tag' => RequestResponseAPI::TAG_STORE_AUTO_CEO_ATTENDANCE]);

        }
        catch (Exception $ex) {
            DB::rollback();

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_STORE_AUTO_CEO_ATTENDANCE]);

        }
    }

    public function apiStoreSelfLocation(Request $request)
    {
//        Log::info('req:'.json_encode($request->all()));
        ///////////////////check token validation/////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        ////////////////////////////////////////////////////////

//        $request['date_time'] = Carbon::now();
        //////set created_at manualy wrt company time_zone////////
        $company = UserRepository::getCompany($user->user_id,null);
        $now = Carbon::now(new DateTimeZone($company[0]->time_zone));
        $request['date_time'] = $now->toDateTimeString();
        /// /////////////////////////////////////////////////////

        //validation
        if (
            !$request->has('is_mission')||
            !$request->has('exiting')||
            !$request->has('tag')||
            !$request->has('coordinates')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_SELF_LOCATION_ATTENDANCE]);

        }

        try {
            // meysam - check if company zone exist
            if(is_null($company[0]->zone))
                return json_encode(['error' => RequestResponseAPI::ERROR_COMPANY_ZONE_NOT_DEFINED_CODE, 'tag' => RequestResponseAPI::TAG_STORE_SELF_LOCATION_ATTENDANCE]);

            ////////////////////////////////////////////


            //meysam - check if coordinates is in company zone
            $point = explode(",",$request->input('coordinates'));
//            Log::info('point:'.json_encode($point));
//            Log::info('company:'.json_encode($company[0]));
            $is_in_company_zone = Utility::pointInCircle($point, explode(",", $company[0]->zone));
//            Log::info('is_in_company_zone:'.json_encode($is_in_company_zone));
            if(!$is_in_company_zone)
            {
                return json_encode(['error' => RequestResponseAPI::ERROR_LOCATION_NOT_IN_ZONE_CODE, 'tag' => RequestResponseAPI::TAG_STORE_SELF_LOCATION_ATTENDANCE]);
            }
            ////////////////////////////////////////////////////////
//            $company_rep = new CompanyRepository(new Company());
//            $company  = $company_rep->find($request->input('company_id'));

            $user_companies = DB::select( DB::raw('SELECT * from user_company where user_company.company_id = :mcompany_id and user_company.user_id = :muser_id and user_company.deleted_at IS null '), array(
                'mcompany_id' => $company[0]->company_id,
                'muser_id' => $user->user_id,
            ));

            if(count($user_companies) == 0)
                return json_encode(['error' => RequestResponseAPI::ERROR_ITEM_NOT_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_STORE_SELF_LOCATION_ATTENDANCE]);

            //meysam - check if user allowed to self roll call
            if($user_companies[0]->self_roll_call == 0)
                return json_encode(['error' => RequestResponseAPI::ERROR_USER_NOT_ALLOWED_TO_SELF_ROLL_CODE, 'tag' => RequestResponseAPI::TAG_STORE_SELF_LOCATION_ATTENDANCE]);

            ////////////////////////////////////////////
            $user_company_id = $user_companies[0]->user_company_id;
            $request['user_company_id'] = $user_company_id;
            $request['is_mission'] =intval($request->input('is_mission'));

            DB::beginTransaction();


            if($request->has('in_out_merge'))
            {
                $in_out_status = "2";//خروج و ورود با هم ثبت می شود

                $request['start'] = $request['date_time'];
                $request['end'] = $request['date_time'];
                if(!$request->has('type'))
                    $request['type'] = 1;
                $this->attendanceRepository = new AttendanceRepository(new Attendance());
                $this->attendanceRepository->initializeByRequest($request);
                $this->attendanceRepository->api_Store();
            }
            else
            {

                $this->attendanceRepository = AttendanceRepository::getLastAttendanceWithEmptyEndDate($user_company_id);
                if($this->attendanceRepository == null)
                {
                    $in_out_status = "0";//ورود ثبت می شود
                    $request['start'] = $request['date_time'];
//                if(!$request->has('type'))
                    $request['type'] = 1;
                    $this->attendanceRepository = new AttendanceRepository(new Attendance());
                    $this->attendanceRepository->initializeByRequest($request);
                    $this->attendanceRepository->api_Store();
                }
                else
                {
                    $in_out_status = "1";//خروج ثبت می شود
                    $this->attendanceRepository->updateEndDate($request['date_time'],$this->attendanceRepository->getId());
                }
            }



            DB::commit();
            return json_encode(['status' => $in_out_status,'token' => $token,'error' => 0, 'tag' => RequestResponseAPI::TAG_STORE_SELF_LOCATION_ATTENDANCE]);

        }
        catch (Exception $ex) {
            DB::rollback();

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_STORE_SELF_LOCATION_ATTENDANCE]);

        }
    }

    public function apiEdit(Request $request)
    {

        ///////////////////check token validation/////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        ////////////////////////////////////////////////////////

        //validation
        if (!$request->has('start_date_time') ||
//            !$request->has('end_date_time')||
            !$request->has('is_mission')||
            !$request->has('user_id')||
            !$request->has('tag')||
            !$request->has('attendance_id')||
            !$request->has('attendance_guid')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_ATTENDANCE]);

        }

        try
        {

            $userTemp = new User();
            $userTemp = $userTemp->find($request['user_id']);
            $request['user_company_id'] = $userTemp->userCompanies[0]->user_company_id;


            //save and update attendance
            $request['start'] = $request['start_date_time'];
            if($request->has('end_date_time')) {

                $request['end'] = $request['end_date_time'];
            }

            $this->attendanceRepository = new AttendanceRepository(new Attendance());
//            $this->attendanceRepository->initialize();
//            $this->attendanceRepository->initializeByRequest($request);
            $this->attendanceRepository->update($request);


            return json_encode(['token' => $token,'error' => 0, 'tag' => RequestResponseAPI::TAG_EDIT_ATTENDANCE]);
        }
        catch (Exception $ex) {
            DB::rollback();

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_ATTENDANCE]);

        }

    }

    public function apiDelete(Request $request)
    {
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::authenticate($token);

        try {

            if (!$request->has('attendance_id') ||
                !$request->has('attendance_guid')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_ATTENDANCE]);

            }
            $attendance_id = $request->input('attendance_id');
            $attendance_guid = $request->input('attendance_guid');
            $this->attendanceRepository->initialize();
            $this->attendanceRepository->set($attendance_id, $attendance_guid);
            $this->attendanceRepository->delete();

            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_DELETE_ATTENDANCE]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_COMPANY_STORE_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_ATTENDANCE]);
        }

    }

}
