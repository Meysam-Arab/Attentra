<?php
/**
 * Created by PhpStorm.
 * User: hooman-pc
 * Date: 15/01/2017
 * Time: 12:02 PM
 */

namespace App\Repositories;
use App;
use App\Attendance;
use ViewComponents\Eloquent\EloquentDataProvider;
use App\Repositories\Contracts\AttendaceRepositpryInterface;
use DB;
use Illuminate\Support\Facades\Auth;
use Log;

class AttendanceRepository implements AttendaceRepositpryInterface
{
    protected $attendance;

    const ENCRYPTION_PASSWORD= "meysamhomman1396";

    public function __construct(Attendance $attendance)
    {

        $this->attendance = $attendance;

    }

    public function initialize()
    {
        $this->attendance-> attendance_id = null;
        $this ->attendance-> attendance_guid =null;
        $this ->attendance-> user_id =null;
        $this->attendance->start_date_time = null;
        $this->attendance->end_date_time = null;
        $this->attendance->is_mission = null;
        $this->attendance->coordinates = null;
        $this->attendance->type = null;


    }

    public function initializeByRequest($request = null)
    {
        $this->attendance-> attendance_id = $request ->input('attendance_id');
        $this ->attendance-> attendance_guid = $request ->input( 'attendance_guid');
        $this ->attendance-> user_company_id = $request -> input('user_company_id');
        $this->attendance->start_date_time = $request -> input('start');
        if(trim($request['end'])!=null){
            $this->attendance->end_date_time = $request ->input('end');
        }else{
            $this->attendance->end_date_time = null;
        }
        $this->attendance->is_mission = $request ->input( 'is_mission');
        $this->attendance->coordinates = $request ->input( 'coordinates');
        if(!is_null($request ->input( 'type')))
            $this->attendance->type = $request ->input( 'type');


    }

    public function initializeByObject($object)
    {
        $this->attendance-> attendance_id = $object ->attendance_id;
        $this ->attendance-> attendance_guid = $object -> attendance_guid;
        $this ->attendance-> user_company_id = $object ->user_company_id;
        $this->attendance->start_date_time = $object ->start_date_time;
        $this->attendance->end_date_time = $object -> end_date_time;
        $this->attendance->is_mission = $object -> is_mission;
        $this->attendance->coordinates = $object -> coordinates;
        if(!is_null($object -> type))
            $this->attendance->type = $object -> type;

    }

    public function set_user_company_id($user_id,$company_id)
    {
        $user_company = DB::table('user_company')->where([
            ['company_id', '=', $company_id],
            ['user_id', '=', $user_id],
            ['deleted_at', '=', null],
        ])->get();
        if(count($user_company)>0){
            $this ->attendance-> user_company_id=$user_company[0]->user_company_id;
            return true;
        }
        return false;

    }

    public function setAttendanceIdAndGuid($id,$guid)
    {
        $this->attendance->attendance_id=$id;
        $this->attendance->attendance_guid=$guid;
    }

    public function select()
    {

        $query = $this->attendance->newQuery();
        if($this->attendance->attendance_id != null){
            $query->where('attendance_id', '=', $this->attendance->attendance_id);
        }
        $query->where('deleted_at', null);
        $provider = new EloquentDataProvider($query);
        $attendances = $query->get();

//

        return array($provider,$attendances);
    }

    public function selectForProvider()
    {

        $query = $this->attendance->newQuery();
        if($this->attendance->attendance_id != null){
            $query->where('attendance_id', '=', $this->attendance->attendance_id);
        }
        $query->where('user_id', '=', Auth::user()->user_id);
        $query->where('deleted_at', null);
        $provider = new EloquentDataProvider($query);
        $attendances = $query->get();



        return array($provider,$attendances);
    }

    public function selectForArray()
    {

        $query = $this->attendance->newQuery();
        if($this->attendance->attendance_id != null){
            $query->where('attendance_id', '=', $this->attendance->attendance_id);
        }
        $query->where('user_id', '=', Auth::user()->user_id);
        $query->where('deleted_at', null);
        $provider = new EloquentDataProvider($query);
        $attendances = $query->get();




        return $attendances;
    }

    public function all()
    {
        return DB::table('attendance')
            ->where('deleted_at',null)
            ->get();


    }

    public function getId()
    {
        return  $this->attendance-> attendance_id;

    }

    public function store()
    {
        $this->attendance->attendance_guid = uniqid('',true);

//        $user->api_token=str_random(60);
        $this->attendance->is_mission = false;
//        $this ->attendance-> user_id =1;
        $this->attendance->save();
    }

    public function api_Store()
    {
        $this->attendance->attendance_guid = uniqid('',true);
        $this->attendance->save();
    }

    public function update($request)
    {

        $attendance=Attendance::find($request->input('attendance_id'));

        $attendance->start_date_time = $request -> input('start');
        if(trim($request['end'])!=null){
            $attendance->end_date_time = $request ->input('end');
        }else{
            $attendance->end_date_time = null;
        }

        $attendance->save();
    }

    public function updateEndDate($end_date,$attendance_id)
    {
        $attendance=self::find($attendance_id);
        $attendance->end_date_time = $end_date;

        $attendance->save();
    }

    public function delete()
    {
        $this->attendance->find($this->attendance->attendance_id)->delete();
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
        return $this->attendance->find($id);
    }

    public function findByIdAndGuid($id, $guid)
    {
        // no code findByIdAndGuid() method.
    }

    public function getFullDetailAttendace( $params1,$params2,$params3,$distinct=null)
    {

        $query = $this->attendance->newQuery();
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

        $provider = new EloquentDataProvider($query);
        $attendaces = $query->get();
        return array($provider,$attendaces);
        Log::info("query:".json_encode($query->get()));

//        return $query->get();
    }

    public function makeWhere($query){
        if($this->attendance->attendance_id != null){
            $query->where('	attendance.'.'attendance_id', '=', $this->attendance->attendance_idattendance_guid);
        }
        if($this->attendance->attendance_guid != null){
            $query->where('	attendance.'.'attendance_guid', '=', $this->attendance->attendance_guid);
        }
        if($this->attendance->user_id != null){
            $query->where('	attendance.'.'user_id', '=', $this->attendance->user_id);
        }
        if( $this->attendance->start_date_time != null){
            $query->where('	attendance.'.'start_date_time', '=', $this->attendance->start_date_time);
        }
        if($this->attendance->end_date_time != null){
            $query->where('	attendance.'.'end_date_time', '=', $this->attendance->end_date_time);
        }
        if( $this->attendance->is_mission != null){
            $query->where('	attendance.'.'is_mission', '=', $this->attendance->is_mission);
        }
        if( $this->attendance->coordinates != null){
            $query->where('	attendance.'.'coordinates', '=', $this->attendance->coordinates);
        }
        if( $this->attendance->type != null){
            $query->where('	attendance.'.'type', '=', $this->attendance->type);
        }

        return $query;
    }

    public function set($id,$guid)
    {
        $this->attendance-> attendance_id  = $id;
        $this ->attendance-> attendance_guid = $guid;

    }
    public static function getAttendance($attendance_id,$attendance_guid)
    {
        return DB::table('attendance')
            ->where('attendance.attendance_id','=',$attendance_id)
            ->where('attendance.attendance_guid','=',$attendance_guid)
            ->where('deleted_at',null)
            ->select('attendance.*')
            ->get();
    }

    public static function getLastAttendanceWithEmptyEndDate($user_company_id)
    {
        $attendances = DB::table('attendance')
            ->where('attendance.user_company_id','=',$user_company_id)
            ->where('attendance.end_date_time','=',null)
//            ->where('attendance_id', DB::raw("(select max(`attendance_id`) from attendance)"))
            ->where('deleted_at',null)
            ->orderBy('attendance_id', 'desc')
            ->select('attendance.*')
            ->get();

        if(count($attendances )== 0)
        {
            return null;
        }
        else
        {
            $attendanceRepo = new AttendanceRepository(new Attendance());
            $attendanceRepo->initializeByObject($attendances[0]);
            return $attendanceRepo;
        }
    }


}