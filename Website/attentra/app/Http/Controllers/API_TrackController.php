<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 3/12/2017
 * Time: 2:27 PM
 */

namespace App\Http\Controllers;

use App\Repositories\ModuleRepository;
use App\Repositories\TrackRepository;
use App\Repositories\UserRepository;
use App\Track;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use App\RequestResponseAPI;
use Redirect;
use JWTAuth;
use Carbon\Carbon;
use DateTimeZone;

class API_TrackController extends Controller
{
    protected $trackRepo;

    public function __construct(TrackRepository $track)
    {
        $this->trackRepo = $track;
    }

    /**
     * Store a new track cordinate.
     *
     * @param  Request  $request
     * @return Response
     */
    public function apiStore(Request $request)
    {
        //validation
        if(!$request->has('user_id') ||!$request->has('user_guid') || !$request->has('track_group') || !$request->has('latitude') || !$request->has('longitude')
            || !$request->has('battery_power')|| !$request->has('signal_power')
            || !$request->has('battery_status')|| !$request->has('charge_status')
            || !$request->has('charge_type'))
        {
            return json_encode(['error'=>RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);

        }

        //module validation here
        list($status,$purchasedCount, $storedCount) = ModuleRepository::api_CheckModuleValidation($request->input('user_id'),null,ModuleRepository::newTrackingModule);
        if($status == ModuleRepository::StatusEnd)
        {
            return json_encode(['error'=>RequestResponseAPI::ERROR_MODULE_EXPIRE_CODE, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);

        }
        /////////////////////////

        //get last stored points for this track group
        //        select track data with
        $last_track = Track::where('track_group', $request->input('track_group'))->orderBy('track_id', 'desc')->first();
        if($last_track != null)
        {
            if($last_track->latitude == $request->input('latitude') &&
                $last_track->longitude == $request->input('longitude'))
            {
                return json_encode(['error'=>0, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);
            }
        }

            //////////////////////////////////////////////

        ////////////////////get user from token////////////////
//        $token = JWTAuth::parseToken()->getToken()->get();
//        $user=JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////////////////////////////////
        $tmpUserRep = new UserRepository(new User());
        $user = $tmpUserRep->findByIdAndGuid($request->input('user_id'),$request->input('user_guid'));

        //////set created_at manualy wrt company time_zone////////
        $company = UserRepository::getCompany($user->user_id,null);
        $now = Carbon::now(new DateTimeZone($company[0]->time_zone));
        $now->subMinutes(10);
        $request['created_at'] =$now->toDateTimeString();
        $request['updated_at'] =$now->toDateTimeString();
        /// /////////////////////////////////////////////////////


        try{
            $this->trackRepo->initializeByRequest($request);
            $this->trackRepo->store();

            return json_encode(['error'=>0, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);

        }catch (Exception $e) {
            $logEvent = new LogEventRepository(($user != null ? $user->user_id : null), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();

            return json_encode(['error'=>RequestResponseAPI::ERROR_TRACK_STORE_CODE, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);
        }


    }

    public function apiIndex(Request $request)
    {

        //validation
        if (!$request->has('user_id')||
            !$request->has('skip')||
            !$request->has('user_guid')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_TRACK]);

        }

        /////////////////////check token
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////
        //check if user is owner... problem in user being middleceo
//        $users = UserRepository::API_GetManager($request->input('user_id'));
//        if(count($users) > 0)
//        {
//            if($user->user_id != $users[0]->user_id)
//            {
//                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_TRACK]);
//
//            }
//        }
//        else
//        {
//            return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_TRACK]);
//
//        }
        //////////////////////////////////
//        select track data with
        $paramsObj1 = array(
            array("se", "track", "track_group")
        );
        $paramsObj3 = array(
            array("whereRaw",
                "track.user_id = (SELECT user_id 
                                    FROM user  
                                    WHERE user_id ='".$request->input('user_id') ."'and
                                    user_guid='".$request->input('user_guid')."')"
            ),
            array("groupBy",
                "track.track_group"
            ),
//            array("groupBy",
//                "track.track_id"
//            ),
            array("orderBy",
                "track.track_id", "DESC"
            )
        );

        $paramsObj3[] =   array("skip",
            $request['skip']
        );
        $paramsObj3[] =   array("take",
            "20"
        );
        /////add deleted at condition to query/////////

        $paramsObj3[] =   array("whereRaw",
            "track.deleted_at is null"
        );

        /// ///////////////////////////////////////

        try
        {
            $this->trackRepo->initialize();

            $tracks = $this->trackRepo->getFullDetailTrack($paramsObj1, null, $paramsObj3);


            return json_encode(['token' => $token, 'error' => 0, 'tracks' => $tracks, 'tag' => RequestResponseAPI::TAG_INDEX_TRACK]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository(($user != null ? $user->user_id : null), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();

            return json_encode(['error'=>RequestResponseAPI::ERROR_TRACK_INDEX_CODE, 'tag'=>RequestResponseAPI::TAG_INDEX_TRACK]);
        }


    }

    public function apiList(Request $request)
    {

        //validation
        if (!$request->has('track_group') ||
            !$request->has('last_loaded_id')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_LIST_TRACK]);

        }

        /////////////////////check token
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////

//        select track data with
        $paramsObj1 = array(
            array("st", "track")
        );
        $paramsObj3 = array(
            array("whereRaw",
                "track.track_group ='" . $request->input('track_group') . "'"
            ),
            array("whereRaw",
                "track.track_id >'" . $request->input('last_loaded_id') . "'"

            )
        );
        /////add deleted at condition to query/////////

        $paramsObj3[] =   array("whereRaw",
            "track.deleted_at is null"
        );

        /// ///////////////////////////////////////
        try
        {
            $this->trackRepo->initialize();

            $tracks = $this->trackRepo->getFullDetailTrack($paramsObj1, null, $paramsObj3);


            return json_encode(['token' => $token, 'error' => 0, 'tracks' => $tracks, 'tag' => RequestResponseAPI::TAG_LIST_TRACK]);

        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
             return json_encode(['error'=>RequestResponseAPI::ERROR_TRACK_LIST_CODE, 'tag'=>RequestResponseAPI::TAG_LIST_TRACK]);
        }


    }

    /**
     * delete a group of tracks .
     *
     * @param  Request  $request
     * @return Response
     */
    public function apiDelete(Request $request)
    {

        //validation
        if (!$request->has('track_group') ||
            !$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_TRACK]);

        }

        /////////////////////check token
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////

        //it is being checked in middleware!!!
//        if($user->user_type_id != UserTypeRepository::CEO &&
//            $user->user_type_id != UserTypeRepository::MiddleCEO)
//        {
//            return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_TRACK]);
//
//        }


        try{

            Track::where('track_group', $request->input('track_group'))->delete();

            return json_encode(['token'=>$token,'error'=>0, 'tag'=>RequestResponseAPI::TAG_DELETE_TRACK]);

        }catch (Exception $e) {
            $logEvent = new LogEventRepository(($user != null ? $user->user_id : null), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();

            return json_encode(['error'=>RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag'=>RequestResponseAPI::TAG_DELETE_TRACK]);
        }


    }

    /**
     * generate a trackgroup .
     *
     * @param  Request  $request
     * @return Response
     */
    public function apiGenerate(Request $request)
    {
        //validation
        if (
            !$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_GENERATE_TRACK]);

        }

        /////////////////////check token
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////
        //module validation here
        list($status,$purchasedCount, $storedCount) = ModuleRepository::api_CheckModuleValidation($user->user_id,null,ModuleRepository::newTrackingModule);
        if($status == ModuleRepository::StatusEnd)
        {
            return json_encode(['error'=>RequestResponseAPI::ERROR_MODULE_EXPIRE_CODE, 'tag'=>RequestResponseAPI::TAG_GENERATE_TRACK]);

        }
        /////////////////////////

        try{

            ///    //////set created_at manualy wrt company time_zone////////
            $company = UserRepository::getCompany($user->user_id,null);
            $now = Carbon::now(new DateTimeZone($company[0]->time_zone));
            $now->subMinutes(10);
            $trackGroup = $user->user_id.Utility::cleanDateTime($now->toDateTimeString());

            /// /////////////////////////////////////////////////////
        return json_encode(['track_group'=>$trackGroup,'token'=>$token,'error'=>0, 'tag'=>RequestResponseAPI::TAG_GENERATE_TRACK]);

    }catch (Exception $e) {
            $logEvent = new LogEventRepository(($user != null ? $user->user_id : null), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            $logEvent->store();

            return json_encode(['error'=>RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag'=>RequestResponseAPI::TAG_GENERATE_TRACK]);
            }
    }

//    /**
//     * Store a new track cordinate.
//     *
//     * @param  Request  $request
//     * @return Response
//     */
//    public function apiBulkStore(Request $request)
//    {
//        //validation
//        if(!$request->has('tracks'))
//        {
//            return json_encode(['error'=>RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);
//
//        }
//
//        //module validation here
//        list($status,$purchasedCount, $storedCount) = ModuleRepository::api_CheckModuleValidation($request->input('user_id'),null,ModuleRepository::newTrackingModule);
//        if($status == ModuleRepository::StatusEnd)
//        {
//            return json_encode(['error'=>RequestResponseAPI::ERROR_MODULE_EXPIRE_CODE, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);
//
//        }
//        /////////////////////////
//
//        //get last stored points for this track group
//        //        select track data with
//        $last_track = Track::where('track_group', $request->input('track_group'))->orderBy('track_id', 'desc')->first();
//        if($last_track != null)
//        {
//            if($last_track->latitude == $request->input('latitude') &&
//                $last_track->longitude == $request->input('longitude'))
//            {
//                return json_encode(['error'=>0, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);
//            }
//        }
//
//        //////////////////////////////////////////////
//
//        ////////////////////get user from token////////////////
////        $token = JWTAuth::parseToken()->getToken()->get();
////        $user=JWTAuth::parseToken()->authenticate($token);
//        //////////////////////////////////////////////////////////
//        $tmpUserRep = new UserRepository(new User());
//        $user = $tmpUserRep->findByIdAndGuid($request->input('user_id'),$request->input('user_guid'));
//
//        //////set created_at manualy wrt company time_zone////////
//        $company = UserRepository::getCompany($user->user_id,null);
//        $now = Carbon::now(new DateTimeZone($company[0]->time_zone));
//        $now->subMinutes(10);
//        $request['created_at'] =$now->toDateTimeString();
//        $request['updated_at'] =$now->toDateTimeString();
//        /// /////////////////////////////////////////////////////
//
//
//        try{
//
//            $tracks=[];
//            $tracks=json_decode($request['tracks']);
//            for($index=0;$index<count($tracks);$index++)
//            {
//                if(!isset($tracks[$index]->user_id) ||!isset($tracks[$index]->user_guid) || !isset($tracks[$index]->track_group)|| !isset($tracks[$index]->latitude) || !isset($tracks[$index]->longitude)
//                    || !isset($tracks[$index]->battery_power)|| !isset($tracks[$index]->signal_power)
//                    || !isset($tracks[$index]->battery_status)|| !isset($tracks[$index]->charge_status)
//                    || !isset($tracks[$index]->charge_type))
//                {
//                    return json_encode(['error'=>RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);
//
//                }
//            }
//
//            $this->trackRepo->initializeByRequest($request);
//            $this->trackRepo->store();
//
//            return json_encode(['error'=>0, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);
//
//        }catch (Exception $e) {
//            $logEvent = new LogEventRepository(($user != null ? $user->user_id : null), Route::getCurrentRoute()->getActionName(), "Main Message: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
//            $logEvent->store();
//
//            return json_encode(['error'=>RequestResponseAPI::ERROR_TRACK_STORE_CODE, 'tag'=>RequestResponseAPI::TAG_STORE_TRACK]);
//        }
//
//
//    }
}