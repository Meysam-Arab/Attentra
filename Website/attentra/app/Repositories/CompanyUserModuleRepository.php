<?php
/**
 * Created by PhpStorm.
 * User: hooman-pc
 * Date: 12/02/2017
 * Time: 09:50 AM
 */

namespace App\Repositories;
use App\Company;
use App\CompanyUserModule;
use App\Repositories\Contracts\CompanyUserModuleRepositoryInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use ViewComponents\Eloquent\EloquentDataProvider;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;
use DB;
use File;

class CompanyUserModuleRepository implements CompanyUserModuleRepositoryInterface
{
    protected  $CompanyUserModule;
    public function __construct(CompanyUserModule $CompanyUserModule)
    {

        $this->CompanyUserModule = $CompanyUserModule;

    }

    public function initialize()
    {
        $this->CompanyUserModule->company_id=null;
        $this->CompanyUserModule->module_id=null;
        $this->CompanyUserModule->end_date=null;
        $this->CompanyUserModule->is_active=null;
        $this->CompanyUserModule->cost=null;
    }

    public function initializeByRequest($request)
    {
        $To_Extend_Flager=true;
        $module = DB::table('module')->where('module_id', $request['module_id'])->where('deleted_at',null)->first();
        if($request['company_id']!=="null")
            $module_company_user = DB::table('company_user_module')->where(['module_id'=> $request['module_id'],'company_id'=> $request['company_id']])->where('deleted_at',null)->orderBy('created_at', 'desc')->first();
        else
            $module_company_user = DB::table('company_user_module')->where(['module_id'=> $request['module_id'],'user_id'=> Auth::user()->user_id])->where('deleted_at',null)->orderBy('created_at', 'desc')->first();

        $last_end_date=null;
        $now = Carbon::now();

        if ($module_company_user === null) {
            $To_Extend_Flager=false;
        }else{
            $last_end_date = new Carbon($module_company_user->end_date);
            $second=$last_end_date->getTimestamp() - $now->getTimestamp();
            if($second>0){
                // $module_company_user->end_date is newer than created at
                $To_Extend_Flager=true;

            }
            else
            {
                $To_Extend_Flager=false;
            }
        }

        $this->CompanyUserModule->module_id=$request['module_id'];
        $enddate='';
        if($request['time']<=6){
            if($To_Extend_Flager)
                $enddate=$last_end_date->addMonths($request['time'])->addDays($request['time']/2)->toDateTimeString();
            else
                $enddate=Carbon::now()->addMonths($request['time'])->addDays($request['time']/2)->toDateTimeString();
//                $enddate=$enddate->addDays($request['time']/2)->toDateTimeString();

        }elseif ($request['time']==12){
            if($To_Extend_Flager)
                $enddate=$last_end_date->addMonths($request['time'])->toDateTimeString();
            else
                $enddate=Carbon::now()->addMonths($request['time'])->toDateTimeString();

        }
        if($request['company_id']!="null") {
            $this->CompanyUserModule->company_id = $request['company_id'];
            $this->CompanyUserModule->limit_count=0;
            $this->CompanyUserModule->end_date=$enddate;
        }
        else
        {
            $this->CompanyUserModule->user_id=Auth::user()->user_id;
            $this->CompanyUserModule->limit_count=$request['time'];
            $this->CompanyUserModule->end_date=null;
        }



        $this->CompanyUserModule->is_active=1;
        $this->CompanyUserModule->cost=$request['time']*$module->price;

    }

    public function select()
    {
        // no code
    }

    public function all()
    {
        // no code
    }

    public function paginate()
    {
        // no code
    }

    public function store()
    {
        $this->CompanyUserModule->company_user_module_guid = uniqid('',true);
        $this->CompanyUserModule->save();
    }

    public function update($request)
    {
        // no code
    }

    public function delete()
    {
        // no code
    }

    public function findBy($field, $value)
    {
        // no code
    }
    //if this method return null that mean is noting module
    public function exist($module_id, $company_id=null, $user_id=null)
    {
        $count=null;
        for($index=0;$index< session('CompanyCount');$index++){
            if(session('companiesId'.$index)==$company_id){
                $count=$index;
                break;
            }
        }

        if($company_id !=null){

            try {
                $end= DB::table('company_user_module')
                    ->where('module_id','=',$module_id)
                    ->where('company_id','=',$company_id)
                    ->where('deleted_at',null)
                    ->orderBy('company_user_module_id', 'desc')
                    ->first();
//                log::info('end of '.json_encode($end));
                if($end==null)
                    return array(null, null);
            }
            catch (\Exception $e) {
                return $e->getMessage();
            }
            //$end=$end->end_date;
//            date_default_timezone_set(session('companiesTimezone'.$count));
            $end_date = new Carbon($end->end_date);
            $now = Carbon::now();
            $second=$end_date->getTimestamp() - $now->getTimestamp();
            return array($second, $end->limit_count,);
        }
        else if($user_id !=null){
            $end= DB::table('company_user_module')
                ->where('module_id','=',$module_id)
                ->where('user_id','=',$user_id)
                ->where('deleted_at',null)->get();
            if($end->isEmpty())
                return array(null, null);
            $end_date = new Carbon($end[0]->end_date);
            $now = Carbon::now();
            $second=$end_date->getTimestamp() - $now->getTimestamp();
            return array($second, $end[0]->limit_count,);

        }
        else
            return array(null, null);
    }
    //if this method return null that mean is noting module
    public function apiExist($module_id, $company_id=null, $user_id=null)
    {
        if($company_id !=null){
            $company_rep = new CompanyRepository(new Company());
            $company = $company_rep->find($company_id);

            try {
                $end= DB::table('company_user_module')
                    ->where('module_id','=',$module_id)
                    ->where('company_id','=',$company_id)
                    ->where('deleted_at',null)
                    ->orderBy('company_user_module_id', 'desc')
                    ->first();
                if($end==null)
                    return array(null, null);
            }
            catch (\Exception $e) {
                throw $e;
            }
            //$end=$end->end_date;
            date_default_timezone_set($company->time_zone);
            $end_date = new Carbon($end->end_date);
            $now = Carbon::now();
            $second=$end_date->getTimestamp() - $now->getTimestamp();
            return array($second, $end->limit_count,);
        }
        else if($user_id !=null){
            $end= DB::table('company_user_module')
                ->where('module_id','=',$module_id)
                ->where('user_id','=',$user_id)
                ->where('deleted_at',null)->get();
            if($end->isEmpty())
                return array(null, null);
            $end_date = new Carbon($end[0]->end_date);
            $now = Carbon::now();
            $second=$end_date->getTimestamp() - $now->getTimestamp();
            return array($second, $end[0]->limit_count,);

        }
        else
            return array(null, null);
    }
    public function find($id)
    {
        //no code
    }

    public function findByIdAndGuid($id, $guid)
    {
        // no code
    }


    //meysam

    /**
     * @param $ceoId
     * @return mixed
     */
    public static function getPurchasedPointCounts($ceoId)
    {
//        $sumManHour = DB::table('company_user_module')
//            ->select(DB::raw('SUM(limit_count) as total_man_hour'))
//            ->whereRaw('company_user_module.module_id = '. ModuleRepository::newManHourModule)
//            ->whereRaw('company_user_module.user_id = '.$ceoId)
//        ->where('deleted_at',null)
//            ->get();
//
//        return $sumManHour;

            $ceoRelatedRecords = DB::table('company_user_module')
            ->select(DB::raw('SUM(limit_count) as total_point_count'))
            ->whereRaw('company_user_module.module_id = '. ModuleRepository::newTrackingModule)
            ->whereRaw('company_user_module.user_id = '.$ceoId)
            ->where('deleted_at',null)
            ->get();

            return $ceoRelatedRecords[0]->total_point_count;

    }

    ///
    /// //meysam
    public static function getPurchasedCompanyCounts($ceoId)
    {
        $ceoRelatedRecords = DB::table('company_user_module')
            ->select(DB::raw('SUM(limit_count) as total_point_count'))
            ->whereRaw('company_user_module.module_id = '. ModuleRepository::newCompanyModule)
            ->whereRaw('company_user_module.user_id = '.$ceoId)
            ->where('deleted_at',null)
            ->get();

//        $users = DB::table('users')
//            ->groupBy('account_id')
//            ->having('account_id', '>', 100)
//        ->where('deleted_at',null)
//            ->get();

        return $ceoRelatedRecords[0]->total_point_count;

    }

    //meysam
    public static function getPurchasedEmployeeCounts($ceoId)
    {
        $ceoRelatedRecords = DB::table('company_user_module')
            ->select(DB::raw('SUM(limit_count) as total_point_count'))
            ->whereRaw('company_user_module.module_id = '. ModuleRepository::newEmployeeModule)
            ->whereRaw('company_user_module.user_id = '.$ceoId)
            ->where('deleted_at',null)
            ->get();

        return $ceoRelatedRecords[0]->total_point_count;

    }

    //meysam
    public static function getPurchasedAttendanceTime($companyId)
    {
        $relatedRecords = DB::table('company_user_module')
            ->select(DB::raw('*'))
            ->whereRaw('company_user_module.module_id = '. ModuleRepository::attendanceModule)
            ->whereRaw('company_user_module.company_Id = '.$companyId)
            ->where('deleted_at',null)
            ->latest()
            ->first();

        return $relatedRecords->end_date;

    }

    //meysam
    public static function getPurchasedMissionTime($companyId)
    {
        $relatedRecords = DB::table('company_user_module')
            ->select(DB::raw('*'))
            ->whereRaw('company_user_module.module_id = '. ModuleRepository::missionModule)
            ->whereRaw('company_user_module.company_Id = '.$companyId)
            ->where('deleted_at',null)
            ->latest()
            ->first();

        return $relatedRecords->end_date;
    }

    //meysam
//    public function api_InitializeByRequest($request, $userId)
//    {
//        $To_Extend_Flager=true;
//        $module = DB::table('module')->where('module_id', $request['module_id'])->where('deleted_at',null)->first();
//        if($request['company_id']!=="null")
//            $module_company_user = DB::table('company_user_module')->where(['module_id'=> $request['module_id'],'company_id'=> $request['company_id']])->orderBy('created_at', 'desc')->where('deleted_at',null)->first();
//        else
//            $module_company_user = DB::table('company_user_module')->where(['module_id'=> $request['module_id'],'user_id'=> $userId])->orderBy('created_at', 'desc')->where('deleted_at',null)->first();
//
//        $last_end_date=null;
//        $now = Carbon::now();
//
//        if ($module_company_user === null) {
//            $To_Extend_Flager=false;
//        }else{
//            $last_end_date = new Carbon($module_company_user->end_date);
//            $second=$last_end_date->getTimestamp() - $now->getTimestamp();
//            if($second>0){
//                // $module_company_user->end_date is newer than created at
//                $To_Extend_Flager=true;
//
//            }
//            else
//            {
//                $To_Extend_Flager=false;
//            }
//        }
//
//        $this->CompanyUserModule->module_id=$request['module_id'];
//        $enddate='';
//        if($request['company_id'] != null)
//        {
////            $cp = new App\Repositories\CompanyRepository(new App\Company());
////            $company = $cp->find($request['company_id']);
////            $now = Carbon::now(new DateTimeZone($company->time_zone))->addMonths($request['time']);
////                $this->CompanyUserModule->end_date = $now->toDateTimeString();
//            $request['end_date'] = $now->toDateTimeString();
//        }
//        if($request['time']<=6){
//            if($To_Extend_Flager)
//                $enddate=$last_end_date->addMonths($request['time'])->addDays($request['time']/2)->toDateTimeString();
//            else
//                $enddate=Carbon::now()->addMonths($request['time'])->addDays($request['time']/2)->toDateTimeString();
//
//        }elseif ($request['time']==12){
//            if($To_Extend_Flager)
//                $enddate=$last_end_date->addMonths($request['time'])->toDateTimeString();
//            else
//                $enddate=Carbon::now()->addMonths($request['time'])->toDateTimeString();
//
//        }
//        if($request['company_id']!="null") {
//            $this->CompanyUserModule->company_id = $request['company_id'];
//            $this->CompanyUserModule->limit_count=0;
//            $this->CompanyUserModule->end_date=$enddate;
//        }
//        else
//        {
//            $this->CompanyUserModule->user_id=$userId;
//            $this->CompanyUserModule->limit_count=$request['limit_count'];
//            $this->CompanyUserModule->end_date=null;
//        }
//
//        $this->CompanyUserModule->is_active=1;
//        $this->CompanyUserModule->cost=$request['time']*$module->price;
//
//    }
}