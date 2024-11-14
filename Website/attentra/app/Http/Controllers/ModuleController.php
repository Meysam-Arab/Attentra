<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Language;
use App\TransModule;
use App\OperationMessage;
use App\Repositories\ModuleRepository;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Morilog\Jalali\jDateTime;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use Illuminate\Support\Facades\Session;
use App\Repositories\UserRepository;
use App\Repositories\UserTypeRepository;
use Log;

use Exception;
use App\Repositories\LogEventRepository;
use Route;

class ModuleController extends Controller
{
    protected  $moduleRepository;

    public function __construct(ModuleRepository $module)
    {
        $this->moduleRepository = $module;
//        $this->middleware('guest');

    }

    public function create()
    {
        $langs = Language::all();

        return view('module.create', ['langs' => $langs]);
    }

    public function checkModule($modouleId,$company_id,$user_id, $destinationSuccessUrl,$toggleCreateOrStore,$ModuleCount,$company_id_For_destination_Error_Url)
    {
        try
        {

            $module = Db::table('module')->where('module_id', '=', $modouleId)->get();
            if ($module[0]->is_active != 1)
            {
                $message = new OperationMessage();
                $message->initializeByCode(OperationMessage::NotActiveThisModule);
                $count=null;
                for($index=0;$index< session('CompanyCount');$index++){
                    if(session('companiesId'.$index)==$company_id_For_destination_Error_Url){
                        $count=$index;
                        break;
                    }
                }
                return redirect('module/publicindex/'.session('companiesId'.$count).'/'.session('companiesGuid').$count)->with('message', $message);
            }
            $second='';
            $limit_count='';

            if(Auth::user()->user_type_id ==0){
                return true;
            }
            else if(Auth::user()->user_type_id !=1)
            {
                if($user_id==null)
                {
                    list($second, $limit_count) = app('App\Repositories\CompanyUserModuleRepository')->exist($modouleId, $company_id, null);
                }
                else{
                    $user=UserRepository::getManagerId(Auth::user()->user_id);
                    list($second, $limit_count) = app('App\Repositories\CompanyUserModuleRepository')->exist($modouleId, $company_id, $user[0]->user_id);
                }
            }
            else{
                if($user_id==null)
                {
                    list($second, $limit_count) = app('App\Repositories\CompanyUserModuleRepository')->exist($modouleId, $company_id, null);
                }
                else{
                    list($second, $limit_count) = app('App\Repositories\CompanyUserModuleRepository')->exist($modouleId, $company_id, Auth::user()->user_id);
                }
            }
            //this mean that CEO not Buying this module
            if ($second === null){
                if($ModuleCount < $module[0]->limit_value) {
                    if ($toggleCreateOrStore){
                        return view($destinationSuccessUrl);
                    }else
                        return true;


                }else {
                    $message = new OperationMessage();
                    $message->initializeByCode(OperationMessage::NotActiveThisModule);
                    $count=null;
                    for($index=0;$index< session('CompanyCount');$index++){
                        if(session('companiesId'.$index)==$company_id_For_destination_Error_Url){
                            $count=$index;
                            break;
                        }
                    }
                    return redirect('module/publicindex/'.session('companiesId'.$count).'/'.session('companiesGuid'.$count))->with('message', $message);
                }

            }
            //this mean that CEO Buying this module But this module is out of date
            if ($second < 0) {
                $module = Db::table('module')->where('module_id', '=', $modouleId)->get();
                if ($ModuleCount < $module[0]->limit_value) {
                    if ($toggleCreateOrStore)
                        return view($destinationSuccessUrl);
                    else
                        return true;
                } else {
                    $message = new OperationMessage();
                    $message->initializeByCode(OperationMessage::EndTimeActiveThisModule);
                    $count=null;
                    for($index=0;$index< session('CompanyCount');$index++){
                        if(session('companiesId'.$index)==$company_id_For_destination_Error_Url){
                            $count=$index;
                            break;
                        }
                    }
                    return redirect('module/publicindex/'.session('companiesId'.$count).'/'.session('companiesGuid'.$count))->with('message', $message);
                }
            }
            //this mean that CEO Buying this module and he/she can use this module if this limit value has true
            if ($second > 0) {
                if($ModuleCount < $limit_count){
                    if ($toggleCreateOrStore)
                        return view($destinationSuccessUrl);
                    else
                        return true;
                }else{
                    $message = new OperationMessage();
                    $message->initializeByCode(OperationMessage::NotActiveThisModule);
                    $count=null;
                    for($index=0;$index< session('CompanyCount');$index++){
                        if(session('companiesId'.$index)==$company_id_For_destination_Error_Url){
                            $count=$index;
                            break;
                        }
                    }
                    return redirect('module/publicindex/'.session('companiesId'.$count).'/'.session('companiesGuid').$count)->with('message', $message);
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

    public function index($company_id=null,$company_guid=null)
    {
        try
        {
            //        select company data with
            $paramsObj1 = array(
                array("st", "module"),
                array("st", "trans_module")
            );

            //join
            $paramsObj2 = array(
                array("join",
                    "trans_module",
                    array("trans_module.module_id", "=", "module.module_id")
                )
            );
            //conditions
            if(Auth::user()->user_type_id <2 && App::getLocale()=='pr'){
                $paramsObj3 = array(
                    array("whereRaw",
                        "trans_module.language_id='1'"
                    )

                );
            }
            elseif (Auth::user()->user_type_id <2 && App::getLocale()=='en'){
                $paramsObj3 = array(
                    array("whereRaw",
                        "trans_module.language_id='2'"
                    )

                );
            }
            else
            {
                $paramsObj3=null;
            }
            if(Auth::user()->user_type_id ==1){
                array_push($paramsObj3,array("whereRaw",
                    "module.is_active='1'"
                ));
                array_push($paramsObj3,array("whereRaw",
                    "module.limit_value='0'"
                ));
            }
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "module.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "trans_module.deleted_at is null"
            );

            /// ///////////////////////////////////////
            $this->moduleRepository->initialize();

            list($provider, $moduleRepositories) = $this->moduleRepository->getFullDetailModule($paramsObj1, $paramsObj2, $paramsObj3);

            //get company_module
            $company_modules=new App\CompanyUserModule();
            $company_modules->newQuery();
            if($company_id != null){

                $company_modules = DB::select("
            SELECT * FROM company_user_module
            WHERE deleted_at is null and company_user_module_id IN ( 
                            SELECT MAX(company_user_module_id)
                FROM (SELECT * FROM company_user_module WHERE deleted_at is null and company_id='".$company_id."') AS maxiano
                GROUP BY module_id
                )
      
            
            ");

            }




            $Company_name_index='';
            $count=null;
            for($index=0;$index< session('CompanyCount');$index++){
                if(session('companiesId'.$index)==$company_id){
                    $Company_name_index=$index;
                    break;
                }
            }
            //list($provider, $moduleRepository)=$this->moduleRepository->select();
            return view('module.index',['company_modules' => $company_modules, 'moduleRepositories' => $moduleRepositories])->with('Company_name_index', $Company_name_index);

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

    public function publicindex($company_id=null,$company_guid=null)
    {
        try
        {
            $manager = DB::table('user')
                ->join('user_company', 'user.user_id', '=', 'user_company.user_id')
                ->where('user_company.company_id','=',$company_id)
                ->where('user_company.deleted_at',null)
                ->where('user.user_type_id','=',UserTypeRepository::CEO)
                ->first();

            //$company_id=null,$company_guid=null
            //        select company data with
            $paramsObj1 = array(
                array("st", "module"),
                array("st", "trans_module")
            );

            //join
            $paramsObj2 = array(
                array("join",
                    "trans_module",
                    array("trans_module.module_id", "=", "module.module_id")
                )
            );
            //conditions
//        if(Auth::user()==null)
//            return 'asa';
            if(Auth::user()->user_type_id <2 && App::getLocale()=='pr'){
                $paramsObj3 = array(
                    array("whereRaw",
                        "trans_module.language_id='1'"
                    )

                );
            }
            elseif (Auth::user()->user_type_id <2 && App::getLocale()=='en'){
                $paramsObj3 = array(
                    array("whereRaw",
                        "trans_module.language_id='2'"
                    )

                );
            }
            else
            {
                $paramsObj3=null;
            }
            if(Auth::user()->user_type_id ==1){
                array_push($paramsObj3,array("whereRaw",
                    "module.is_active='1'"
                ));
                array_push($paramsObj3,array("whereRaw",
                    "module.limit_value!=''"
                ));
            }
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "module.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "trans_module.deleted_at is null"
            );

            /// ///////////////////////////////////////
            $this->moduleRepository->initialize();

            list($provider, $moduleRepositories) = $this->moduleRepository->getFullDetailModule($paramsObj1, $paramsObj2, $paramsObj3);

            //get company_module
            $company_modules=new App\CompanyUserModule();
            $query = $company_modules->newQuery();
            if($company_id != null){

                $company_modules = DB::select("
            SELECT * FROM company_user_module
            WHERE deleted_at is null and company_user_module_id IN ( 
                            SELECT MAX(company_user_module_id)
                FROM (SELECT * FROM company_user_module WHERE deleted_at is null and user_id='".$manager->user_id."') AS maxiano
                GROUP BY module_id
                )
      
            
            ");

            }

            //sum of all of module that user buy themselves
            $sums_of_module_purchase_count=$this->sums_of_module_purchase_count();

            //all of users of this CEO
            $users = $this->count_Of_users();

            //all of users of this CEO with deleted
            $users_with_delete = $this->count_Of_users_with_deleted();

            //all of point that registeret with this users
            $track = $this->count_Of_tracks($users_with_delete);


            $module_used_count=Array(count($users),session('CompanyCount'),count($track));
//

            //list($provider, $moduleRepository)=$this->moduleRepository->select();
            return view('module.publicModule',['company_modules' => $company_modules, 'moduleRepositories' => $moduleRepositories
                ,'module_used_count' => $module_used_count, 'sums_of_module_purchase_count' => $sums_of_module_purchase_count]);

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

    public function store(Request $request)
    {
        try
        {
            $langs = Language::all();

            $this->moduleRepository->initialize();
            $this->moduleRepository->initializeByRequest($request);
            $module_guid=$this->moduleRepository->store();

            $ThisModuleId=$this->moduleRepository->findBy('module_guid',$module_guid);
            foreach($langs as $lang)
            {
                $desc="Description".$lang->title;
                $title="title".$lang->title;

                $transModule=new TransModule();
                $transModule->trans_module_guid = uniqid('',true);
                $transModule->module_id=$ThisModuleId[0]->module_id;


                $transModule->language_id=$lang->language_id;
                $transModule->title=$request->input($title);
                $transModule->description=$request->input($desc);

                $transModule->save();

            }

            return redirect()->action(
                'ModuleController@index'
            );
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

    public function edit($module_id,$module_guid)
    {
        $paramsObj1 = array(
            array("st", "module"),
            array("st", "trans_module")
        );

        //join
        $paramsObj2 = array(
            array("join",
                "trans_module",
                array("trans_module.module_id", "=", "module.module_id")
            )
        );
        //conditions
        $paramsObj3 = array(
            array("whereRaw",
                "module.module_id='".$module_id."'"
            ),
            array("whereRaw",
                "module.module_guid='".$module_guid."'"
            )
        );
        /////add deleted at condition to query - meysam/////////

        $paramsObj3[] =   array("whereRaw",
            "module.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "trans_module.deleted_at is null"
        );

        /// ///////////////////////////////////////
        try
        {
            $this->moduleRepository->initialize();

            list($provider, $moduleRepository) = $this->moduleRepository->getFullDetailModule($paramsObj1, $paramsObj2, $paramsObj3);

            $langs = Language::all();
            return view('module.update',['moduleRepository' => $moduleRepository, 'langs' => $langs]);

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

    public function update(Request $request)
    {
        try
        {
            $langs = Language::all();

            $this->moduleRepository->initialize();
            $this->moduleRepository->initializeByRequest($request);
            $this->moduleRepository->update($request);

            $oldTranseModules=App\TransModule::where('module_id', $request['module_id'])->get();
            $counter=0;
            foreach($oldTranseModules as $oldTranseModule)
            {
                $desc="Description".$langs[$counter]->title;
                $title="title".$langs[$counter]->title;

//            $transModule=new TransModule();
//            $transModule->trans_module_guid = uniqid('',true);
//            $transModule->module_id=$ThisModuleId[0]->module_id;

//
//            $transModule->language_id=$lang->language_id;
                $oldTranseModule->title=$request->input($title);
                $oldTranseModule->description=$request->input($desc);

                $oldTranseModule->save();
                $counter++;
            }

            return redirect()->action(
                'ModuleController@index'
            );
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

    public function count_Of_users()
    {
        try
        {
            $company_idss = DB::table('user_company')
                ->where([
                    ['user_id', '=', Auth::user()->user_id],
                    ['deleted_at', null]
                ])
                ->pluck('company_id');
            $users = DB::table('user_company')
                ->whereIn('company_id',$company_idss)->distinct('user_id')
                ->where('deleted_at', null)
                ->pluck('user_id');
            return $users;
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

    public function count_Of_users_with_deleted()
    {
        try
        {
            $company_idss = DB::table('user_company')
                ->where([
                    ['user_id', '=', Auth::user()->user_id]
                ])
                ->pluck('company_id');
            $users = DB::table('user_company')
                ->whereIn('company_id',$company_idss)->distinct('user_id')
                ->pluck('user_id');
            return $users;
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

    public function count_Of_tracks($users)
    {
        try
        {
            return DB::table('track')
                ->whereIn('user_id',$users)
                ->get();

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

    public function sums_of_module_purchase_count()
    {
        try
        {
            return App\CompanyUserModule::groupBy('module_id')
                ->where('user_id',"=",Auth::user()->user_id)
                ->where('deleted_at', null)
                ->selectRaw('sum(limit_count) as sum,module_id')
                ->get();

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





}
