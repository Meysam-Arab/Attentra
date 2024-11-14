<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 3/12/2017
 * Time: 2:27 PM
 */
namespace App\Http\Controllers;


use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserTypeRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\UserCompanyRepository;

use App\RequestResponseAPI;
use App\Company;
use App\UserCompany;
use Illuminate\Http\Request;
use Input;
use Validator;
use Redirect;
use Session;
use Auth;
use DB;
use Log;
use File;
use JWTAuth;
use App\Repositories\LogEventRepository;
use Route;

class API_CompanyController extends Controller
{
    protected $CompanyRepository;

    public function __construct(CompanyRepository $compony)
    {
        $this->CompanyRepository = $compony;
    }

    public function apiIndex(Request $request)
    {
        /////////////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////

        try {
            //validation
            $companyTemp = new CompanyRepository(new Company());
            if ($request->has('name')) {
                $companyTemp->set_name($request->input('name'));
            }


//        select company data with
            $paramsObj1 = array(
                 array("se", "company", "company_id"),
                 array("se", "company", "company_guid"),
                 array("se", "company", "name"),
                array("se", "company", "time_zone"),
                array("se", "company", "created_at")
            );

            //join
            $paramsObj2 = array(
                array("join",
                    "user_company",
                    array("company.company_id", "=", "user_company.company_id")
                ),
                array("join",
                    "user",
                    array("user_company.user_id", "=", "user.user_id")
                )
            );
            //conditions
            if ($user->user_type_id == UserTypeRepository::CEO || $user->user_type_id == UserTypeRepository::MiddleCEO) {
                if ($companyTemp->get_name() != null) {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user.user_id='" . $user->user_id . "'"
                        ),
                        array("whereRaw",
                            "company.name like '%" . $companyTemp->get_name() . "%'"
                        )
                    );
                } else {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user.user_id='" . $user->user_id . "'"
                        )
                    );
                }

            } elseif ($user->user_type_id == UserTypeRepository::Admin) {
                $paramsObj3 = null;
            }

            /////add deleted at condition to query/////////

            $paramsObj3[] =   array("whereRaw",
                "company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user_company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user.deleted_at is null"
            );

            /// ///////////////////////////////////////
            $this->CompanyRepository->initialize();

            $companies = $this->CompanyRepository->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);


            $destinationPath = storage_path() . '/app/company';
            $allFiles = scandir($destinationPath);

            foreach ($companies as $company) {
                $filename = $company->company_guid;

                $file_length = strlen($filename);
                foreach ($allFiles as $key => $value) {
                    if (substr($value, 0, $file_length) == $filename) {
                        $contents = File::get($destinationPath . '/' . $value);
                        $company->image = base64_encode($contents);
                        break;
                    }
                }
            }
            return json_encode(['token' => $token, 'error' => 0, 'companies' => $companies, 'tag' => RequestResponseAPI::TAG_INDEX_COMPANY]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_INDEX_COMPANY]);

        }

    }

    public function apiStore(Request $request)
    {
        ////////////////////get user from token////////////////
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);
        //////////////////////////////////////////////////////////

        try {

            //module validation here
            list($status,$purchasedCount, $storedCount) = ModuleRepository::api_CheckModuleValidation($user->user_id,null,ModuleRepository::newCompanyModule);
            if($status == ModuleRepository::StatusEnd)
            {
                return json_encode(['error'=>RequestResponseAPI::ERROR_MODULE_EXPIRE_CODE, 'tag'=>RequestResponseAPI::TAG_STORE_COMPANY]);

            }
            /////////////////////////

            //validation
            if (!$request->has('name')) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_COMPANY]);

            }
            if (strlen($request->input('name')) > 255)
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_STORE_COMPANY]);

            $companyTemp = new CompanyRepository(new Company());
            $companyTemp->set_name($request->input('name'));
            $companies = $companyTemp->select();
            if (count($companies) > 0) {
                return json_encode(['error' => RequestResponseAPI::ERROR_COMPANY_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_STORE_COMPANY]);
            }

            DB::beginTransaction();

            $this->CompanyRepository->initializeByRequest($request);
            $company_guid = $this->CompanyRepository->store();

            //get company id from db
            $CompanyRow = DB::table('company')
                ->where('company_guid', $company_guid)
                ->where('deleted_at',null)
                ->get();
            //fill one user company row
            $userCompany = new UserCompanyRepository(null);
            $userCompany->set_user_and_company_id($user->user_id, $CompanyRow[0]->company_id);
            if ($user->user_type_id == UserTypeRepository::CEO)
            {
                $userCompany->set_self_roll_call(true);
            }
            $userCompany->store();

            app('App\Http\Controllers\API_CompanyUserModuleController')->api_register_default_free_moduals_for_company($CompanyRow[0]->company_id);


            $file = null;
            if ($request->input('fileLogo') != null) {

//                Log::info('pic='.json_encode($request->input('fileLogo')));
                $file = $request->file('fileLogo');
                $size = $file->getClientSize();
                if ($size > 200000) {
//                    Log::info('size excees');

                    return json_encode(['error' => RequestResponseAPI::ERROR_INVALID_FILE_SIZE_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);
                }
                //decode base64 string
                $file = base64_decode($request->input('fileLogo'));
//                Log::info('after decode:'.json_encode($file));
//                if (File::size($file) > 200000) {
//                    Log::info('size excees');
//
//                    return json_encode(['error' => RequestResponseAPI::ERROR_INVALID_FILE_SIZE_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);
//                }
                $allowed = array('gif', 'png', 'jpg', 'jpeg', 'bmp', 'svg');
//                $ext = $file->getClientOriginalExtension();

                $f = finfo_open();

                $mime = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
                $split = explode( '/', $mime );
                $ext = $split[1];

//                Log::info('$ext='.json_encode($ext));
//                Log::info('$allowed='.json_encode($allowed));

                if (!in_array($ext, $allowed)) {
                    return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);
                }
            }
            if ($file != null) {
                $this->CompanyRepository->API_UpdateLogoOfCompany($this->CompanyRepository->get_guid(), $file);
            }
            // If we reach here, then
            // data is valid and working.
            // Commit the queries!
            DB::commit();
            return json_encode(['token' => $token,'error' => 0, 'tag' => RequestResponseAPI::TAG_STORE_COMPANY]);
        } catch (\Exception $ex) {
            DB::rollback();
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_COMPANY_STORE_CODE, 'tag' => RequestResponseAPI::TAG_STORE_COMPANY]);
        }

    }

    public function apiUpdate(Request $request)
    {
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::parseToken()->authenticate($token);

        try {

            //validation
            if (!$request->has('name') ||
                !$request->has('time_zone') ||
                !$request->has('company_id') ||
                !$request->has('company_guid')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);

            }
            if (strlen($request->input('name')) > 255)
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);

            $file = null;
            if ($request->input('fileLogo') != null) {

                $size = (int) (strlen(rtrim($request->input('fileLogo'), '=')) * 3 / 4);
                if ($size > 200000) {
                    return json_encode(['error' => RequestResponseAPI::ERROR_INVALID_FILE_SIZE_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);
                }

                //decode base64 string
                $file = base64_decode($request->input('fileLogo'));
//                if (File::size($file) > 200000) {
//                    return json_encode(['error' => RequestResponseAPI::ERROR_INVALID_FILE_SIZE_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);
//                }
                $allowed = array('gif', 'png', 'jpg', 'jpeg', 'bmp', 'svg');
//                $ext = $file->getClientOriginalExtension();


                $f = finfo_open();

                $mime = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
                $split = explode( '/', $mime );
                $ext = $split[1];

                if (!in_array($ext, $allowed)) {
                    return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);
                }
            }

            $this->CompanyRepository->Update($request);
            if ($file != null) {
                $this->CompanyRepository->API_UpdateLogoOfCompany($this->CompanyRepository->get_guid(), $file);
            }


            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_COMPANY_STORE_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_COMPANY]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  int $id
     * @return Response
     */
    public function apiDestroy(Request $request)
    {

        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::authenticate($token);

        try {

            if (!$request->has('company_id') ||
                !$request->has('company_guid')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_COMPANY]);

            }
            $company_id = $request->input('company_id');
            $company_guid = $request->input('company_guid');

            //check if user is owner...
            $users = CompanyRepository::getCeoOfThisCompany($company_id,$company_guid);
            if(count($users) > 0)
            {
                if($user->user_id != $users[0]->user_id)
                {
                    return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_COMPANY]);

                }
            }
            else
            {
                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_COMPANY]);

            }
            //////////////////////////////////

                $this->CompanyRepository->deleteLogo($company_guid);

                //DELETE USER'S AVATAR OF THIS COMPANY
                $users = DB::table('user_company')
                    ->join('user', 'user_company.user_id', '=', 'user.user_id')
                    ->where('user_company.company_id', '=', $company_id)
                    ->where('user.deleted_at',null)
                    ->where('user_company.deleted_at',null)
                    ->select('user.*')
                    ->get();
                foreach ($users as $user)
                    if ($user->user_type_id != UserTypeRepository::CEO)
                    {
                        app('App\Repositories\UserRepository')->deleteAvatar($user->user_guid);
                        app('App\Repositories\UserRepository')->deleteByIdAndGuid($user->user_id, $user->user_guid);

                    }

                $this->CompanyRepository->initialize(null);
                $this->CompanyRepository->set($company_id, $company_guid);
                $this->CompanyRepository->delete();



            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_DELETE_COMPANY]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

return json_encode(['error' => RequestResponseAPI::ERROR_DELETE_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_COMPANY]);
        }
    }

    public function apiChangeSelfRollCall(Request $request)
    {
//        Log::info('req:'.json_encode($request->all()));
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::authenticate($token);
        //////////////////////////////////////////////////////
        if (!$request->has('tag') ||
            !$request->has('selfRollCall') ||
            !$request->has('user_id')
        ) {
            return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY]);

        }
        try {
            $company = UserRepository::getCompany($request->input('user_id'),null);
            // meysam - check if company zone exist
            if(is_null($company[0]->zone))
                return json_encode(['error' => RequestResponseAPI::ERROR_COMPANY_ZONE_NOT_DEFINED_CODE, 'tag' => RequestResponseAPI::TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY]);
            ////////////////////////////////////////////

            if($request->input('selfRollCall') == "0")
            {

                UserCompany::where('user_id', $request->input('user_id'))
                    ->update(['self_roll_call' => 0]);
            }
            else
            {
                UserCompany::where('user_id', $request->input('user_id'))
                    ->update(['self_roll_call' => 1]);
            }

            return json_encode(['token' => $token,'self_roll_call' => $request->input('selfRollCall'),  'error' => 0, 'tag' => RequestResponseAPI::TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY]);


        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_UPDATE_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_CHANGE_SELF_ROLL_CALL_USER_COMPANY]);

        }

    }
}
