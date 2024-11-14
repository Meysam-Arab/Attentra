<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 3/12/2017
 * Time: 2:03 PM
 */


namespace App\Repositories;

use App\Track;
use App\Repositories\Contracts\TrackRepositoryInterface;
use Log;
use DB;
use Auth;
use Lang;

class TrackRepository implements TrackRepositoryInterface
{

    protected  $track;
    public function __construct(Track $track)
    {

        $this->track = $track;

    }


    public function initialize()
    {
        $this->track->track_id=null;
        $this->track->track_guid=null;
        $this->track->user_id=null;
        $this->track->track_group=null;
        $this->track->latitude=null;
        $this->track->longitude=null;
        $this->track->altitude=null;
        $this->track->accuracy=null;
        $this->track->speed=null;
        $this->track->bearing=null;
        $this->track->battery_power=null;
        $this->track->battery_status=null;
        $this->track->charge_status=null;
        $this->track->charge_type=null;
        $this->track->signal_power=null;
        $this->track->deleted_at = null;

    }

    public function initializeByRequest($request)
    {

        $this->track->track_id=$request->input('track_id');
        $this->track->track_guid=$request->input('track_guid');
        $this->track->user_id=$request->input('user_id');
        $this->track->track_group=$request->input('track_group');
        $this->track->latitude=$request->input('latitude');
        $this->track->longitude=$request->input('longitude');
        $this->track->altitude=$request->input('altitude');
        $this->track->accuracy=$request->input('accuracy');
        $this->track->speed=$request->input('speed');
        $this->track->bearing=$request->input('bearing');
        $this->track->battery_power=$request->input('battery_power');
        $this->track->battery_status=$request->input('battery_status');
        $this->track->charge_status=$request->input('charge_status');
        $this->track->charge_type=$request->input('charge_type');
        $this->track->signal_power=$request->input('signal_power');
        $this->track->created_at=$request->input('created_at');
        $this->track->updated_at=$request->input('updated_at');
        $this->track->deleted_at = null;
    }

    public function select()
    {
        $query = $this->track->newQuery();
        if($this->track->track_id != null){
            $query->where('track_id', '=', $this->track->track_id);
        }
        if($this->track->track_guid != null){
            $query->where('track_guid', '=', $this->track->track_guid);
        }
        if($this->track->user_id != null){
            $query->where('user_id', '=', $this->track->user_id);
        }
        if($this->track->track_group != null){
            $query->where('track_group', '=', $this->track->track_group);
        }
        $query->where('deleted_at', null);
        $tracks = $query->get();

        return $tracks;
    }

    public function all()
    {
        return DB::table('track')
            ->where('deleted_at',null)
            ->get();
    }

    public function paginate()
    {
        // no code paginate() method.
    }

    public function store()
    {
        $this->track->track_guid = uniqid('',true);

        $this->track->save();
    }

    public function update($request)
    {
        self::initializeByRequest($request);
        $oldTrack = $this->track->find($this->track ->track_id);

        DB::table('track')
            ->where('track_id', $this->track->track_id)
            ->where('deleted_at',null)
            ->update(['track.time_zone' => $this->compony->time_zone,'company.name' => $this->compony->name]);

    }

    public function delete()
    {
        $this->track->find($this->track->track_id)->softDeletes();
    }

    public function findBy($field, $value)
    {
        return $this->track->where($field, $value)->get();
    }

    public function exist($id, $guid)
    {
        $query = $this->track->newQuery();
        $query->where('track_id', '=', $id);
        $query->where('track_guid', '=', $guid);
        $tracks = $query->get()->first();
        if (count($tracks) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function find($id)
    {
        return $this->track->find($id);
    }

    public function findByIdAndGuid($id, $guid)
    {
        return $this->track->where($id, $guid)->get();
    }

    ////simplest query
    public function makeWhere($query){
        if($this->track->track_id != null){
            $query->where('	track.'.'track_id', '=', $this->track->track_id);
        }
        if($this->track->track_guid != null){
            $query->where('	track.'.'track_guid', '=', $this->track->track_guid);
        }
        if($this->track->user_id != null){
            $query->where('	track.'.'user_id', '=', $this->track->user_id);
        }
        if( $this->track->track_group != null){
            $query->where('	track.'.'track_group', '=', $this->track->track_group);
        }

        return $query;
    }

    public function getFullDetailTrack( $params1,$params2,$params3)
    {

        $query = $this->track->newQuery();
        //
        if($params1!=null) {
            if(Auth::user()->user_type_id == 0)
            {
                $query=\App\Utility::fillQueryAlias($query,$params1,true);
            }
            else
            {
                $query=\App\Utility::fillQueryAlias($query,$params1);
            }

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
        $tracks = $query->get();

        return $tracks;

    }



    public static function getStoredPointCounts($userId)
    {

        $relatedRecords = DB::select('SELECT * from track where user_id in (select user_id from user_company 
        where company_id in (select company_id from user_company where user_id = '.$userId.'))');


        return count($relatedRecords);
    }

    public static function getGsmLevelString($level)
    {
        if($level == 0)
            return Lang::get('messages.lbl_signal_none_or_unknown');
        if($level == 1)
            return Lang::get('messages.lbl_signal_poor');
        if($level == 2)
            return Lang::get('messages.lbl_signal_moderate');
        if($level == 3)
            return Lang::get('messages.lbl_signal_good');
        if($level == 4)
            return Lang::get('messages.lbl_signal_great');
        return Lang::get('messages.lbl_signal_none_or_unknown');
    }

    public static function getPlugTypeString($status)
    {

        if($status == 0)
            return Lang::get('messages.lbl_battery_not_plugged_in');
        if($status == 1)
            return Lang::get('messages.lbl_battery_plugged_ac');
        if($status == 2)
            return Lang::get('messages.lbl_battery_plugged_usb');
        if($status == 4)
            return Lang::get('messages.lbl_battery_plugged_wireless');
        return Lang::get('messages.lbl_status_unknown');


    }

    public static function getBatryStatusString($status)
    {

        if($status == 2)
            return Lang::get('messages.lbl_battery_health_good');
        if($status == 3)
            return Lang::get('messages.lbl_battery_health_over_heat');
        if($status == 4)
            return Lang::get('messages.lbl_battery_health_dead');
        if($status == 5)
            return Lang::get('messages.lbl_battery_health_over_voltage');
        if($status == 6)
            return Lang::get('messages.lbl_battery_health_unspecific_failure');
        return Lang::get('messages.lbl_status_unknown');
    }

    public static function getCharcheStatusString($status)
    {

        if($status == 2)
            return Lang::get('messages.lbl_battery_status_charging');
        if($status == 3)
            return Lang::get('messages.lbl_battery_status_discharging');
        if($status == 4)
            return Lang::get('messages.lbl_battery_status_not_charging');
        if($status == 5)
            return Lang::get('messages.lbl_battery_status_full');
        return Lang::get('messages.lbl_status_unknown');


    }

}