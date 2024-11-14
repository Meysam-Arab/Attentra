<?php
/**
 * Created by PhpStorm.
 * User: hooman-pc
 * Date: 08/02/2017
 * Time: 11:28 AM
 */
namespace App\Repositories;
use App;
use App\Module;
use ViewComponents\Eloquent\EloquentDataProvider;
use App\Repositories\Contracts\ModuleRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class ModuleRepository implements ModuleRepositoryInterface
{
    protected $module;

    const attendanceModule=1;

    const missionModule=2;

    const newEmployeeModule=3;

    const newCompanyModule=4;

    const newTrackingModule=5;

    //status
    const StatusOK=0;
    const StatusEnd=1;
    const StatusVerge=2;
    ////////////////////////////////

    public function __construct(Module $module)
    {

        $this->module = $module;
        ///////////////////////
    }

    public function initialize()
    {
        $this->module->module_id=null;
        $this->module->module_guid=null;
        $this->module->is_active=null;
        $this->module->limit_value=null;
        $this->module->price=null;
    }

    public function initializeByRequest($request)
    {
        $this->module->module_id=$request->input('module_id');
        $this->module->module_guid=$request->input('module_guid');
        if (Input::get('is_active') === 'yes') {
            $this->module->is_active = 1;
        } else {
            $this->module->is_active = 0;
        }
        $this->module->limit_value=$request->input('limit_value');
        $this->module->price=$request->input('price');
    }

    public function getFullDetailModule( $params1,$params2,$params3)
    {
        $query = $this->module->newQuery();
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

        $provider = new EloquentDataProvider($query);
        $modules = $query->get();

        return array($provider,$modules);
    }

    public function makeWhere($query){
        if($this->module->company_id != null){
            $query->where('module.'.'module_id', '=', $this->module->module_id);
        }
        if($this->module->company_guid != null){
            $query->where('module.'.'module_guid', '=', $this->module->module_guid);
        }
        if( $this->module->is_active != null){
            $query->where('module.'.'is_active', '=', $this->module->is_active);
        }
        if($this->module->name != null){
            $query->where('module.'.'limit_value', '=', $this->module->limit_value);
        }
        if($this->module->name != null){
            $query->where('module.'.'price', '=', $this->module->price);
        }

        return $query;
    }

    public function all()
    {
        // no use
    }

    public function delete()
      {
          // no use
      }

    public function select()
    {
        $query = $this->module->newQuery();
        if($this->module->module_id != null){
            $query->where('module_id', '=', $this->module->module_id);
        }
        $query->where('deleted_at', null);
        $provider = new EloquentDataProvider($query);
        $company = $query->get();

        return array($provider,$company);
    }

    public function update($request)
    {
        self::initializeByRequest($request);
        $oldModule = (new App\Module)->find($request['module_id']);

        if (Input::get('is_active') === 'yes') {
            $oldModule->is_active = 1;
        } else {
            $oldModule->is_active = 0;
        }
        $oldModule->limit_value=$request->input('limit_value');
        $oldModule->price=$request->input('price');
        $oldModule->save();
    }

    public function paginate()
    {
        // no use
    }

    public function store()
    {
        $this->module->module_guid = uniqid('',true);
        $this->module->save();
        return $this->module->module_guid;
    }

    public function findBy($field, $value)
    {
        return Module::where($field, $value)->get();

    }

    public function exist($id, $guid)
    {
        // no use
    }

    public function find($id)
    {
        // no use
    }

    public function findByIdAndGuid($id, $guid)
    {
        // no use
    }



//    public static function api_CheckModuleValidation($empId, $companyId, $companyCount, $employeCount, $trackPointCount, $attendance, $trackShow, $mission)
    public static function api_CheckModuleValidation( $userId, $companyId, $moduleId)
    {
        $ceoUser = null;
        if($userId != null)
            $ceoUser = UserRepository::API_GetManager($userId);
        $arrResult = array();
        if($moduleId == ModuleRepository::newTrackingModule)
        {
            list($status,$purchased,$remained) = ModuleRepository::checkTrackPointCount($ceoUser->user_id,20);
            $arrResult[] = array($status,  $purchased, $remained);
        }
        if($moduleId == ModuleRepository::newCompanyModule)
        {
            list($status,$purchased,$remained) =  ModuleRepository::checkCompanyCount($ceoUser->user_id,20);

            $arrResult[] = array($status,  $purchased, $remained);
        }
        if($moduleId == ModuleRepository::newEmployeeModule)
        {
            list($status,$purchased,$remained) =  ModuleRepository::checkEmployeeCount($ceoUser->user_id,20);
            $arrResult[] = array($status,  $purchased, $remained);
        }
        if($moduleId == ModuleRepository::attendanceModule)
        {
            list($status,$purchased,$remained) =  ModuleRepository::checkAttendanceTime($companyId,20);
            //here purchased is end date for module and remained is number of remained dayes...
//            $arrResult[] = array("status" => $status, "purchased" => $purchased, "remained" => $remained);
            $arrResult[] = array($status,  $purchased, $remained);

        }
        if($moduleId == ModuleRepository::missionModule)
        {

            list($status,$purchased,$remained) =  ModuleRepository::checkMissionTime($companyId,20);
            //here purchased is end date for module and remained is number of remained dayes...
            $arrResult[] = array($status,  $purchased, $remained);
        }

        return $arrResult[0];
    }
    public static function checkTrackPointCount($ceoId, $warningPercentage)
    {

        $purchasedPointCount = App\Repositories\CompanyUserModuleRepository::getPurchasedPointCounts($ceoId);
        $storedPointCount = App\Repositories\TrackRepository::getStoredPointCounts($ceoId);

        if(intval($purchasedPointCount) > intval($storedPointCount))
        {
            if((($warningPercentage * $purchasedPointCount)/100) >(intval($purchasedPointCount)-$storedPointCount))
            {
                $status =  ModuleRepository::StatusVerge;

            }
            else
            {
                $status =  ModuleRepository::StatusOK;

            }
        }
        else
        {
            $status =  ModuleRepository::StatusEnd;
        }

        return array($status,$purchasedPointCount, $storedPointCount);
    }

    public static function checkCompanyCount($ceoId, $warningPercentage)
    {
        $storedCount = count(UserRepository::getCeoCompanies($ceoId));
        $purchasedCount = App\Repositories\CompanyUserModuleRepository::getPurchasedCompanyCounts($ceoId);

        if(intval($purchasedCount) > intval($storedCount))
        {

            if((($warningPercentage * intval($purchasedCount))/100) > (intval($purchasedCount)-$storedCount))
            {
                $status =  ModuleRepository::StatusVerge;

            }
            else
            {
                $status =  ModuleRepository::StatusOK;

            }
        }
        else
        {
            $status =  ModuleRepository::StatusEnd;
        }

        return array($status,$purchasedCount, $storedCount);
    }

    public static function checkEmployeeCount($ceoId, $warningPercentage)
    {
        $storedCount = count(UserRepository::getCeoEmployess($ceoId));
        $purchasedCount = App\Repositories\CompanyUserModuleRepository::getPurchasedEmployeeCounts($ceoId);

        if(intval($purchasedCount) > intval($storedCount))
        {
            if((($warningPercentage * $purchasedCount)/100) < $storedCount)
            {
                $status =  ModuleRepository::StatusVerge;

            }
            else
            {
                $status =  ModuleRepository::StatusOK;

            }
        }
        else
        {
            $status =  ModuleRepository::StatusEnd;
        }

        return array($status,$purchasedCount, $storedCount);
    }

    public static function checkAttendanceTime($companyId, $warningPercentage)
    {
        $currentDateTime = Carbon::now();
        $purchasedDateTime = App\Repositories\CompanyUserModuleRepository::getPurchasedAttendanceTime($companyId);

        $purchasedDateTime = Carbon::parse($purchasedDateTime);

        if($purchasedDateTime ->gt($currentDateTime))
        {

            $length = $purchasedDateTime->diffInDays($currentDateTime);
            if((($warningPercentage * 30)/100) < $length)
            {
                $status =  ModuleRepository::StatusVerge;
            }
            else
            {
                $status =  ModuleRepository::StatusOK;
            }
        }
        else
        {
            $status =  ModuleRepository::StatusEnd;
        }

        return array($status,$purchasedDateTime->toDateTimeString(), $currentDateTime->toDateTimeString());
    }

    public static function checkMissionTime($companyId, $warningPercentage)
    {
        $currentDateTime = Carbon::now();

        $purchasedDateTime = App\Repositories\CompanyUserModuleRepository::getPurchasedMissionTime($companyId);
        $purchasedDateTime = Carbon::parse($purchasedDateTime);

        if($purchasedDateTime ->gt($currentDateTime))
        {

            $length = $purchasedDateTime->diffInDays($currentDateTime);
            if((($warningPercentage * 30)/100) < $length)
            {
                $status =  ModuleRepository::StatusVerge;

            }
            else
            {
                $status =  ModuleRepository::StatusOK;

            }
        }
        else
        {
            $status =  ModuleRepository::StatusEnd;
        }

        return array($status,$purchasedDateTime->toDateTimeString(), $currentDateTime->toDateTimeString());
    }


}