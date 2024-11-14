<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Morilog\Jalali\jDateTime;
use Log;
use Illuminate\Support\Facades\Input;
use Validator;
use Morilog\Jalali\Facades\jDate;
use DB;
use Auth;
use App\Repositories\UserTypeRepository;
use App\Repositories\CompanyUserModuleRepository;
use App\OperationMessage;

use Exception;
use App\Repositories\LogEventRepository;
use Route;

class CompanyUserModuleController extends Controller
{
    protected  $CompanyUserModule;

    public function __construct(CompanyUserModuleRepository $CompanyUserModule)
    {
        $this->CompanyUserModule = $CompanyUserModule;
    }

    public function initialize()
    {
        $this->CompanyUserModule->company_module_id=null;
        $this->CompanyUserModule->company_module_guid=null;
        $this->CompanyUserModule->company_id=null;
        $this->CompanyUserModule->module_id=null;
        $this->CompanyUserModule->cost=null;
        $this->CompanyUserModule->end_date=null;
        $this->CompanyUserModule->is_active=null;
        $this->CompanyUserModule->created_at=null;
        $this->CompanyUserModule->updated_at=null;
        $this->CompanyUserModule->deleted_at=null;
    }

    public function initializeByRequest($request)
    {
        $this->CompanyUserModule->company_module_id=$request->input('company_module_id');
        $this->CompanyUserModule->company_module_guid=$request->input('company_module_guid');
        $this->CompanyUserModule->company_id=$request->input('company_id');
        $this->CompanyUserModule->module_id=$request->input('module_id');
        $this->CompanyUserModule->cost=$request->input('cost');
        $this->CompanyUserModule->end_date=$request->input('end_date');
        $this->CompanyUserModule->is_active=$request->input('is_active');
        $this->CompanyUserModule->created_at=$request->input('created_at');
        $this->CompanyUserModule->updated_at=$request->input('updated_at');
        $this->CompanyUserModule->deleted_at=$request->input('deleted_at');
    }

    public function isVisitedAttendance($company_id)
    {
        try
        {
            $updaterow=DB::table('company_user_module')
                ->where('company_id', $company_id)
                ->where('deleted_at',null)
                ->value('end_date');
            $created = new Carbon($updaterow);
            $now = Carbon::now();
            return $created->getTimestamp()-$now->getTimestamp();

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

    public function activeModule(Request $request){

        try
        {
            $rules = [
                'time' => 'required',
                'module_id' => 'required',
                'module_guid' => 'required',
                'company_id' => 'required',
                'company_guid' => 'required'
            ];
            $v = Validator::make($request->all(), $rules);
            if ($v->fails()) {

                return redirect()->back()->withErrors($v->errors());

            }
            else
            {
                if(Auth::user()->user_type_id==UserTypeRepository::MiddleCEO)
                {
                    //if(get_Admin_User_Id())
                }
                if($this->checkPurcheseModule($request)){
                    $message = new OperationMessage();
                    $message->initializeByCode(OperationMessage::OperationSuccessCode);
                }else{
                    $message = new OperationMessage();
                    $message->initializeByCode(OperationMessage::NotEnouphMoney);
                }
                if($request['company_id']=="null")
                {
                    $YourCompany = DB::table('company')
                        ->join('user_company', 'user_company.company_id', '=', 'company.company_id')
                        ->select('company.*')
                        ->where('user_company.user_id','=', Auth::user()->user_id)
                        ->where('user_company.deleted_at',null)
                        ->where('company.deleted_at',null)
                        ->first();


                    return Redirect::to('/module/publicindex/'.$YourCompany->company_id.'/'.$YourCompany->company_guid)->with('message', $message);
                }
                else
                    return Redirect::to('/module/index/'.$request['company_id'].'/'.$request['company_guid'])->with('message', $message);


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

    public function register_default_free_moduals($user_id)
    {
        try
        {
            $moduls= DB::table('module')
                ->where('deleted_at',null)
                ->get();
            foreach ($moduls as $module){
                if($module->module_id==3){
                    $guid = uniqid('',true);
                    DB::table('company_user_module')->insert(
                        ['company_user_module_guid' => $guid,
                            'company_id' => null,
                            'user_id' => $user_id,
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
                else if($module->module_id==4){
                    $guid = uniqid('',true);
                    DB::table('company_user_module')->insert(
                        ['company_user_module_guid' => $guid,
                            'company_id' => null,
                            'user_id' => $user_id,
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
                else if($module->module_id==5){
                    $guid = uniqid('',true);
                    DB::table('company_user_module')->insert(
                        ['company_user_module_guid' => $guid,
                            'company_id' => null,
                            'user_id' => $user_id,
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
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }


    }

    public function register_default_free_moduals_for_company($company_id){
        try
        {
            $moduls= DB::table('module')
                ->where('deleted_at',null)
                ->get();

            foreach ($moduls as $module) {
                if ($module->module_id == 1) {
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
                else if ($module->module_id == 2) {
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
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }

    }

    public function delete_default_free_moduals_for_company($company_id){
        try
        {
            App\CompanyUserModule::where('company_id', $company_id)->delete();

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

    public function purchasesList()
    {
        try
        {
            $company_idss = DB::table('user_company')
                ->where('user_id', Auth::user()->user_id)
                ->where('deleted_at',null)
                ->pluck('company_id');


            $company_user_module = DB::table('company_user_module')
                ->whereIn('company_id',$company_idss)
                ->orwhere('user_id',Auth::user()->user_id)
                ->where('deleted_at',null)
                ->get();

            if (App::isLocale('en')) {
                $modules = DB::table('trans_module')
                    ->where('language_id','=','2')
                    ->where('deleted_at',null)
                    ->get();
            }
            elseif (App::isLocale('pr')){
                $modules = DB::table('trans_module')
                    ->where('language_id','=','1')
                    ->where('deleted_at',null)
                    ->get();
            }
            return view('module/purchases',['company_user_module'=>$company_user_module,'modules'=>$modules]);
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

    public function checkPurcheseModule(Request $request){
        try
        {
            $module = DB::table('module')
                ->where('module_id','=',$request['module_id'])
                ->where('deleted_at',null)
                ->first();
            $price=$module->price * $request['time'];
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }

        if(Auth::user()->balance >= $price)
        {
            try
            {
                DB::beginTransaction();
                DB::table('user')
                    ->where('user_id', Auth::user()->user_id)
                    ->where('deleted_at',null)
                    ->update(['balance' => Auth::user()->balance - $price]);
                $this->CompanyUserModule->initializeByRequest($request);
                $this->CompanyUserModule->store();
                DB::commit();
                return true;

            }
            catch (Exception $e)
            {
                DB::rollBack();
                $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
                $logEvent->store();
                $message = new OperationMessage();
                $message->initializeByCode(OperationMessage::OperationErrorCode);
                return redirect()->back()->with('message', $message);
            }

        }
        else{
            return false;
        }
    }


}
