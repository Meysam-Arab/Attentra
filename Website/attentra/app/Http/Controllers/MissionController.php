<?php

namespace App\Http\Controllers;

use App\Repositories\MissionRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ModuleRepository;

use App;
use App\OperationMessage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Morilog\Jalali\jDateTime;
use App\Repositories\UserTypeRepository;

use Illuminate\Support\Facades\Input;
use phpDocumentor\Reflection\Types\Null_;
use Validator;
use Morilog\Jalali\Facades\jDate;
use DB;
use Exception;
use App\Repositories\LogEventRepository;
use Route;
use Log;


class MissionController extends Controller
{
    protected $missionRepository;

    /**
     * MissionController constructor.
     * @param MissionRepository $mis
     */
    public function __construct(MissionRepository $mis)
    {
        $this->missionRepository = $mis;
    }

    public function index($company_id = null, $company_guid = null,$userId=null,$userGuid=null)
    {
        try
        {
            $count = 0;

            //this variable count seconds of this module exist to access
            //this variable will get the seconds of this company access to mission module
            //if this company end time to access to this module this variable is negative amount
            //if this company access deny t0 this module this variable is null
            $second = 0;
            //if user was employ or middle manager just has a one company id
            if (Auth::user()->user_type_id > UserTypeRepository::CEO) {
                $company_id = session('companiesId0');
                $company_guid = session('companiesGuid0');
            }
            // if user was employ , middle manager or manager must set the session
            if (Auth::user()->user_type_id == UserTypeRepository::CEO) {
                //get index of session for company data
                //this session before set when user login in company controller in profileset method
                for ($index = 0; $index < session('CompanyCount'); $index++) {
                    if (session('companiesId' . $index) == $company_id) {
                        $count = $index;
                        break;
                    }
                }


            }
            if (Auth::user()->user_type_id > UserTypeRepository::Admin) {
                //this variable count seconds of this module exist to access
                //this variable will get the seconds of this company access to attendane module
                //if this company end time to access to this module this variable is negative amount
                //if this company access deny t0 this module this variable is null

                list($second,$nothing) = app('App\Repositories\CompanyUserModuleRepository')->exist(ModuleRepository::missionModule, $company_id, null);
            }

            //         select table
            if($userId=='null' && $userGuid=='null'){
                $paramsObj1 = array(
                    array("st", "mission"),
                    array("se", "company", "name"),
                    array("se", "company", "company_id"),
                    array("se", "company", "company_guid"),
//            array("se", "user_company", "user_id"),
                );
            }else{
                $paramsObj1 = array(
                    array("st", "mission"),
                    array("se", "company", "name"),
                    array("se", "company", "company_id"),
                    array("se", "company", "company_guid"),
                    array("se", "user_company", "user_id"),
                );
            }


            //join
            $paramsObj2 = array(
                array("join",
                    "user_mission",
                    array("user_mission.mission_id", "=", "mission.mission_id")
                ),
                array("join",
                    "user_company",
                    array("user_company.user_company_id", "=", "user_mission.user_company_id")
                ),
                array("join",
                    "company",
                    array("company.company_id", "=", "user_company.company_id")
                )
            );

            if ($second > 0) {
                //for middle manager
                //for manager
                if (Auth::user()->user_type_id == UserTypeRepository::MiddleCEO|| Auth::user()->user_type_id == UserTypeRepository::CEO) {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id . "'"
                        ),
                        array("whereRaw",
                            "company.company_guid like '" . $company_guid ."'"
                        )
                    );
                } //foer middle manager  or employer
                elseif ( Auth::user()->user_type_id == UserTypeRepository::Employee) {


                    $userCompany = DB::table('user_company')->where('user_id', Auth::user()->user_id)->where('deleted_at', null)->first();
                    $userCompanyId = $userCompany->user_company_id;

                    $paramsObj3 = array(
                        array("whereRaw",
                            "company.company_id='" . $company_id . "'"
                        ),
                        array("whereRaw",
                            "company.company_guid like '" . $company_guid ."'"
                        ),
                        array("whereRaw",
                            "user_mission.user_company_id='" . $userCompanyId . "'"
                        )

                    );
                }
            }
            else if ($second < 0 || $second === null) {

                $tomarow = Carbon::tomorrow();
                $oneMounthAgo = Carbon::now()->addDays(-31);

                if (Auth::user()->user_type_id == UserTypeRepository::MiddleCEO ||Auth::user()->user_type_id == UserTypeRepository::CEO) {
                    if($userId=='null' && $userGuid=='null'){
                        $paramsObj3 = array(
                            array("whereRaw",
                                "user_company.company_id='" . $company_id . "'"
                            ),
                            array("whereRaw",
                                "company.company_guid like '" . $company_guid ."'"
                            ),
                            array("whereRaw",
                                "mission.start_date_time>'" . $oneMounthAgo. "'"
                            )
                        );
                    }else{
                        $paramsObj3 = array(
                            array("whereRaw",
                                "user_company.company_id='" . $company_id . "'"
                            ),
                            array("whereRaw",
                                "company.company_guid like '" . $company_guid ."'"
                            ),
                            array("whereRaw",
                                "mission.start_date_time>'" . $oneMounthAgo. "'"
                            ),
                            array("whereRaw",
                                "user_company.user_id='" . $userId . "'"
                            )
                        );
                    }



                } //foer middle manager or for employer
                elseif ( Auth::user()->user_type_id == UserTypeRepository::Employee) {


                    $paramsObj3 = array(
                        array("whereRaw",
                            "company.company_id='" . $company_id . "'"
                        ),
                        array("whereRaw",
                            "company.company_guid like '" . $company_guid ."'"
                        ),
                        array("whereRaw",
                            "mission.start_date_time>'" . $oneMounthAgo. "'"
                        ),
                        array("whereRaw",
                            "user_company.user_id='" . Auth::user()->user_id . "'"
                        )
                    );
                }

            }
            else if (Auth::user()->user_type_id == UserTypeRepository::Admin) {
                //conditions
                //for super admin
                $paramsObj3 = null;
            }
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "mission.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user_company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user_mission.deleted_at is null"
            );


            /// ///////////////////////////////////////

            $this->missionRepository->initialize();
            $MissionRepository = $this->missionRepository->getFullDetailMission($paramsObj1, $paramsObj2, $paramsObj3, true);

            $notId = 0;
            if (Auth::user()->user_type_id == UserTypeRepository::CEO || Auth::user()->user_type_id == UserTypeRepository::MiddleCEO || Auth::user()->user_type_id == UserTypeRepository::Employee) {
                return view('company/missionList', ['MissionRepositories' => $MissionRepository])
                    ->with(['second' => $second, 'company_id' => $company_id, 'company_guid' => $company_guid]);
            } else {
                return view('company/missionList', ['MissionRepositories' => $MissionRepository])
                    ->with(['second' => $second, 'company_id' => $notId, 'company_guid' => $notId]);
            }

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

    public function userListForThisMission($mission_id, $mission_guid)
    {
//        $query->where('user_guid', 'like', $guid);
        $paramsObj1 = array(
            array("st", "user")
        );

        //join
        $paramsObj2 = array(
            array("join",
                "user_mission",
                array("user_mission.mission_id", "=", "mission.mission_id")
            ),
            array("join",
                "user_company",
                array("user_company.user_company_id", "=", "user_mission.user_company_id")
            ),
            array("join",
                "user",
                array("user.user_id", "=", "user_company.user_id")
            )
        );
        //conditions
        $paramsObj3 = array(
            array("whereRaw",
                "mission.mission_id='" . $mission_id . "'"
            ),
            array("whereRaw",
                "mission.mission_guid like'" . $mission_guid . "'"
            ),
//         ,
//             array("whereRaw",
//                 "mission.mission_guid='".$mission_guid."'"
//             ),

        );
        /////add deleted at condition to query - meysam/////////

        $paramsObj3[] =   array("whereRaw",
            "mission.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "user_company.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "user_mission.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "user.deleted_at is null"
        );

        /// ///////////////////////////////////////
        try
        {

            $this->missionRepository->initialize();
            $usersOfThisMission = $this->missionRepository->getFullDetailMission($paramsObj1, $paramsObj2, $paramsObj3);

            return view('mission/usersOfThisMission', ['usersOfThisMission' => $usersOfThisMission]);

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

    public function addMission($company_id, $company_guid)
    {

        try
        {
            //get list of  users this company
            $company_usrers = new CompanyRepository(new App\Company());
            $usersOfCompany = $company_usrers->getUsersOfThisCompany($company_id, $company_guid);
            //pass data to view
            // return json_encode($usersOfCompany);
            return view('mission/newMission', ['usersOfCompany' => $usersOfCompany])->with(['company_id' => $company_id, 'company_guid' => $company_guid]);

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

    public function store(Request $request)
    {

        try
        {

            //convert start and end time as persian to english date
            $request['end_date_time'] = $this->convert((string)$request->input('end_date_time'));
            $carbon = $request['end_date_time'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['end_date_time']);
            $request['end_date_time'] = $carbon->format('Y/m/d H:i:s');

            $request['start_date_time'] = $this->convert((string)$request->input('start_date_time'));
            $carbon = $request['start_date_time'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['start_date_time']);
            $request['start_date_time'] = $carbon->format('Y/m/d H:i:s');

        }
        catch (Exception $e)
        {
//            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
//            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::NotValidDateFormat);
            return redirect()->back()->with('message', $message);
        }


        //set validation for thid form
        $rules = [
            'title' => 'required',
            'start_date_time' => 'required|date_format:Y/m/d H:i:s',
            'end_date_time' => 'required|date_format:Y/m/d H:i:s',
            'missionperson' => 'required'
        ];
        $v = Validator::make($request->all(), $rules);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        } else {
            //save mission
            $this->missionRepository->initializeByRequest($request);
            try {
                $this->missionRepository->store();

            } catch (Exception $e) {
                $logEvent = new LogEventRepository((Auth::check() == true ? Auth::user()->user_id : -1), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
                $logEvent->store();

                $message = new OperationMessage();
                $message->initializeByCode(OperationMessage::OperationErrorCode);

                return redirect('/missionList/'.Input::get('company_id').'/'.Input::get('company_guid').'/null/null')->with('message', $message);
            }


            //select list of user for this mission
            $company_usrers = new CompanyRepository(new App\Company());
             $usersOfCompany = $company_usrers->getUsersOfThisCompany(Input::get('company_id'), Input::get('company_guid'));
            $userIdArray = [];


            $myCheckboxes = $request->input('missionperson');
//            print_r($myCheckboxes);
             foreach ($myCheckboxes as $key => $data) {
                foreach ($usersOfCompany as $user) {
                    if ($data == $user->user_id) {
                        array_push($userIdArray, $user->user_company_id);
                        break;
                    }
                }
            }
            //get Mission id from db
            $MissionRow = DB::table('mission')
                ->where('mission_guid', $this->missionRepository->getMissionGuid())
                ->where('deleted_at',null)
                ->get();
            //save user_mission that person is in this mission to database
            for ($index = 0; $index < count($userIdArray); $index++) {
                $userMission = new App\UserMision();
                $userMission->user_mission_guid = uniqid('', true);
                $userMission->mission_id = $MissionRow[0]->mission_id;
                $userMission->user_company_id = $userIdArray[$index];

                try {
                    $userMission->save();

                } catch (Exception $e) {
                    $logEvent = new LogEventRepository((Auth::check() == true ? Auth::user()->user_id : -1), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
                    $logEvent->store();

                    $message = new OperationMessage();
                    $message->initializeByCode(OperationMessage::OperationErrorCode);

                    return redirect('/missionList/'.Input::get('company_id').'/'.Input::get('company_guid').'/null/null')->with('message', $message);
                }
            }
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationSuccessCode);
            return redirect('/missionList/'.Input::get('company_id').'/'.Input::get('company_guid').'/null/null')->with('message', $message);

        }


    }

    public function convert($string)
    {
        try
        {
            $western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $estern = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            return str_replace($estern, $western, $string);

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

    public function edit($mission_id, $mission_guid)
    {
        //get COMPANY ID
        $paramsObj1 = array(
            array("st", "user_company"),
            array("st", "mission")
        );

        //join
        $paramsObj2 = array(
            array("join",
                "user_mission",
                array("user_mission.mission_id", "=", "mission.mission_id")
            ),
            array("join",
                "user_company",
                array("user_company.user_company_id", "=", "user_mission.user_company_id")
            )
        );
        //conditions
        $paramsObj3 = array(
            array("whereRaw",
                "mission.mission_id='" . $mission_id . "'"
            ),
            array("whereRaw",
                "mission.mission_guid like '" . $mission_guid ."'"
            ),

        );
        /////add deleted at condition to query - meysam/////////

        $paramsObj3[] =   array("whereRaw",
            "mission.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "user_company.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "user_mission.deleted_at is null"
        );


        /// ///////////////////////////////////////
        try
        {
            $this->missionRepository->initialize();
            $usersOfThisMission = $this->missionRepository->getFullDetailMission($paramsObj1, $paramsObj2, $paramsObj3, true);
            $company_id = $usersOfThisMission[0]->company_id;
            $CompanyRow = DB::table('company')
                ->where('company_id', $company_id)
                ->where('deleted_at',null)
                ->get();
            $company_guid = $CompanyRow[0]->company_guid;
            //get list of  users this company
            $company_usrers = new CompanyRepository(new App\Company());
            $usersOfCompany = $company_usrers->getUsersOfThisCompany($company_id, $company_guid);
            //pass data to view
            return view('mission/update', ['usersOfCompany' => $usersOfCompany, 'usersOfThisMission' => $usersOfThisMission])->with(['company_id' => $company_id, 'company_guid' => $company_guid]);

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
            //convert start and end time as persian to english date
            $request['end_date_time'] = $this->convert((string)$request->input('end_date_time'));
            $carbon = $request['end_date_time'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['end_date_time']);
            $request['end_date_time'] = $carbon->format('Y/m/d H:i:s');

            $request['start_date_time'] = $this->convert((string)$request->input('start_date_time'));
            $carbon = $request['start_date_time'] = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['start_date_time']);
            $request['start_date_time'] = $carbon->format('Y/m/d H:i:s');
        }
        catch (Exception $e)
        {
//            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
//            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::NotValidDateFormat);
            return redirect()->back()->with('message', $message);
        }

        //set validation for thid form
        $rules = [
            'title' => 'required',
            'start_date_time' => 'required|date_format:Y/m/d H:i:s',
            'end_date_time' => 'required|date_format:Y/m/d H:i:s',
            'missionperson' => 'required'
        ];

        $v = Validator::make($request->all(), $rules);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        } else {


            //save and update mission
            $this->missionRepository->initialize();
            $this->missionRepository->initializeByRequest($request);
            $this->missionRepository->update($request);


            //select list of user for this mission
            $company_usrers = new CompanyRepository(new App\Company());
             $usersOfCompany = $company_usrers->getUsersOfThisCompany($request['company_id'], $request['company_guid']);
            $userIdArray = [];


            $myCheckboxes = $request->input('missionperson');
            foreach ($myCheckboxes as $key => $data) {
                foreach ($usersOfCompany as $user) {
                    if ($data == $user->user_id) {
                        array_push($userIdArray, $user->user_company_id);
                        break;
                    }
                }
            }

            //get Mission id from db
            $MissionRow = DB::table('mission')
                ->where('mission_guid', $this->missionRepository->getMissionGuid())
                ->where('deleted_at',null)
                ->get();

            //select list of old usermission for delete
            DB::table('user_mission')->where('mission_id', '=', $request->input('mission_id'))->where('deleted_at',null)->delete();
            //save new user_mission that person is in this mission to database
            for ($index = 0; $index < count($userIdArray); $index++) {
                $userMission = new App\UserMision();
                $userMission->user_mission_guid = uniqid('', true);
                $userMission->mission_id = $MissionRow[0]->mission_id;
                $userMission->user_company_id = $userIdArray[$index];
                $userMission->save();
            }
        }
        $message = new OperationMessage();
        $message->initializeByCode(OperationMessage::OperationSuccessCode);
        return redirect('/missionList/'.Input::get('company_id').'/'.Input::get('company_guid').'/null/null')->with('message', $message);
    }

    public function delete($mission_id, $mission_guid)
    {

        try
        {
            $this->missionRepository->initialize();
            $this->missionRepository->set($mission_id, $mission_guid);

            $result=$this->missionRepository->delete();
            if(!$result)
                return view('errors/resourceNotExist');
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






}
