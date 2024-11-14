<?php
//Meysam Arab - 13950829

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserTypeRepository;
use App\RequestResponseAPI;
use App\UserCompany;
use App\Utility;
use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use App\User;
use File;
use Redirect;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\LogEventRepository;
use Route;
use App\Repositories\ModuleRepository;


class API_UserController extends Controller
{

    protected $userRepo;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->userRepo = $user;
    }

    /**
     * Store a new user.
     *
     * @param  Request $request
     * @return Response
     */
    public function apiRegister(Request $request)
    {
        try {
            // Validate the request...
            $request->request->remove('balance');
            if (!$request->has('user_name') || !$request->has('password')||
                !$request->has('country_id')||!$request->has('email')||
                !$request->has('phone_code')) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);

            }
            if (preg_match('/[^A-Za-z0-9]/', $request->input('user_name'))) // '/[^a-z\d]/i' should also work.
            {
                // string contains not only english letters & digits
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);

            }
            if (strlen($request->input('user_name')) < 3 || strlen($request->input('user_name')) > 15)
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);
            if (strlen($request->input('password')) < 5)
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);
            if (UserRepository::existByUserName($request->input('user_name'),false,null)) {
                return json_encode(['error' => RequestResponseAPI::ERROR_USER_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);
            }
//            $userTemp = new UserRepository(new User());
//            $userTemp->setUserName($request->input('user_name'));
//            $users = $userTemp->select();
//            if (count($users) > 0) {
//                return json_encode(['error' => RequestResponseAPI::ERROR_USER_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);
//            }
            if (UserRepository::existByEmail($request->input('email'),null,null)) {
                return json_encode(['error' => RequestResponseAPI::ERROR_EMAIL_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);
            }
            $pass = $request['password'];
            $request['password'] = bcrypt($request['password']);

            $this->userRepo->initializeByRequest($request);
            $this->userRepo->api_Store();
            // Via the global helper...


            $user = $this->userRepo->get_user();

            app('App\Http\Controllers\API_CompanyUserModuleController')->api_register_default_free_moduals($user->user_id);
            Utility::sendMail($user->email, $pass);
            return json_encode(['error' => 0, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository(-1, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);

        }
    }

    public function apiIndex(Request $request)
    {
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::authenticate($token);

        try {
            $users = $this->userRepo->select(new User());

            return json_encode(['token' => $token, 'error' => 0, 'users' => $users, 'tag' => "user_list"]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => "user_list"]);

        }
    }

    public
    function apiStoreAddMembers(Request $request)
    {
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::authenticate($token);

        // Specifying a default value...
        $value = session::pull('done', 'false');
        if($value == 'true')
           return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);



        try {

            if (!$request->has('name') ||
                !$request->has('family') ||
                !$request->has('user_name') ||
                !$request->has('email') ||
                !$request->has('password') ||
                !$request->has('company_id') ||
                !$request->has('gender') ||
                !$request->has('user_type_id')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);

            }

            if (preg_match('/[^A-Za-z0-9]/', $request->input('user_name'))) // '/[^a-z\d]/i' should also work.
            {
                // string contains not only english letters & digits
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);

            }
            if (strlen($request->input('user_name')) < 3 || strlen($request->input('user_name')) > 15)
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);
            if (strlen($request->input('password')) < 5)
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);

            if (UserRepository::existByUserName($request->input('user_name'),false,null)) {
                return json_encode(['error' => RequestResponseAPI::ERROR_USER_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);
            }
////            //check if user is owner...is it necessary???
//            $users = CompanyRepository::getCeoOfThisCompanyById($request->input('company_id'));
//            if(count($users) > 0)
//            {
//                if($user->user_id != $users[0]->user_id)
//                {
//                    return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);
//
//                }
//            }
//            else
//            {
//                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);
//
//            }
////
////            //////////////////////////////////
            //module validation here
            list($status,$purchasedCount, $storedCount) = ModuleRepository::api_CheckModuleValidation($user->user_id,null,ModuleRepository::newEmployeeModule);
            if($status == ModuleRepository::StatusEnd)
            {
                return json_encode(['error'=>RequestResponseAPI::ERROR_MODULE_EXPIRE_CODE, 'tag'=>RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);

            }
            /////////////////////////
            $file = null;
            if ($request->input('fileLogo') != null) {
                //decode base64 string
                $file = $request->file('fileLogo');
                $size = $file->getClientSize();
                if ($size > 200000) {
                    return json_encode(['error' => RequestResponseAPI::ERROR_INVALID_FILE_SIZE_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);
                }
                $file = base64_decode($request->input('fileLogo'));

                $allowed = array('gif', 'png', 'jpg', 'jpeg', 'bmp', 'svg');
//                $ext = $file->getClientOriginalExtension();

                $f = finfo_open();

                $mime = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
                $split = explode( '/', $mime );
                $ext = $split[1];

//                Log::info('$ext='.json_encode($ext));
//                Log::info('$allowed='.json_encode($allowed));

                if (!in_array($ext, $allowed)) {
                    return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);
                }
            }



            $userTemp = new UserRepository(new User());
            $userTemp->setUserName($request->input('user_name'));
            $users = $userTemp->select();
            if (count($users) > 0) {
                return json_encode(['error' => RequestResponseAPI::ERROR_USER_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);
            }
            if (UserRepository::existByEmail($request->input('email'),null,null)) {
                return json_encode(['error' => RequestResponseAPI::ERROR_EMAIL_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_REGISTER_USER]);
            }

            $pass = $request['password'];
            $request['password'] = bcrypt($request['password']);
            $this->userRepo->initializeByRequest($request);
            $this->userRepo->API_storeForeEmployment($request);

            //get user id from db
            $UserRow = DB::table('user')
                ->where('user_name', $request['user_name'])
                ->where('deleted_at',null)
                ->get();

            if ($file != null ) {
//                $fileName = $UserRow[0]->user_guid . '.' . $file->guessClientExtension();
//                $destinationPath = storage_path() . '/app/avatars';
//                $file->move($destinationPath, $fileName);

                $this->userRepo->API_UpdateAvatarOfUser($UserRow[0]->user_guid, $file);

            }


            //fill one usercompany row
            $UserCompanyRow = new UserCompany();
            $UserCompanyRow->user_company_guid = uniqid('', true);
            $UserCompanyRow->user_id = $UserRow[0]->user_id;
            $UserCompanyRow->company_id = $request['company_id'];
            $UserCompanyRow->save();


            // Store a piece of data in the session...
            Session(['done' => 'true']);
            try{
                Utility::sendMail($request->input('email'), $pass);

            } catch (\Exception $ex) {
                $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
                $logEvent->store();

            }

            Session::forget('done');
            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_ADD_MEMBER_COMPANY]);

        }
    }

    public function apiListMembers(Request $request)
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
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_COMPANY]);

            }
            $company_id = $request->input('company_id');
            $company_guid = $request->input('company_guid');
            //check if user is owner... problem for user being middleceo
//            $users = CompanyRepository::getCeoOfThisCompany($company_id,$company_guid);
//            if(count($users) > 0)
//            {
//                if($user->user_id != $users[0]->user_id)
//                {
//                    return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_COMPANY]);
//
//                }
//            }
//            else
//            {
//                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_COMPANY]);
//
//            }

            //////////////////////////////////

            $users = $this->userRepo->GetListUsersOfCompany($company_id, $company_guid);
            $temp = array();
            foreach ($users as $usertmp)
            {
                if($usertmp->user_type_id != UserTypeRepository::CEO)
                {
                    if($user->user_type_id != $usertmp->user_type_id)
                    {
                        array_push($temp,$usertmp);
                    }

                }
            }
            $users = $temp;

            $destinationPath = storage_path() . '/app/avatars';
            $allFiles = scandir($destinationPath);
            foreach ($users as $usertmp) {

                $filename = $usertmp->user_guid;

                $file_length = strlen($filename);
                foreach ($allFiles as $key => $value) {
                    if (substr($value, 0, $file_length) == $filename) {
                        $contents = File::get($destinationPath . '/' . $value);
                        $usertmp->image = base64_encode($contents);
                        break;
                    }
                }
            }

            return json_encode(['token' => $token, 'error' => 0, 'users' => $users, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_COMPANY]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_OPERATION_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_LIST_MEMBERS_COMPANY]);

        }
    }


    /**
     * Update the specified user.
     *
     * @param
     * @return Response
     */
    public
    function apiUpdate(Request $request)
    {
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::authenticate($token);
//        $request = $request->only('name','gender', 'tag','family','email','user_id','user_guid',
//            'country_id');
        $request->request->remove('balance');

        try {
            if (!$request->has('name') ||
                !$request->has('gender') ||
                !$request->has('tag') ||
                !$request->has('family') ||
                !$request->has('email') ||
                !$request->has('user_id') ||
                !$request->has('user_guid')||
                !$request->has('country_id')
//                !$request->has('password')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_USER]);

            }
            if ($request->has('password'))
            {
                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_USER]);

            }
//            if ($user->user_type_id != UserTypeRepository::CEO || $user->user_type_id != UserTypeRepository::Admin) {
//                if (!$request->has('code')) {
//                    return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_USER]);
//
//                }
//            }

            $oldUser = new UserRepository(new User());
            $oldUser = $oldUser->findByIdAndGuid($request->input('user_id'), $request->input('user_guid'));

            if($oldUser->email != $request->input('email'))
            {
                if (UserRepository::existByEmail($request->input('email'),true,$request->input('user_id'))) {
                    return json_encode(['error' => RequestResponseAPI::ERROR_EMAIL_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_USER]);
                }
            }

            $file = null;
            if ($request->input('fileLogo') != null) {
                $size = (int) (strlen(rtrim($request->input('fileLogo'), '=')) * 3 / 4);
                if ($size > 200000) {
                    return json_encode(['error' => RequestResponseAPI::ERROR_INVALID_FILE_SIZE_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_USER]);
                }
                //decode base64 string
                $file = base64_decode($request->input('fileLogo'));
                $allowed = array('gif', 'png', 'jpg', 'jpeg', 'bmp', 'svg');
                $f = finfo_open();
                $mime = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
                $split = explode( '/', $mime );
                $ext = $split[1];

                if (!in_array($ext, $allowed)) {
                    return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_USER]);
                }
            }


            $this->userRepo->apiUpdate($request);
            if ($file != null) {
                $this->userRepo->API_UpdateAvatarOfUser($this->userRepo->get_guid(), $file);
            }

            $user = $this->userRepo->get_user();

            return json_encode(['token' => $token, 'error' => 0, 'user' => $user, 'tag' => RequestResponseAPI::TAG_EDIT_USER]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_UPDATE_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_EDIT_USER]);

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
            if (!$request->has('user_id') ||
                !$request->has('user_guid')
            ) {
                return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_USER]);

            }
            $user_id = $request->input('user_id');
            $user_guid = $request->input('user_guid');
            if (!$this->userRepo->exist($user_id, $user_guid)) {
                return json_encode(['error' => RequestResponseAPI::ERROR_USER_EXIST_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_USER]);
            }

            if($user->user_id == $user_id)
            {
                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_USER]);

            }

            //problem in user being middleceo
//            $ceoUser = UserRepository::API_GetManager($user_id);
//            if($ceoUser->user_id != $user->user_id)
//            {
//                return json_encode(['error' => RequestResponseAPI::ERROR_UNAUTHURIZED_USER_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_USER]);
//
//            }

            $this->userRepo->set($user_id, $user_guid);
            $this->userRepo->delete();

            $this->userRepo->deleteAvatar($user_guid);
            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_DELETE_USER]);
        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_UPDATE_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_DELETE_USER]);

        }
    }

    public function apiRemovePhoneCode(Request $request)
    {
        $token = null;
        if (session('tokenRefreshed'))
            $token = session('token');
        else
            $token = JWTAuth::parseToken()->getToken()->get();
        $user = JWTAuth::authenticate($token);
        //////////////////////////////////////////////////////
        if (!$request->has('tag') ||
        !$request->has('user_id') ||
        !$request->has('user_guid')
            ) {
        return json_encode(['error' => RequestResponseAPI::ERROR_DEFECTIVE_INFORMATION_CODE, 'tag' => RequestResponseAPI::TAG_REMOVE_PHONE_CODE_USER]);

    }
        try {

            UserRepository::removePhoneCode($request->input('user_id'),$request->input('user_guid'));
            return json_encode(['token' => $token, 'error' => 0, 'tag' => RequestResponseAPI::TAG_REMOVE_PHONE_CODE_USER]);


        } catch (\Exception $ex) {
            $logEvent = new LogEventRepository($user->user_id, Route::getCurrentRoute()->getActionName(), "Main Message: " . $ex->getMessage() . " Stack Trace: " . $ex->getTraceAsString());
            $logEvent->store();

            return json_encode(['error' => RequestResponseAPI::ERROR_UPDATE_FAIL_CODE, 'tag' => RequestResponseAPI::TAG_REMOVE_PHONE_CODE_USER]);

            }

    }
}