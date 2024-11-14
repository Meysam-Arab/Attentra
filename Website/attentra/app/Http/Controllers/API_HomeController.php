<?php

namespace App\Http\Controllers;

use App\Repositories\LogEventRepository;
use App\RequestResponseAPI;
use App\User;
use App;
use Log;
use Validator;
use DB;
use JWTAuth;
use Route;
use Illuminate\Http\Request;
use App\Repositories\AttendanceRepository;
use Carbon\Carbon;
use DateTimeZone;
use File;

class API_HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
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

        /////validation
        if (!$request->has('tag')||
            !$request->has('user_id')||
            !$request->has('user_guid')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_HOME]);

        }
        try {

            //get users QR code

            $qr_code = null;
            //select track data with
            $user_companies = DB::select( DB::raw('SELECT * from user_company where deleted_at is null and user_company.company_id IN(select MIN(user_company.company_id) from user_company where user_company.user_id = :muser_id and user_company.deleted_at IS null) and user_company.user_id = :muser_id2'), array(
                'muser_id' => $request->input('user_id'),
                'muser_id2' => $request->input('user_id'),
            ));

            if(count($user_companies) == 0)
                return json_encode(['error' => RequestResponseAPI::ERROR_COMPANY_NOT_EXIST, 'tag' => RequestResponseAPI::TAG_INDEX_HOME]);

            $user_company_id = $user_companies[0]->user_company_id;
            $qr_code = openssl_encrypt(strval($user_company_id),
                 "AES-128-ECB",
                AttendanceRepository::ENCRYPTION_PASSWORD);

            $company_id =  $user_companies[0]->company_id;
            $company = DB::table('company')
                ->where('company_id', $company_id)
                ->where('deleted_at',null)
                ->first();

            /////////////////////////
            /// check if user currently is in company
            $tommarow = Carbon::today(new DateTimeZone($company->time_zone))->addDays(1);
//            Log::info('tommarow:'.json_encode($tommarow));
            $today =  Carbon::today(new DateTimeZone($company->time_zone));
//            Log::info('$today:'.json_encode($today));
            $attendances = DB::select( DB::raw('SELECT attendance.start_date_time, MAX(attendance.attendance_id) from attendance where attendance.user_company_id = :muser_company_id and attendance.deleted_at IS null and attendance.start_date_time >= :mtoday and attendance.start_date_time < :mtommarow group by attendance.start_date_time, attendance.attendance_id order by attendance.attendance_id DESC'), array(
                'mtoday' => $today->toDateTimeString(),
                'muser_company_id' => $user_company_id,
                'mtommarow' => $tommarow->toDateTimeString(),

            ));
            $start_date_time = null;
            if(count($attendances) != 0)
                $start_date_time = $attendances[0]->start_date_time;


            /////////////////////////
            /// get list of employes that today are absent
            $absent_users = null;
//            Log::info('user_id:'.json_encode($request->input('user_id')));
            if($user->user_type_id == App\Repositories\UserTypeRepository::CEO ||
                $user->user_type_id == App\Repositories\UserTypeRepository::MiddleCEO ||
                $user->user_type_id == App\Repositories\UserTypeRepository::Admin)
            {
                $absent_users = DB::select( DB::raw('SELECT user.name as user_first_name, user.family as user_last_name, user.user_type_id as user_type, user.user_id, user.user_guid, company.name as company_name, attendance.start_date_time, attendance.end_date_time, MAX(attendance.attendance_id) as max_att_id FROM user join user_company on user.user_id = user_company.user_id LEFT OUTER JOIN attendance on user_company.user_company_id = attendance.user_company_id JOIN company on company.company_id = user_company.company_id LEFT OUTER JOIN attendance p2 ON (user_company.user_company_id = p2.user_company_id AND (attendance.attendance_id < p2.attendance_id OR attendance.attendance_id = p2.attendance_id AND attendance.attendance_id < p2.attendance_id) AND p2.deleted_at is null)WHERE p2.attendance_id IS NULL and  p2.deleted_at IS NULL and attendance.deleted_at is null and company.deleted_at is null and user_company.deleted_at is null and user.deleted_at is null and user_company.company_id in (select user_company.company_id FROM user_company WHERE user_company.user_id = :muser_id1 AND user_company.deleted_at is NULL)and user.user_type_id not in (0, 1) and user.user_id <> :muser_id2  GROUP By attendance.user_company_id, user_first_name, user_last_name, user_type,company_name, attendance.start_date_time, attendance.end_date_time, user.user_id, user.user_guid order by attendance.attendance_id DESC'), array(
                    'muser_id1' => $request->input('user_id'),
                    'muser_id2' => $request->input('user_id'),
                ));
            }






            $usert = User::find($user->user_id);
            $destinationPath = storage_path() . '/app/avatars';
            $allFiles = scandir($destinationPath);

            $filename = $usert->user_guid;

            $file_length = strlen($filename);
            foreach ($allFiles as $key => $value) {
                if (substr($value, 0, $file_length) == $filename) {
                    $contents = File::get($destinationPath.'/'.$value);
                    $usert -> image = base64_encode($contents);
                    break;
                }
            }


//            $absent_users = null;
            //////////////////////////

            return json_encode(['token' => $token, 'user' => $usert, 'qr_code' => $qr_code, 'start_date_time' => $start_date_time, 'company_name' => $company->name, 'users_attendances' => $absent_users, 'error' => 0, 'tag' => RequestResponseAPI::TAG_INDEX_HOME]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_HOME]);
        }

    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiGetVersion(Request $request)
    {

        ///////////////////check token validation/////////////
        //nothing for here ... no need for token
        ////////////////////////////////////////////////////////

        /////validation
        if (!$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_GET_VERSION_HOME]);

        }
        try {

            $version = "2.5";
            $link = "https://cafebazaar.ir/app/ir.fardan7eghlim.attentra/?l=fa";
            $min_time_interval = "300000";//meysam - must be 300000
            $min_distance_interval = "100";//meysam - must be 100
            //$link = "";
//            $message = "متن اضطراری";
            $message = "";
            $server_check_interval = 20;//meysam - in minutes - must be 20

            return json_encode([ 'server_check_interval'=> $server_check_interval, 'message'=>$message, 'version' => $version,'link' => $link, "min_time_interval" => $min_time_interval, "min_distance_interval" => $min_distance_interval, 'error' => 0, 'tag' => RequestResponseAPI::TAG_GET_VERSION_HOME]);

        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository(0, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_GET_VERSION_HOME]);
        }

    }
}
