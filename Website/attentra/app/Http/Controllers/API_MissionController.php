<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 3/12/2017
 * Time: 2:27 PM
 */
namespace App\Http\Controllers;

use App\Repositories\MissionRepository;
use App\Repositories\CompanyRepository;
use App\RequestResponseAPI;
use Morilog\Jalali\jDateTime;
use App;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;
use Validator;
use DB;
use JWTAuth;
use App\Company;
use App\Repositories\LogEventRepository;
use Route;

class API_MissionController extends Controller
{
    protected $missionRepository;

    /**
     * MissionController constructor.
     * @param MissionRepository $mission_repo
     */
    public function __construct(MissionRepository $mission_repo)
    {
        $this->missionRepository = $mission_repo;
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
        try {

            //validation
            if (!$request->has('company_id') ||
                !$request->has('skip')||
                !$request->has('company_guid')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_MISSION]);

            }
            $company_id = $request->input('company_id');
            $company_guid = $request->input('company_guid');


            //check if user is owner...problem in user being middleceo
//            $users = CompanyRepository::getCeoOfThisCompany($company_id,$company_guid);
//            if(count($users) > 0)
//            {
//                if($user->user_id != $users[0]->user_id)
//                {
//                    return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_MISSION]);
//
//                }
//            }
//            else
//            {
//                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_MISSION]);
//
//            }

            //////////////////////////////////

            $userId = null;
            $userGuid = null;
            if ($request->has('user_id') &&
                $request->has('user_guid')
            ) {
                $userId = $request->input('user_id');
                $userGuid = $request->input('user_guid');
            }

            //this variable count seconds of this module exist to access
            //this variable will get the seconds of this company access to attendane module
            //if this company end time to access to this module this variable is negative amount
            //if this company access deny t0 this module this variable is null
            $second = 0;

            if ($user->user_type_id > App\Repositories\UserTypeRepository::Admin) {
                //this variable count seconds of this module exist to access
                //this variable will get the seconds of this company access to attendane module
                //if this company end time to access to this module this variable is negative amount
                //if this company access deny t0 this module this variable is null

                list($second, $nothing) = app('App\Repositories\CompanyUserModuleRepository')->apiExist(2, $company_id, null);

            }

            //get target company
            $companyTemp = new CompanyRepository(new Company());
            $companyTemp->initializeByRequest($request);
            $companies = $companyTemp->select();
            $company = $companies[0];

            //         select table
            if ($userId == null && $userGuid == null) {
                $paramsObj1 = array(
                    array("st", "mission"),

                );
            } else {
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
                if ($user->user_type_id == App\Repositories\UserTypeRepository::MiddleCEO || $user->user_type_id == App\Repositories\UserTypeRepository::CEO) {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user_company.company_id='" . $company_id . "'"
                        )
                    );
                } //for middle manager  or employer
                elseif ($user->user_type_id == App\Repositories\UserTypeRepository::Employee) {

                    $userCompany = DB::table('user_company')
                        ->where('user_id', $user->user_id)
                        ->where('deleted_at',null)
                        ->first();
                    $userCompanyId = $userCompany->user_company_id;

                    $paramsObj3 = array(
                        array("whereRaw",
                            "company.company_id='" . $company_id . "'"
                        ),
                        array("whereRaw",
                            "user_mission.user_company_id='" . $userCompanyId . "'"
                        )

                    );
                }
            } else if ($second < 0 || $second === null) {
                date_default_timezone_set($company->time_zone);
//                $tomarow = Carbon::tomorrow();
                $oneMounthAgo = Carbon::now()->addDays(-31);
                //for middle manager
                //for manager
                if ($user->user_type_id == App\Repositories\UserTypeRepository::MiddleCEO || $user->user_type_id == App\Repositories\UserTypeRepository::CEO) {
                    if ($userId == null && $userGuid == null) {
                        $paramsObj3 = array(
                            array("whereRaw",
                                "user_company.company_id='" . $company_id . "'"
                            ),
                            array("whereRaw",
                                "mission.start_date_time >='" . $oneMounthAgo . "'"
                            )
                        );
                    } else {
                        $paramsObj3 = array(
                            array("whereRaw",
                                "user_company.company_id='" . $company_id . "'"
                            ),
                            array("whereRaw",
                                "mission.start_date_time >='" . $oneMounthAgo . "'"
                            )
                        ,
                            array("whereRaw",
                                "user_company.user_id='" . $userId . "'"
                            )
                        );
                    }

                } //foer middle manager or for employer
                elseif ($user->user_type_id == App\Repositories\UserTypeRepository::Employee) {

                    $paramsObj3 = array(
                        array("whereRaw",
                            "company.company_id='" . $company_id . "'"
                        ),
                        array("whereRaw",
                            "mission.start_date_time >='" . $oneMounthAgo . "'"
                        )
                    ,
                        array("whereRaw",
                            "user_company.user_id='" . $user->user_id . "'"
                        )
                    );
                }

            } else if ($user->user_type_id == App\Repositories\UserTypeRepository::Admin) {
                //conditions
                //for super admin
                $paramsObj3 = null;
            }

            $paramsObj3[] =   array("skip",
                $request['skip']
            );
            $paramsObj3[] =   array("take",
                "20"
            );
            /////add deleted at condition to query/////////

            $paramsObj3[] =   array("whereRaw",
                "mission.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user_mission.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user_company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "company.deleted_at is null"
            );

            /// ///////////////////////////////////////

            $this->missionRepository->initialize();
            $Missions = $this->missionRepository->getFullDetailMission($paramsObj1, $paramsObj2, $paramsObj3, true);

            return json_encode(['token' => $token, 'missions' => $Missions, 'error' => 0, 'tag' => RequestResponseAPI::TAG_INDEX_MISSION]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_MISSION]);
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

        //validation
        if (!$request->has('company_id') ||
            !$request->has('company_guid')||
            !$request->has('tag')||
            !$request->has('title')||
            !$request->has('start_date_time')||
            !$request->has('end_date_time')||
            !$request->has('missionperson')||
            !$request->has('description')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_MISSION]);

        }

        //check if user is owner... problem in user being middleceo
//        $users = CompanyRepository::getCeoOfThisCompany($request->input('company_id'),$request->input('company_guid'));
//        if(count($users) > 0)
//        {
//            if($user->user_id != $users[0]->user_id)
//            {
//                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_STORE_MISSION]);
//
//            }
//        }
//        else
//        {
//            return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_STORE_MISSION]);
//
//        }

        //////////////////////////////////

        try {

            //convert start and end time as persian to english date
//            $carbon  = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['end_date_time']);
//            $request['end_date_time'] = $carbon->format('Y/m/d H:i:s');
//
//            $carbon  = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['start_date_time']);
//            $request['start_date_time'] = $carbon->format('Y/m/d H:i:s');

            DB::beginTransaction();

            //save mission
            $this->missionRepository->initializeByRequest($request);
            $this->missionRepository->store();

            //select list of user for this mission
            $company_usrers = new CompanyRepository(new App\Company());
            $usersOfCompany = $company_usrers->getUsersOfThisCompany($request->input('company_id'), $request->input('company_guid'));
            $userIdArray = [];


//            $persons = $request->input('missionperson');
            $persons_ids = explode(',', $request->input('missionperson'));

            foreach ($persons_ids as $person_id) {
                foreach ($usersOfCompany as $user) {
                    if ($person_id == $user->user_id) {
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

                    $userMission->save();
            }
            DB::commit();
            return json_encode(['token' => $token,'error' => 0, 'tag' => RequestResponseAPI::TAG_STORE_MISSION]);

        } catch
        (\Exception $ex) {
            DB::rollback();

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_STORE_MISSION]);
        }
    }
    public function apiUpdate(Request $request)
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
        if (!$request->has('company_id') ||
            !$request->has('company_guid')||
            !$request->has('tag')||
            !$request->has('title')||
            !$request->has('start_date_time')||
            !$request->has('end_date_time')||
            !$request->has('missionperson')||
            !$request->has('description')||
            !$request->has('mission_id')||
            !$request->has('mission_guid')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_MISSION]);

        }
        //check if user is owner...problem in user being middleceo
//        $users = CompanyRepository::getCeoOfThisCompany($request->input('company_id'),$request->input('company_guid'));
//        if(count($users) > 0)
//        {
//            if($user->user_id != $users[0]->user_id)
//            {
//                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_MISSION]);
//
//            }
//        }
//        else
//        {
//            return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_MISSION]);
//
//        }

        //////////////////////////////////
        try
        {
            //convert start and end time as persian to english date
            $carbon  = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['end_date_time']);
            $request['end_date_time'] = $carbon->format('Y/m/d H:i:s');

            $carbon  = jDatetime::createCarbonFromFormat('Y/m/d H:i:s', $request['start_date_time']);
            $request['start_date_time'] = $carbon->format('Y/m/d H:i:s');


            //save and update mission
            $this->missionRepository->initialize();
            $this->missionRepository->initializeByRequest($request);
            $this->missionRepository->update($request);


            //select list of user for this mission
            $company_usrers = new CompanyRepository(new App\Company());
            $usersOfCompany = $company_usrers->getUsersOfThisCompany($request['company_id'], $request['company_guid']);
            $userIdArray = [];


            $persons_ids = explode(',', $request->input('missionperson'));

            foreach ($persons_ids as $person_id) {
                foreach ($usersOfCompany as $user) {
                    if ($person_id == $user->user_id) {
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

            DB::beginTransaction();

            //select list of old usermission for delete
            DB::table('user_mission')->where('mission_id', '=', $request->input('mission_id'))
                ->where('deleted_at',null)
                ->delete();
            //save new user_mission that person is in this mission to database
            for ($index = 0; $index < count($userIdArray); $index++) {
                $userMission = new App\UserMision();
                $userMission->user_mission_guid = uniqid('', true);
                $userMission->mission_id = $MissionRow[0]->mission_id;
                $userMission->user_company_id = $userIdArray[$index];
                $userMission->save();
            }
            DB::commit();
            return json_encode(['token' => $token,'error' => 0, 'tag' => RequestResponseAPI::TAG_EDIT_MISSION]);

        }
        catch (Exception $ex)
        {
            DB::rollback();

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_MISSION]);
        }

    }

    /**
     * Remove the specified resource from storage.
     * @param  int $id
     * @return Response
     */
    public function apiDestroy(Request $request)
    {

        ///////token auth//////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::authenticate($token);
        ///////////////////////////////////////////

        try {

            //////////////////validation//////////////
            if (!$request->has('mission_id') ||
                !$request->has('mission_guid')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_MISSION]);

            }
            //////////////////////////////////////////


            $mission_id = $request->input('mission_id');
            $mission_guid = $request->input('mission_guid');

            ///////////////////////////////////////////
            ///  //check if user is owner...
//            $users = MissionRepository::getCeoOfThisMission($request->input('mission_id'));
//            if(count($users) > 0)
//            {
//                if($user->user_id != $users[0]->user_id)
//                {
//                    return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_MISSION]);
//
//                }
//            }
//            else
//            {
//                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_MISSION]);
//
//            }

            //////////////////////////////////

                $this->missionRepository->initialize(null);
                $this->missionRepository->set($mission_id, $mission_guid);
                $this->missionRepository->delete();

            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_DELETE_MISSION]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_DELETE_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_MISSION]);
        }
    }

    public function apiListMembers(Request $request)
    {
        //////////////token auth////////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::authenticate($token);
        //////////////////////////////////////////////

        /////////////validation/////////////////
        if (!$request->has('mission_id') ||
            !$request->has('mission_guid')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_MISSION]);

        }
        ///////////////////////////////////////////
        ///  //check if user is owner... problem in user being middleceo
//        $users = MissionRepository::getCeoOfThisMission($request->input('mission_id'));
//        if(count($users) > 0)
//        {
//            if($user->user_id != $users[0]->user_id)
//            {
//                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_MISSION]);
//
//            }
//        }
//        else
//        {
//            return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_MISSION]);
//
//        }

        //////////////////////////////////

        $mission_id=$request->input('mission_id');
        $mission_guid = $request->input('mission_guid');

        try {

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
            array("whereRaw",
                "user.deleted_at is null"
            ),
            array("whereRaw",
                "user_mission.deleted_at is null"
            ),

            array("whereRaw",
                "user_company.deleted_at is null"
            ),
            array("whereRaw",
                "mission.deleted_at is null"
            ),



        );

            /////add deleted at condition to query/////////

            $paramsObj3[] =   array("whereRaw",
                "mission.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user_mission.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user_company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user.deleted_at is null"
            );

            /// ///////////////////////////////////////


            $this->missionRepository->initialize();
            $usersOfThisMission = $this->missionRepository->getFullDetailMission($paramsObj1, $paramsObj2, $paramsObj3);

           return json_encode(['token' => $token, 'error' => 0, 'users' => $usersOfThisMission, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_MISSION]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_DELETE_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_MISSION]);
        }

    }

}
