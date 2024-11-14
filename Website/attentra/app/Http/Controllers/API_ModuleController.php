<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/13/2017
 * Time: 10:34 AM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RequestResponseAPI;
use App;
use App\Repositories\ModuleRepository;
use Log;
use Validator;
use DB;
use JWTAuth;


class API_ModuleController extends Controller
{
    protected $moduleRepository;

    public function __construct(ModuleRepository $module)
    {
        $this->moduleRepository = $module;
    }

    public function apiUserModuleIndex(Request $request)
    {
        /////////////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////

        //validation
        if (!$request->has('tag' )||
            !$request->has('language_code')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_USER_MODULE]);

        }
        /////////

        $request['language_id'] = App\Repositories\LanguageRepository::getIdByCode($request->input('language_code'));

        try
        {

            $ceoRelatedRecords = DB::select( DB::raw('select module.module_id, module.limit_value, module.price, trans_module.title, trans_module.description
              from module JOIN trans_module on module.module_id = trans_module.module_id and module.module_id in (3,4,5) and trans_module.language_id = :mlanguage_id and module.is_active = 1 and  module.deleted_at is null and trans_module.deleted_at is null ORDER BY module.module_id DESC'), array(
                'mlanguage_id' => $request->input('language_id'),
            ));


            //get company_module
            foreach ($ceoRelatedRecords as $module) {

//                Log::info('module_id:'.json_encode($module->module_id));
                list($status,$purchased,$remained)  = ModuleRepository::api_CheckModuleValidation($user->user_id, null, $module->module_id);
//                Log::info('$status:'.json_encode($status));
                //module validation here
                $module->status = $status;
                $module->purchased = $purchased;
                $module->stored = $remained;
                /////////////////////////

            }

            return json_encode(['token' => $token, 'modules' => $ceoRelatedRecords,'error' => 0, 'tag' => RequestResponseAPI::TAG_INDEX_USER_MODULE]);

        }
        catch (Exception $ex) {

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_USER_MODULE]);

        }

    }

    public function apiCompanyModuleIndex(Request $request)
    {
        /////////////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////

        //validation
        if (!$request->has('tag' )||
            !$request->has('company_id') ||
            !$request->has('company_guid') ||
            !$request->has('language_code')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_COMPANY_MODULE]);

        }

        $request['language_id'] = App\Repositories\LanguageRepository::getIdByCode($request->input('language_code'));

        /////////
        $company_rep = new App\Repositories\CompanyRepository(new App\Company());
        if(!$company_rep->exist($request->input('company_id'),$request->input('company_guid')))
        {
            return json_encode(['error' => RequestResponseAPI::ERROR_ITEM_NOT_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_COMPANY_MODULE]);

        }


        try
        {

            $ceoRelatedRecords = DB::select( DB::raw('select module.module_id, module.limit_value, module.price, trans_module.title, trans_module.description
              from module JOIN trans_module on module.module_id = trans_module.module_id and module.module_id in (1,2) and trans_module.language_id = :mlanguage_id and module.is_active = 1 and  module.deleted_at is null and trans_module.deleted_at is null ORDER BY module.module_id DESC'), array(
                'mlanguage_id' => $request->input('language_id'),
            ));


            //get company_module
            foreach ($ceoRelatedRecords as $module) {
                list($status,$purchased,$remained)  = ModuleRepository::api_CheckModuleValidation(null, $request->input('company_id'), $module->module_id);

                //module validation here
                $module->status = $status;
                $module->purchased = $purchased;
                $module->stored = $remained;
                /////////////////////////

            }
            return json_encode(['token' => $token, 'modules' => $ceoRelatedRecords,'error' => 0, 'tag' => RequestResponseAPI::TAG_INDEX_COMPANY_MODULE]);

        }
        catch (Exception $ex) {

            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_COMPANY_MODULE]);

        }

    }



}