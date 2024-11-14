<?php

namespace App\Http\Controllers;
use App\Repositories\AttendanceRepository;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use App\OperationMessage;
use Illuminate\Support\Facades\Input;
use App\Repositories\UserTypeRepository;
use Morilog\Jalali\jDateTime;
use DateTimeZone;
//use ViewComponents\Eloquent\EloquentDataProvider;
use App\Attendance;
use Validator;
use Redirect;
use Session;
use Auth;
use DB;
use File;
use Log;

use Exception;
use App\Repositories\LogEventRepository;
use Route;
use App\Repositories\UserRepository;



class AttendanceController extends Controller
{
    protected $attendanceRepository;

    public function __construct(AttendanceRepository $atten)
    {
        try
        {
            $this->attendanceRepository = $atten;

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }

    }

    public function index($company_id = null , $company_guid = null)
    {
//        log::info($last_id." indexqqqqqqqqqqqqqqqqqqq");
        try
        {
            //this variable is index of company session
            $count=0;

            //this variable count seconds of this module exist to access
            //this variable will get the seconds of this company access to attendane module
            //if this company end time to access to this module this variable is negative amount
            //if this company access deny t0 this module this variable is null
            $second=0;
            //if user was employ or middle manager just has a one company id
            if(Auth::user()->user_type_id !=UserTypeRepository::CEO && Auth::user()->user_type_id !=UserTypeRepository::Admin) {
                $company_id = session('companiesId0');
            }
            // if user was employ , middle manager or manager must set the session
            if( Auth::user()->user_type_id ==UserTypeRepository::CEO){
                //get index of session for company data
                //this session before set when user login in company controller in profileset method
                for($index=0;$index< session('CompanyCount');$index++){
                    if(session('companiesId'.$index)==$company_id){
                        $count=$index;
                        break;
                    }
                }
            }
            if(Auth::user()->user_type_id > 0) {
                //this variable count seconds of this module exist to access
                //this variable will get the seconds of this company access to attendane module
                //if this company end time to access to this module this variable is negative amount
                //if this company access deny t0 this module this variable is null

                list($second,$nothing)=app('App\Repositories\CompanyUserModuleRepository')->exist(1,$company_id,null);
            }

            //         select table
            $paramsObj1 = array(
                array("st", "attendance"),
                array("as","user","name","uname"),
//            array("as","user_company","user_company_id","user_company_id"),
//            array("as","user_company","user_company_guid","user_company_guid"),
                array("se","user","family"),
                array("as","company","name","cname"),

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
                if(Auth::user()->user_type_id ==1)
                {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        )
                    );
                }
                //foer middle manager
                elseif(Auth::user()->user_type_id ==2 )
                {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
//                    array("whereRaw",
//                        "user.user_type_id in (2,3)"
//                    )

                    );
                }
                //for employer
                elseif(Auth::user()->user_type_id ==3 )
                {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                        array("whereRaw",
                            "user.user_id='".Auth::user()->user_id."'"
                        )
                    );
                }
            }
            else if($second<0 || $second===null){
                $tomarow=Carbon::tomorrow();
                $oneMounthAgo=Carbon::now()->addDays(-31);
                //for middle manager
                //for manager
                if(Auth::user()->user_type_id ==1)
                {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $oneMounthAgo. "'"
                        )
                    );
                }
                //foer middle manager
                elseif(Auth::user()->user_type_id ==2 )
                {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
//                    array("whereRaw",
//                        "user.user_type_id in (2,3)"
//                    ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $oneMounthAgo. "'"
                        )

                    );
                }
                //for employer
                elseif(Auth::user()->user_type_id ==3 )
                {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                        array("whereRaw",
                            "user.user_id='".Auth::user()->user_id."'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $oneMounthAgo. "'"
                        )

                    );
                }
            }
            else if(Auth::user()->user_type_id ==0)
            {
                //conditions
                //for super admin
                $paramsObj3=null;
            }
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "user_company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "attendance.deleted_at is null"
            );

            /// ///////////////////////////////////////

            //GET LIST OF ATTENDANCCE
            list($provider,$AttendanceRepository)=$this->attendanceRepository->getFullDetailAttendace($paramsObj1,$paramsObj2,$paramsObj3,true);


            return view('attendance/index', ['AttendanceRepositories' => $AttendanceRepository])
                ->with(['second'=>$second,'company_id'=>$company_id,'company_guid'=>$company_guid]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }


    }

    public function indexreprtAttendanceHourByValue(Request $request){
        try
        {
            if(trim($request['end'])!=null){
                $dt = Carbon::parse($this->convert($request['end']));
//              var_dump($dt->year);
                //convert start and end time as persian to english date
                $request['end'] = $this->convert((string)$request->input('end'));
                $carbon=$request['end'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['end']);
                $request['end']=$carbon->format('Y-m-d H:i:s');
            }else{

                $request['end']=Carbon::now()->addDays(365);
            }
            if(trim($request['start'])!=null){
                $request['start'] = $this->convert((string)$request->input('start'));
                $carbon=$request['start'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['start']);
                $request['start']=$carbon->format('Y-m-d H:i:s');
            }else{

                $request['start']=Carbon::now()->addDays(-365);
            }
            //this variable is index of company session
            $count=0;

            //this variable count seconds of this module exist to access
            //this variable will get the seconds of this company access to attendane module
            //if this company end time to access to this module this variable is negative amount
            //if this company access deny t0 this module this variable is null
            $second=0;
            //if user was employ or middle manager just has a one company id
            if(Auth::user()->user_type_id !=UserTypeRepository::CEO && Auth::user()->user_type_id !=UserTypeRepository::Admin) {
                $company_id = session('companiesId0');
            }
            // if user was employ , middle manager or manager must set the session
            if( Auth::user()->user_type_id ==UserTypeRepository::CEO){
                //get index of session for company data
                //this session before set when user login in company controller in profileset method
                for($index=0;$index< session('CompanyCount');$index++){
                    if(session('companiesId'.$index)==$request['company_id']){
                        $count=$index;
                        break;
                    }
                }
            }
            if(Auth::user()->user_type_id > 0) {
                //this variable count seconds of this module exist to access
                //this variable will get the seconds of this company access to attendane module
                //if this company end time to access to this module this variable is negative amount
                //if this company access deny t0 this module this variable is null

                list($second,$nothing)=app('App\Repositories\CompanyUserModuleRepository')->exist(1,$request['company_id'],null);
            }

            //         select table
            $paramsObj1 = array(
                array("st", "attendance"),
                array("as","user","name","uname"),
//            array("as","user_company","user_company_id","user_company_id"),
//            array("as","user_company","user_company_guid","user_company_guid"),
                array("se","user","family"),
                array("as","company","name","cname"),

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
            log::info('1111111111');
            if($second>0){
                //for middle manager
                //for manager
                if(Auth::user()->user_type_id ==1)
                {
                    log::info('2222222');
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $request['company_id']. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $request['start']. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time<'" . $request['end']. "'"
                        ),
                    );
                }
                //foer middle manager
                elseif(Auth::user()->user_type_id ==2 )
                {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $request['company_id']. "'"
                        ),
                        array("whereRaw",
                            "user.user_id='".Auth::user()->user_id."'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $request['start']. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time<'" . $request['end']. "'"
                        ),

                    );
                }
                //for employer
                elseif(Auth::user()->user_type_id ==3 )
                {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $request['company_id']. "'"
                        ),
                        array("whereRaw",
                            "user.user_id='".Auth::user()->user_id."'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $request['start']. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time<'" . $request['end']. "'"
                        ),
                    );
                }
            }
            else if($second<0 || $second===null){
                $tomarow=Carbon::tomorrow();
                $oneMounthAgo=Carbon::now()->addDays(-31);
                //for middle manager
                //for manager
                if(Auth::user()->user_type_id ==1)
                {
                    log::info('3333333333333');
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $request['company_id']. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $oneMounthAgo. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $request['start']. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time<'" . $request['end']. "'"
                        ),
                    );
                }
                //foer middle manager
                elseif(Auth::user()->user_type_id ==2 )
                {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $request['company_id']. "'"
                        ),
//                    array("whereRaw",
//                        "user.user_type_id in (2,3)"
//                    ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $oneMounthAgo. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $request['start']. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time<'" . $request['end']. "'"
                        ),

                    );
                }
                //for employer
                elseif(Auth::user()->user_type_id ==3 )
                {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $request['company_id']. "'"
                        ),
                        array("whereRaw",
                            "user.user_id='".Auth::user()->user_id."'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $oneMounthAgo. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $request['start']. "'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time<'" . $request['end']. "'"
                        ),

                    );
                }
            }
            else if(Auth::user()->user_type_id ==0)
            {
                //conditions
                //for super admin
                $paramsObj3=null;
            }
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "user_company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "attendance.deleted_at is null"
            );

            /// ///////////////////////////////////////

            //GET LIST OF ATTENDANCCE
            list($provider,$AttendanceRepository)=$this->attendanceRepository->getFullDetailAttendace($paramsObj1,$paramsObj2,$paramsObj3,true);


            return view('attendance/index', ['AttendanceRepositories' => $AttendanceRepository])
                ->with(['second'=>$second,'company_id'=>$request['company_id'],'company_guid'=>$request['company_guid']]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }
    }

    public function indexOfThisUser($company_id = null, $company_guid = null,$userId=null,$userGuid=null)
    {
//        log::info($last_id." indexqqqqqqqqqqqqqqqqqqq");
        try
        {
            //this variable is index of company session
            $count=0;

            //this variable count seconds of this module exist to access
            //this variable will get the seconds of this company access to attendane module
            //if this company end time to access to this module this variable is negative amount
            //if this company access deny t0 this module this variable is null
            $second=0;
            //if user was employ or middle manager just has a one company id
            if(Auth::user()->user_type_id !=UserTypeRepository::CEO && Auth::user()->user_type_id !=UserTypeRepository::Admin) {
                $company_id = session('companiesId0');
            }
            // if user was employ , middle manager or manager must set the session
            if( Auth::user()->user_type_id ==UserTypeRepository::CEO){
                //get index of session for company data
                //this session before set when user login in company controller in profileset method
                for($index=0;$index< session('CompanyCount');$index++){
                    if(session('companiesId'.$index)==$company_id){
                        $count=$index;
                        break;
                    }
                }
            }
            if(Auth::user()->user_type_id > 0) {
                //this variable count seconds of this module exist to access
                //this variable will get the seconds of this company access to attendane module
                //if this company end time to access to this module this variable is negative amount
                //if this company access deny t0 this module this variable is null

                list($second,$nothing)=app('App\Repositories\CompanyUserModuleRepository')->exist(1,$company_id,null);
            }

            //         select table
            $paramsObj1 = array(
                array("st", "attendance"),
                array("as","user","name","uname"),
//            array("as","user_company","user_company_id","user_company_id"),
//            array("as","user_company","user_company_guid","user_company_guid"),
                array("se","user","family"),
                array("as","company","name","cname"),

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
                //for employer


                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                        array("whereRaw",
                            "user.user_id='".$userId."'"
                        )
                    );

            }
            else if($second<0 || $second===null){
                $tomarow=Carbon::tomorrow();
                $oneMounthAgo=Carbon::now()->addDays(-31);


                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id. "'"
                        ),
                        array("whereRaw",
                            "user.user_id='".$userId."'"
                        ),
                        array("whereRaw",
                            "attendance.start_date_time>'" . $oneMounthAgo. "'"
                        )

                    );

            }
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "user_company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "attendance.deleted_at is null"
            );

            /// ///////////////////////////////////////

            //GET LIST OF ATTENDANCCE
            list($provider,$AttendanceRepository)=$this->attendanceRepository->getFullDetailAttendace($paramsObj1,$paramsObj2,$paramsObj3,true);


            return view('attendance/index', ['AttendanceRepositories' => $AttendanceRepository])
                ->with(['second'=>$second,'company_id'=>$company_id,'company_guid'=>$company_guid]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }


    }

    public function create($company_id,$company_guid)
    {
        try
        {
            $users=app('App\Repositories\UserRepository')->GetListUsersOfCompany($company_id,$company_guid);
            return view('attendance/create') ->with(['company_id'=>$company_id,'company_guid'=>$company_guid,'users'=>$users]);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }
    }

    public function WorkHourThisUser(Request $request){
        Log::info('in edit request:'.json_encode($request->request->all()));
        return "OKKKKKKKKKKKKKKKKKKKKKKKKKK";
    }

    public function store(Request $request)
    {
        try
        {
            if(trim($request['end'])!=null){
                $dt = Carbon::parse($this->convert($request['end']));
//              var_dump($dt->year);
                //convert start and end time as persian to english date
                $request['end'] = $this->convert((string)$request->input('end'));
                $carbon=$request['end'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['end']);
                //$request['end']=$carbon->format('Y/m/d H:i:s');
            }
            if(trim($request['start'])!=null){
                $request['start'] = $this->convert((string)$request->input('start'));
                $carbon=$request['start'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['start']);
                $request['start']=$carbon->format('Y/m/d H:i:s');
            }



        }
        catch (Exception $e)
        {
//            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
//            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::NotValidDateFormat);
            return redirect()->back()->with('message', $message);
        }
        $this->validate($request, [
            'start' => 'required',
        ]);
        try {
            $user_id = $request['user_name'];
            $keywords = explode(',',$user_id);
            $this->attendanceRepository->initializeByRequest($request);
            $this->attendanceRepository->set_user_company_id($keywords[0],$request['company_id']);
            $this->attendanceRepository->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationSuccessCode);

            return redirect()->action(
                'AttendanceController@index', ['$company_id' => Input::get('company_id'), '$company_guid' => Input::get('company_guid')]
            )->with('message', $message);

        }
        catch (Exception $e) {
            $logEvent = new LogEventRepository((Auth::check() == true ? Auth::user()->user_id : 1), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);

            return redirect()->action(
                'AttendanceController@index', ['$company_id' => Input::get('company_id'), '$company_guid' => Input::get('company_guid')]
            )->with('message', $message);
        }
    }

    public function convert($string)
    {
        try
        {
            $western=['0','1','2','3','4','5','6','7','8','9'];
            $estern=['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];

            return str_replace($estern,$western,$string);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }


    }

    public function convertEtoV($string)
    {
        try
        {
            $western=['0','1','2','3','4','5','6','7','8','9'];
            $estern=['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];

            return str_replace($western,$estern,$string);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }


    }

    public function chartshow()
    {
        try
        {

            $user_id=Auth::user()->user_id;
            $user_guid=Auth::user()->user_guid;
            $this->attendanceRepository->initialize();


            list($provider, $AttendanceRepository) = $this->attendanceRepository->selectForProvider($this->attendanceRepository);
            $AttendanceRepositories = $this->attendanceRepository->selectForArray();



            $array = array();
            foreach($AttendanceRepositories as $attendance) {
                $start=new Carbon($attendance->start_date_time);
                $end=new Carbon($attendance->end_date_time);
                $array[] = array('hourStart' => $start->hour   , 'minuteStart' => $start->minute,'hourEnd' => $end->hour   , 'minuteEnd' => $end->minute);
            }

            return view('attendance/chart', ['AttendanceRepositories' => $array, 'provider'=>$provider]);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }
    }

    public function lastMonthChartShow()
    {
        try
        {
            $this->attendanceRepository->initialize();
            list($provider, $AttendanceRepository) = $this->attendanceRepository->selectForProvider($this->attendanceRepository);
            $AttendanceRepositories = $this->attendanceRepository->selectForArray();



            $array = array();
            foreach($AttendanceRepositories as $attendance) {
                $start=new Carbon($attendance->start_date_time);
                $end=new Carbon($attendance->end_date_time);
                $array[] = array('hourStart' => $start->hour , 'minuteStart' => $start->minute,'hourEnd' => $end->hour   , 'minuteEnd' => $end->minute);
            }

            return view('attendance/chart', ['AttendanceRepositories' => $array, 'provider'=>$provider]);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }


    }

    public function edit($attendance_id,$attendance_guid,$company_id,$company_guid)
    {
        try
        {
            $results = Attendance::where([
                ['attendance_id', '=', $attendance_id],
                ['attendance_guid', '=', $attendance_guid]])->get();
                //return json_encode($attendance_guid);
                return view('attendance.update',['Attendance' => $results,'company_id'=>$company_id,'company_guid'=>$company_guid]);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }
    }

    public function update(Request $request)
    {
        try
        {
            if(trim($request['end'])!=null){
                //convert start and end time as persian to english date
                $request['end'] = $this->convert((string)$request->input('end'));
                $carbon=$request['end'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['end']);
                $request['end']=$carbon->format('Y/m/d H:i:s');
            }


            $request['start'] = $this->convert( (string)$request->input('start'));
            $carbon=$request['start'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['start']);
            $request['start']=$carbon->format('Y/m/d H:i:s');
        }
        catch (Exception $e)
        {
//            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
//            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::NotValidDateFormat);
            return redirect()->back()->with('message', $message);
        }
        try
        {



//        //convert start and end time as persian to english date
//        $request['end_date_time'] = $this->convert((string)$request->input('end_date_time'));
//        $carbon = $request['end_date_time'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['end_date_time']);
//        $request['end_date_time'] = $carbon->format('Y/m/d H:i:s');
//
//        $request['start_date_time'] = $this->convert((string)$request->input('start_date_time'));
//        $carbon = $request['start_date_time'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['start_date_time']);
//        $request['start_date_time'] = $carbon->format('Y/m/d H:i:s');
            //set validation for thid form
            $rules = [
                'start' => 'required|date_format:Y/m/d H:i:s',
                'end' => 'date_format:Y/m/d H:i:s',
                'attendance_id' => 'required',
                'attendance_guid' => 'required',
            ];

            $v = Validator::make($request->all(), $rules);

            if ($v->fails()) {
                return redirect()->back()->withErrors($v->errors());
            } else {


                //save and update attendance
                $this->attendanceRepository->initialize();
                $this->attendanceRepository->initializeByRequest($request);
                $this->attendanceRepository->update($request);

                //$attendance=$this->attendanceRepository->getAttendance($request ->input('attendance_id'),$request ->input('attendance_guid'));

            }
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationSuccessCode);
//            log::info(Input::get('attendance_id')." editqqqqqqqqqqqqqqqqqqq");
            return redirect()->action(
                'AttendanceController@index', ['company_id' => Input::get('company_id'), 'company_guid' => Input::get('company_guid')]
            )->with(['message'=> $message]);
        }
        catch (Exception $e)
        {

            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }

    }

    public function delete($attendance_id, $attendance_guid)
    {
        try
        {
            $this->attendanceRepository->initialize();
            $this->attendanceRepository->set($attendance_id, $attendance_guid);
            $this->attendanceRepository->delete();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationSuccessCode);
            session(['message'=> $message]);

            return redirect()->back();
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }

    }

    public function ReportList($company_id,$company_guid,$user_id,$user_guid){
        try
        {
            $t=new UserRepository(new User());
            $userRepository=$t->GetListUsersOfCompany($company_id,$company_guid);
            log::info("thids ".json_encode(($userRepository)));
            $destinationPath = storage_path().'/app/company';
            $files1 = scandir($destinationPath);
            $nameOfFile="";
            $search =$company_guid;
            $search_length = strlen($search);
            foreach ($files1 as $key => $value) {
                if (substr($value, 0, $search_length) == $search) {
                    $nameOfFile=$value;
                    break;
                }
            }

            return view('company.listOfAttendanceReport', ['UserRepositories' => $userRepository,'logoPath'=>$nameOfFile,'company_id'=>$company_id,'company_guid'=>$company_guid]);
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }
    }

    public function reprtAttendanceHour($company_id = null , $company_guid = null){
//        $users=new UserRepository(new User());
//        $userRepository=$users->GetListUsersOfCompany($company_id,$company_guid);
        $start=Carbon::now()->subMonth();
        $end=Carbon::now();


        $destinationPath = storage_path().'/app/company';
        $files1 = scandir($destinationPath);
        $nameOfFile="";
        $search =$company_guid;
        $search_length = strlen($search);
        foreach ($files1 as $key => $value) {
            if (substr($value, 0, $search_length) == $search) {
                $nameOfFile=$value;
                break;
            }
        }




        $users = DB::select("
            SELECT uc.user_company_id,(NOW() - INTERVAL 1 MONTH) as `start_date_time`,NOW() as `end_date_time`,uc.user_company_guid,u.name,u.family,u.user_type_id,u.user_id,u.user_guid, sum((UNIX_TIMESTAMP(end_date_time) - UNIX_TIMESTAMP(start_date_time))/3600)  AS `hours` FROM `attendance` a
            left outer join user_company uc
              on uc.user_company_id=a.user_company_id
            left outer join user u
              on uc.user_id=u.user_id
            left outer join company c
              on c.company_id=uc.company_id
              where c.company_id=:company_id and c.company_guid=:company_guid and c.deleted_at is null and u.deleted_at is null and uc.deleted_at is null and a.deleted_at is null
              and a.`start_date_time`>(NOW() - INTERVAL 1 MONTH)
              GROUP by u.user_id
        ",array('company_id' => $company_id,'company_guid' => $company_guid));

//        return json_encode($users);
        return view('reports.index', ['UserRepositories' => $users,'logoPath'=>$nameOfFile,'company_id'=>$company_id,'company_guid'=>$company_guid,'start'=>$start,'end'=>$end]);


    }

    public function reprtAttendanceHourByValue(Request $request){
        try
        {
            $start=$request['start'];
            $end=$request['end'];
            if(trim($request['end'])!=null){
                $dt = Carbon::parse($this->convert($request['end']));
//              var_dump($dt->year);
                //convert start and end time as persian to english date
                $request['end'] = $this->convert((string)$request->input('end'));
                $carbon=$request['end'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['end']);
                $request['end']=$carbon->format('Y-m-d H:i:s');
            }
            if(trim($request['start'])!=null){
                $request['start'] = $this->convert((string)$request->input('start'));
                $carbon=$request['start'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['start']);
                $request['start']=$carbon->format('Y-m-d H:i:s');
            }
            $destinationPath = storage_path().'/app/company';
            $files1 = scandir($destinationPath);
            $nameOfFile="";
            $search =$request['company_guid'];
            $search_length = strlen($search);
            foreach ($files1 as $key => $value) {
                if (substr($value, 0, $search_length) == $search) {
                    $nameOfFile=$value;
                    break;
                }
            }




            $users = DB::select("
            SELECT uc.user_company_id,uc.user_company_guid,u.name,u.family,u.user_type_id,u.user_id,u.user_guid, sum((UNIX_TIMESTAMP(end_date_time) - UNIX_TIMESTAMP(start_date_time))/3600)  AS `hours` FROM `attendance` a
            left outer join user_company uc
              on uc.user_company_id=a.user_company_id
            left outer join user u
              on uc.user_id=u.user_id
            left outer join company c
              on c.company_id=uc.company_id
              where c.company_id=:company_id and c.company_guid=:company_guid and c.deleted_at is null and u.deleted_at is null and uc.deleted_at is null and a.deleted_at is null
              and a.`start_date_time`>:start and a.`start_date_time`<:end_time
              GROUP by u.user_id
        ",array('company_id' => $request['company_id'],'company_guid' => $request['company_guid'],'start'=>$request['start'],'end_time'=>$request['end']));

//            return json_encode($users);
            return view('reports.index', ['UserRepositories' => $users,'logoPath'=>$nameOfFile,'company_id'=>$request['company_id'],'company_guid'=>$request['company_guid'],'start'=>$request['start'],'end'=>$request['end']]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::NotValidDateFormat);
            return redirect()->back()->with('message', $message);
        }
    }

}
