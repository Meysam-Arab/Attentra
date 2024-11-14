<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/13/2017
 * Time: 10:15 AM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App;
use Carbon\Carbon;
use DateTimeZone;
use Log;
use Validator;
use DB;
use Auth;
use App\Repositories\CompanyUserModuleRepository;
use JWTAuth;
use App\RequestResponseAPI;


class API_CompanyUserModuleController extends Controller
{
    protected  $CompanyUserModule;
    public function __construct(CompanyUserModuleRepository $CompanyUserModule)
    {
        $this->CompanyUserModule = $CompanyUserModule;
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
        if (
            !$request->has('module_id')||
            !$request->has('tag')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_COMPANY_USER_MODULE]);

        }

        $module = new App\Module();
        $module = $module->find($request->input('module_id'));
        $companyUserModule = new App\CompanyUserModule();
        try
        {
            DB::beginTransaction();
            $cp = new App\Repositories\CompanyRepository(new App\Company());
            $company = null;
            if($request['company_id']!="null")
            {
                $company = $cp->find($request['company_id']);
                $now = Carbon::now(new DateTimeZone($company->time_zone));
                $module_company_user = DB::table('company_user_module')->where(['module_id'=> $request['module_id'],'company_id'=> $request['company_id']])->orderBy('created_at', 'desc')->where('deleted_at',null)->first();

            }
            else
            {
                $module_company_user = DB::table('company_user_module')->where(['module_id'=> $request['module_id'],'user_id'=> $user->user_id])->orderBy('created_at', 'desc')->where('deleted_at',null)->first();
                $now = Carbon::now();
            }

            $last_end_date=null;


            if ($module_company_user == null) {
                $To_Extend_Flager=false;
            }else{
                $last_end_date = new Carbon($module_company_user->end_date);
                if($company != null)
                    $last_end_date->setTimezone($company->time_zone);
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
            $companyUserModule->module_id=$request['module_id'];

            $enddate='';
            if($request->has('time')) {
                if($request['time']<=6){
                    if($To_Extend_Flager)
                    {
                        $enddate=$last_end_date->addMonths($request['time'])->addDays($request['time']/2)->toDateTimeString();
                    }
                    else
                    {
                        $enddate=$now->addMonths($request['time'])->addDays($request['time']/2)->toDateTimeString();

                    }

                }elseif ($request['time']==12){
                    if($To_Extend_Flager)
                        $enddate=$last_end_date->addMonths($request['time'])->toDateTimeString();
                    else
                        $enddate=$now->addMonths($request['time'])->toDateTimeString();

                }

                $companyUserModule->cost=$request['time']*$module->price;
            }
            else
            {
                $companyUserModule->limit_count=$request['limit_count'];
                if($request['module_id'] == App\Repositories\ModuleRepository::newTrackingModule ||
                    $request['module_id'] == App\Repositories\ModuleRepository::newCompanyModule ||
                    $request['module_id'] == App\Repositories\ModuleRepository::newEmployeeModule )
                {
                    $companyUserModule->cost=($module->price * $request['limit_count']);
                }
                else
                {
                    $companyUserModule->cost=$module->price;
                }

            }
            if($request['company_id']!="null") {
                $companyUserModule->company_id = $request['company_id'];
                $companyUserModule->limit_count=0;
                $companyUserModule->end_date=$enddate;
            }
            else
            {
                $companyUserModule->user_id=$user->user_id;
                $companyUserModule->limit_count=$request['limit_count'];
                $companyUserModule->end_date=null;
            }
            $companyUserModule->is_active=1;

            if(!App\Repositories\UserRepository::DecreaseCharge($user->user_id,$user->user_guid,$companyUserModule->cost))
                return json_encode(['error' => RequestResponseAPI::ERROR_NOT_ENOUGH_CHARGE_CODE, 'tag' => RequestResponseAPI::TAG_STORE_COMPANY_USER_MODULE]);

            $companyUserModule->company_user_module_guid = uniqid('',true);
            $companyUserModule->save();

            DB::commit();

            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_STORE_COMPANY_USER_MODULE]);

        }
        catch (Exception $ex) {
            DB::rollback();
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_STORE_COMPANY_USER_MODULE]);

        }


    }

    public function api_register_default_free_moduals($ceoId)
    {
        $moduls= DB::table('module')
            ->where('deleted_at',null)
            ->get();

        foreach ($moduls as $module){
            if($module->module_id==App\Repositories\ModuleRepository::newEmployeeModule){
                $guid = uniqid('',true);
                DB::table('company_user_module')->insert(
                    ['company_user_module_guid' => $guid,
                        'company_id' => null,
                        'user_id' => $ceoId,
                        'module_id' => $module->module_id,
                        'cost' => 0,
                        'limit_count' => $module->limit_value,
                        'end_date' => null,
                        'is_active' => 1,
                        'created_at' => Carbon::now('Asia/Tehran'),
                        'updated_at' =>  Carbon::now('Asia/Tehran'),
                        'deleted_at' => null,]
                );
            }
            else if($module->module_id==App\Repositories\ModuleRepository::newCompanyModule){
                $guid = uniqid('',true);
                DB::table('company_user_module')->insert(
                    ['company_user_module_guid' => $guid,
                        'company_id' => null,
                        'user_id' => $ceoId,
                        'module_id' => $module->module_id,
                        'cost' => 0,
                        'limit_count' => $module->limit_value,
                        'end_date' => null,
                        'is_active' => 1,
                        'created_at' => Carbon::now('Asia/Tehran'),
                        'updated_at' =>  Carbon::now('Asia/Tehran'),
                        'deleted_at' => null,]
                );
            }
            else if($module->module_id==App\Repositories\ModuleRepository::newTrackingModule){
                $guid = uniqid('',true);
                DB::table('company_user_module')->insert(
                    ['company_user_module_guid' => $guid,
                        'company_id' => null,
                        'user_id' => $ceoId,
                        'module_id' => $module->module_id,
                        'cost' => 0,
                        'limit_count' => $module->limit_value,
                        'end_date' => null,
                        'is_active' => 1,
                        'created_at' => Carbon::now('Asia/Tehran'),
                        'updated_at' =>  Carbon::now('Asia/Tehran'),
                        'deleted_at' => null,]
                );
            }
        }

    }

    public function api_register_default_free_moduals_for_company($company_id){
        $moduls= DB::table('module')
            ->where('deleted_at',null)
            ->get();

        foreach ($moduls as $module) {
            if ($module->module_id == App\Repositories\ModuleRepository::attendanceModule) {
                $guid = uniqid('', true);
                DB::table('company_user_module')->insert(
                    ['company_user_module_guid' => $guid,
                        'company_id' => $company_id,
                        'user_id' => null,
                        'module_id' => $module->module_id,
                        'cost' => 0,
                        'limit_count' => $module->limit_value,
                        'end_date' => Carbon::now('Asia/Tehran')->addMonths(1),
                        'is_active' => 1,
                        'created_at' => Carbon::now('Asia/Tehran'),
                        'updated_at' => Carbon::now('Asia/Tehran'),
                        'deleted_at' => null,]
                );
            }
            else if ($module->module_id == App\Repositories\ModuleRepository::missionModule) {
                $guid = uniqid('', true);
                DB::table('company_user_module')->insert(
                    ['company_user_module_guid' => $guid,
                        'company_id' => $company_id,
                        'user_id' => null,
                        'module_id' => $module->module_id,
                        'cost' => 0,
                        'limit_count' => $module->limit_value,
                        'end_date' => Carbon::now('Asia/Tehran')->addMonths(1),
                        'is_active' => 1,
                        'created_at' => Carbon::now('Asia/Tehran'),
                        'updated_at' => Carbon::now('Asia/Tehran'),
                        'deleted_at' => null,]
                );
            }
        }
    }

}