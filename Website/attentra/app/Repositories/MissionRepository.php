<?php
/**
 * Created by PhpStorm.
 * User: hooman-pc
 * Date: 18/01/2017
 * Time: 05:16 PM
 */

namespace App\Repositories;
use App;
use App\Mission;
use App\User;
use App\Repositories\Contracts\MissionRepositoryInterface;
use DB;
use Log;
use Input;
class MissionRepository implements MissionRepositoryInterface
{
    protected $mission;

    public function __construct(Mission $mission)
    {

        $this->mission = $mission;
    }

    public function initialize()
    {
        $this->mission->mission_id=null;
        $this->mission->mission_guid=null;
        $this->mission->title=null;
        $this->mission->description=null;
        $this->mission->start_date_time=null;
        $this->mission->end_date_time=null;
        $this->mission->deleted_at=null;
    }

    public function initializeByRequest($request)
    {
        $this->mission->mission_id=$request->input('mission_id');
        $this->mission->mission_guid=$request->input('mission_guid');
        $this->mission->title=$request->input('title');
        $this->mission->description=$request->input('description');
        $this->mission->start_date_time=$request->input('start_date_time');
        $this->mission->end_date_time=$request->input('end_date_time');
    }

    public function getMissionGuid()
    {
        return $this->mission->mission_guid;
    }

    public function getFullDetailMission( $params1,$params2,$params3,$distinct=null)
    {

        $query = $this->mission->newQuery();
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
        $missions = $query->get();
        return $missions;

//        return $query->get();
    }

    public function makeWhere($query){
        if($this->mission->mission_id != null){
            $query->where('	mission.'.'mission_id', '=', $this->mission->mission_id);
        }
        if($this->mission->mission_guid != null){
            $query->where('	mission.'.'mission_guid', '=', $this->mission->mission_guid);
        }
        if($this->mission->title != null){
            $query->where('	mission.'.'title', '=', $this->mission->title);
        }
        if( $this->mission->description != null){
            $query->where('	mission.'.'description', '=', $this->mission->description);
        }
        if($this->mission->start_date_time != null){
            $query->where('	mission.'.'start_date_time', '=', $this->mission->start_date_time);
        }
        if( $this->mission->end_date_time != null){
            $query->where('	mission.'.'end_date_time', '=', $this->mission->end_date_time);
        }
        return $query;
    }

    public function select()
    {
        // no code select() method.
    }

    public function all()
    {
        // no code all() method.
    }

    public function paginate()
    {
        // no code paginate() method.
    }

    public function store()
    {
        $this->mission->mission_guid = uniqid('',true);
        $this->mission->save();
    }

        public function update($request)
        {
            self::initializeByRequest($request);
            $oldMission = $this->mission->find($request['mission_id']);


            $oldMission->title= $request->input('title');
            $oldMission->description= $request->input('description');
            $oldMission->start_date_time= $request->input('start_date_time');
            $oldMission->end_date_time= $request->input('end_date_time');
            $oldMission->save();
        }

    public function delete()
    {
        $RESULT=$this->findByIdAndGuid($this->mission->mission_id,$this->mission->mission_guid);
        if(!$RESULT)
            return false;
        else{
            $RESULT->delete();
            return true;
        }


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
        return $this->mission->find($id);
    }

    public function findByIdAndGuid($id, $guid)
    {
        try
        {
            $query = $this->mission->newQuery();
            $query->where('mission_id', '=', $id);
            $query->where('mission_guid', 'like', $guid);
            $missions = $query->get();
            if(count($missions)==0)
                return false;
            return $missions[0];
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function set($id,$guid)
    {
        $this->mission->mission_id = $id;
        $this->mission->mission_guid = $guid;

    }


    public static function getCeoOfThisMission($mission_id)
    {
        $ceos = DB::select( DB::raw('SELECT * FROM `user` WHERE user.user_type_id = :muser_type_id and user.user_id in(select user_company.user_id from user_company WHERE user_company.company_id in (select user_company.company_id from user_company join user_mission on user_company.user_company_id = user_mission.user_company_id where user_mission.mission_id = :mmission_id))'), array(
            'mmission_id' => $mission_id,
            'muser_type_id' => UserTypeRepository::CEO,
        ));


        return $ceos;
    }
}