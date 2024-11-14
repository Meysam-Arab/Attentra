<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepository;
use App\Repositories\UserTypeRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\UserCompanyRepository;
use App\Repositories\UserRepository;
use App\User;
use App\Company;
use App\OperationMessage;
use Illuminate\Http\Request;

use App\Repositories\LogEventRepository;
use Exception;
use Route;
//use ViewComponents\Eloquent\EloquentDataProvider;
use Input;
use Validator;
use Redirect;
use Session;
use Auth;
use DB;
use Log;
use File;



class CompanyController extends Controller
{
    protected $CompanyRepository;

    public function __construct(CompanyRepository $compony)
    {
        $this->CompanyRepository = $compony;

    }

    public function index()
    {
//        select company data with
        $paramsObj1 = array(
            array("st", "company")
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
        if (Auth::user()->user_type_id == UserTypeRepository::CEO) {
            $paramsObj3 = array(
                array("whereRaw",
                    "user.user_id='" . Auth::user()->user_id . "'"
                )
            );
        } elseif (Auth::user()->user_type_id == UserTypeRepository::Admin) {
            $paramsObj3 = null;
        }
        /////add deleted at condition to query - meysam/////////

        $paramsObj3[] =   array("whereRaw",
            "user_company.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "user.deleted_at is null"
        );
        $paramsObj3[] =   array("whereRaw",
            "company.deleted_at is null"
        );


        /// ///////////////////////////////////////
        try
        {
            $this->CompanyRepository->initialize();

            $CompanyRepository = $this->CompanyRepository->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);

            return view('company/list', ['CompanyRepository' => $CompanyRepository]);

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

    public function getImage($filename)
    {
        try
        {
            $file = File::get(storage_path('app/company/' . $filename));
            return $file;

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

    public function getAvatar($user_guid)
    {
        try
        {


            $flag = false;
            $destinationPath = storage_path() . '/app/avatars';
            $files1 = scandir($destinationPath);
            $nameOfFile = "";
            $search = $user_guid;
            $search_length = strlen($search);
            foreach ($files1 as $key => $value) {
                if (substr($value, 0, $search_length) == $search) {
                    $nameOfFile = $value;
                    $flag = true;
                    break;
                }
            }
//            $cur_user = DB::select( DB::raw('select gender+0 from user where user_id= :mlanguage_id '), array(
//                'mlanguage_id' => Auth::user()->user_id,
//            ));
//            $cur_user=DB::table('user')->select('gender')->where('user_id', Auth::user()->user_id)->first();
            log::info("gender".json_encode(Auth::user()->gender));
            if (!$flag && Auth::user()->gender == 1) {
                $file = File::get(storage_path('app/avatars/female.png'));
            } else if (!$flag && Auth::user()->gender == 0)
                $file = File::get(storage_path('app/avatars/male.png'));
            else
                $file = File::get(storage_path('app/avatars/' . $nameOfFile));
            return $file;

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




    public function create()
    {
        if (session('CompanyCount') == 0) {
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::NotExistCompanyYet);
            session(['message' => $message]);
            return view('company/create');
        }
        //checkModule($modouleId,$company_id,$user_id, $destinationSuccessUrl,$toggleCreateOrStore,$ModuleCount,$company_id_For_destination_Error_Url)
        try
        {


            $moduleCounts=app('App\Http\Controllers\ModuleController')->sums_of_module_purchase_count();

            $count_of_company_pourches=0;
            foreach ($moduleCounts as $m)
            {
                if($m->module_id == ModuleRepository::newCompanyModule)
                    $count_of_company_pourches=$m->sum;
            }
            $flag=false;
            if($count_of_company_pourches>session('CompanyCount'))
                $flag=true;

            if($flag)
                return view('company/create')->with('message', '.');
            else{
                $message = new OperationMessage();
                $message->initializeByCode(OperationMessage::NotActiveThisModule);
                return redirect('module/publicindex/'.session('companiesId0').'/'.session('companiesGuid0'))->with('message', $message);
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

    public function store(Request $request)
    {
        try {
//            $if_Condition_for_chek_Module=app('App\Http\Controllers\ModuleController')->checkModule(ModuleRepository::newCompanyModule,null,Auth::user()->user_id,'company/create', false,session('CompanyCount'),session('companiesId0'));

            $moduleCounts = app('App\Http\Controllers\ModuleController')->sums_of_module_purchase_count();

            $count_of_company_pourches = 0;
            foreach ($moduleCounts as $m) {
                if ($m->module_id == ModuleRepository::newCompanyModule)
                    $count_of_company_pourches = $m->sum;
            }
            $flag = false;
            if ($count_of_company_pourches > session('CompanyCount'))
                $flag = true;

            if ($flag) {
                $rules = [
                    'name' => 'required|max:255|unique:company,deleted_at,NULL',
                    'fileLogo' => 'mimes:jpeg,png,bmp,gif,svg'
                ];
                $v = Validator::make($request->all(), $rules);
                if ($v->fails()) {
                    return redirect()->back()->withErrors($v->errors())->withInput($request->except('file'));

                } else {
                    $this->CompanyRepository->initializeByRequest($request);
                    $company_guid = $this->CompanyRepository->store();
                    //get company id from db
                    $CompanyRow = DB::table('company')
                        ->where('company_guid', $company_guid)
                        ->where('deleted_at',null)
                        ->get();

                    //fill one usercompany row
                    $userCompany = new UserCompanyRepository(null);
                    $userCompany->set_user_and_company_id(Auth::user()->user_id, $CompanyRow[0]->company_id);
                    $userCompany->store();
                    app('App\Http\Controllers\CompanyUserModuleController')->register_default_free_moduals_for_company($CompanyRow[0]->company_id);
                    self::deleteSession();
                    self::setProfile();
                    $message = new OperationMessage();
                    $message->initializeByCode(OperationMessage::OperationSuccessCode);


                    if ($request->hasFile('fileLogo')) {

                        $file = $request->file('fileLogo');
                        $fileName = $company_guid . '.' . $file->guessClientExtension();
                        $destinationPath = storage_path() . '/app/company';
                        $file->move($destinationPath, $fileName);
                    }
                    return Redirect::to('/companyList')->with('message', $message);
                }
            } else {
                $message = new OperationMessage();
                $message->initializeByCode(OperationMessage::NotActiveThisModule);
                return redirect('module/publicindex/'.session('companiesId0').'/'.session('companiesGuid0'))->with('message', $message);
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

    public function destroy($company_id, $company_guid)
    {
        try
        {
            $users = DB::table('user_company')
                ->join('user', 'user_company.user_id', '=', 'user.user_id')
                ->where('user_company.company_id','=',$company_id)
                ->where('user.deleted_at','=',null)
                ->where('user_company.deleted_at',null)
                ->where('user.deleted_at',null)
                ->select('user.*')
                ->get();
            if(count($users)!=0){
                foreach ($users as $user)
                    if($user->user_type_id!=UserTypeRepository::CEO){
                        app('App\Repositories\UserRepository')->deleteAvatar($user->user_guid);
                        $deleteUser=new UserRepository(new User());
                        $deleteUser->find($user->user_id)->delete();
                    }
            }
//           throw new Exception();


            //DELETE USER'S AVATAR OF THIS COMPANY and delete users


            $this->CompanyRepository->deleteLogo($company_guid);
            $this->CompanyRepository->initialize(null);
            $this->CompanyRepository->set($company_id, $company_guid);
            $this->CompanyRepository->delete();
            //DELETE SESSION
            self::deleteSession();
            self::setProfile();


            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationSuccessCode);
            session(['message' => $message]);
            return redirect()->back();

        }
        catch (\Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }


    }

    public function edit($company_id, $company_guid)
    {

        try
        {
            $company_Repository=new CompanyRepository(new Company());
            $company=$company_Repository->findByIdAndGuid($company_id,$company_guid);
            $time_zone = $company->time_zone;
            $result=$company->name;
            $zone = $company->zone;

            $destinationPath = storage_path() . '/app/company';
            $files1 = scandir($destinationPath);
            $nameOfFile = "";
            $search = $company_guid;
            $search_length = strlen($search);
            foreach ($files1 as $key => $value) {
                if (substr($value, 0, $search_length) == $search) {
                    $nameOfFile = $value;
                    break;
                }
            }
            return view('company/update', ['company_id' => $company_id, 'company_guid' => $company_guid, 'logoPath' => $nameOfFile])->with(['nameOfCompany' => $result, 'time_zone' => $time_zone, 'zone' => $zone]);


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
        log::info('zoneeeeeeeeeeee'.json_encode($request['zone']));
        $this->validate($request, [
            'name' => 'required|max:255|unique:company,deleted_at,NULL',
            'fileLogo' => 'mimes:jpeg,png,bmp,gif,svg'
        ]);
        try
        {

            $results = DB::table('company')
                ->where('company_id', $request->input('company_id'))
                ->where('deleted_at',null)
                ->pluck('name');
            $this->CompanyRepository->Update($request);
            if ($request->hasFile('fileLogo')) {
                $this->CompanyRepository->UpdateLogoOfCompany($request);
            }

            $resultrow = "";
            foreach ($results as $result) {
                $resultrow = $result;
                break;
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

        if ($resultrow != $request->input('name')) {
            $this->validate($request, [
                'name' => 'max:255'
            ]);
        }

        try
        {
            self::deleteSession();
            self::setProfile();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationSuccessCode);
            return Redirect::to('/companyList')->with('message', $message);
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

    public function deleteSession()
    {
        try
        {
            $CompanyRepository = null;
            if (Auth::user()->user_type_id == 1) {
                $paramsObj1 = array(
                    array("st", "company")
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
                if (Auth::user()->user_type_id == 1) {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user.user_id='" . Auth::user()->user_id . "'"
                        )

                    );
                } elseif (Auth::user()->user_type_id == 0) {
                    $paramsObj3 = null;
                }
                /////add deleted at condition to query - meysam/////////

                $paramsObj3[] =   array("whereRaw",
                    "user_company.deleted_at is null"
                );
                $paramsObj3[] =   array("whereRaw",
                    "user.deleted_at is null"
                );
                $paramsObj3[] =   array("whereRaw",
                    "company.deleted_at is null"
                );


                /// ///////////////////////////////////////
                $this->CompanyRepository->initialize();

                $CompanyRepository = $this->CompanyRepository->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);

                $counter = 0;
                $companiesName = array(10);
                $companiesNameId = array(10);
                $companiesNameGuid = array(10);
                foreach ($CompanyRepository as $company) {
                    session()->forget('companiesName' . $counter);
                    session()->forget('companiesId' . $counter);
                    session()->forget('companiesGuid' . $counter);
                    session()->forget('companiesTimezone' . $counter);
                    $counter++;
                }
                session()->forget('CompanyCount');
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function setProfile()
    {
        try
        {
            if (!Auth::user())
            {
                // Auth::user() returns an instance of the authenticated user...
                $message = new OperationMessage();
                $message->initializeByCode(OperationMessage::OperationErrorCode);
                return redirect()->back()->with('message', $message);
            }
            $CompanyRepository = '';

            if (Auth::user()->user_type_id == UserTypeRepository::getCEOCode() ||
                Auth::user()->user_type_id == UserTypeRepository::getMiddleCEOCode() ||
                Auth::user()->user_type_id == UserTypeRepository::getEmployeeCode()) {
                $paramsObj1 = array(
                    array("st", "company")
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
                if (Auth::user()->user_type_id == UserTypeRepository::getCEOCode() ||
                    Auth::user()->user_type_id == UserTypeRepository::getMiddleCEOCode() ||
                    Auth::user()->user_type_id == UserTypeRepository::getEmployeeCode()) {
                    $paramsObj3 = array(
                        array("whereRaw",
                            "user.user_id='" . Auth::user()->user_id . "'"
                        )

                    );
                } elseif (Auth::user()->user_type_id == UserTypeRepository::getAdminCode()) {
                    $paramsObj3 = null;
                }
                /////add deleted at condition to query - meysam/////////

                $paramsObj3[] =   array("whereRaw",
                    "user_company.deleted_at is null"
                );
                $paramsObj3[] =   array("whereRaw",
                    "user.deleted_at is null"
                );
                $paramsObj3[] =   array("whereRaw",
                    "company.deleted_at is null"
                );

                /// ///////////////////////////////////////
                $this->CompanyRepository->initialize();

                $CompanyRepository= $this->CompanyRepository->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);

                $counter = 0;
                foreach ($CompanyRepository as $company) {

                    session(['companiesName' . $counter => $company->name]);
                    session(['companiesId' . $counter => $company->company_id]);
                    session(['companiesGuid' . $counter => $company->company_guid]);
                    session(['companiesTimezone' . $counter => $company->time_zone]);
                    $counter++;
                }
                session(['CompanyCount' => count($CompanyRepository)]);
            }


            self::countOfMission(session('companiesId0'), session('companiesGuid0'));
            self::countOfAttendance(session('companiesId0'), session('companiesGuid0'));




            $paramsObj1 = array(
                array("st", "company")
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
            if (Auth::user()->user_type_id == UserTypeRepository::CEO) {
                $paramsObj3 = array(
                    array("whereRaw",
                        "user.user_id='" . Auth::user()->user_id . "'"
                    )
                );
            } elseif (Auth::user()->user_type_id == UserTypeRepository::Admin) {
                $paramsObj3 = null;
            }
            /////add deleted at condition to query - meysam/////////

            $paramsObj3[] =   array("whereRaw",
                "user_company.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "user.deleted_at is null"
            );
            $paramsObj3[] =   array("whereRaw",
                "company.deleted_at is null"
            );

            /// ///////////////////////////////////////

            if(\Illuminate\Support\Facades\Auth::user()->user_type_id==UserTypeRepository::getCEOCode())
            {
                $this->CompanyRepository->initialize();

                $CompanyRepository = $this->CompanyRepository->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);

                $last_attendances_for_users = DB::select('
                    SELECT user.name as user_first_name,
                    user.family as user_last_name,
                    user.user_type_id as user_type,
                    company.name as company_name,
                    attendance.start_date_time,
                    attendance.end_date_time, MAX(attendance.attendance_id) as max_att_id 
                    FROM user join user_company on user.user_id = user_company.user_id  
                    LEFT OUTER JOIN attendance on user_company.user_company_id = attendance.user_company_id 
                    JOIN company on company.company_id = user_company.company_id 
                    LEFT OUTER JOIN attendance p2 ON (user_company.user_company_id = p2.user_company_id AND (attendance.attendance_id < p2.attendance_id OR attendance.attendance_id = p2.attendance_id AND attendance.attendance_id < p2.attendance_id))
                    WHERE p2.attendance_id IS NULL
                    and attendance.deleted_at is null
                    and company.deleted_at is null 
                    and user_company.deleted_at is null 
                    and user.deleted_at is null  
                    and user_company.company_id in (select user_company.company_id FROM user_company WHERE user_company.user_id = :id2 AND user_company.deleted_at is NULL)and user.user_type_id not in (0, 1) and user.user_id <> :id 
                    GROUP By attendance.user_company_id ,user_first_name,user_last_name,user_type,company_name,attendance.start_date_time,attendance.end_date_time
                ', ['id' => Auth::user()->user_id,
                    'id2' => Auth::user()->user_id]);

                return view('dashboard/dashboard', ['CompanyRepository' => $CompanyRepository,'last_attendances_for_users'=>$last_attendances_for_users]);
            }
            elseif (\Illuminate\Support\Facades\Auth::user()->user_type_id==UserTypeRepository::getMiddleCEOCode()){
                $ceoUser = UserRepository::getManager(Auth::user()->user_id);

                $this->CompanyRepository->initialize();

                $CompanyRepository = $this->CompanyRepository->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);

                $last_attendances_for_users = DB::select('
                    SELECT user.name as user_first_name,
                    user.family as user_last_name,
                    user.user_type_id as user_type,
                    company.name as company_name,
                    attendance.start_date_time,
                    attendance.end_date_time, MAX(attendance.attendance_id) as max_att_id 
                    FROM user join user_company on user.user_id = user_company.user_id  
                    LEFT OUTER JOIN attendance on user_company.user_company_id = attendance.user_company_id 
                    JOIN company on company.company_id = user_company.company_id 
                    LEFT OUTER JOIN attendance p2 ON (user_company.user_company_id = p2.user_company_id AND (attendance.attendance_id < p2.attendance_id OR attendance.attendance_id = p2.attendance_id AND attendance.attendance_id < p2.attendance_id))
                    WHERE p2.attendance_id IS NULL
                    and attendance.deleted_at is null
                    and company.deleted_at is null 
                    and user_company.deleted_at is null 
                    and user.deleted_at is null  
                    and company.company_id = :company_id 
                    and user_company.company_id in (select user_company.company_id FROM user_company WHERE user_company.user_id = :id2 AND user_company.deleted_at is NULL)and user.user_type_id not in (0, 1) and user.user_id <> :id 
                    GROUP By attendance.user_company_id ,user_first_name,user_last_name,user_type,company_name,attendance.start_date_time,attendance.end_date_time
                ', [
                    'id' => $ceoUser[0]->user_id,
                    'id2' => $ceoUser[0]->user_id,
                    'company_id' => session('companiesId0')
                ]);

                log::info('zzzzzzzzz'.json_encode($last_attendances_for_users));
                return view('dashboard/dashboard', ['CompanyRepository' => $CompanyRepository,'last_attendances_for_users'=>$last_attendances_for_users]);

            }
            else
                return view('dashboard/dashboard');



        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();

            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }


        // return view('dashboard/dashboard');

    }

    public function countOfAttendance($company_id, $company_guid)
    {//$company_id,$company_guid
        try
        {
            $AttendanceList = Db::table('user_company')
                ->select('user.name', 'user.family', 'user.user_id', 'attendance.*')
                ->where('user_company.company_id', '=', $company_id)
                ->join('user', 'user_company.user_id', '=', 'user.user_id')
                ->join('attendance', 'attendance.user_company_id', '=', 'user_company.user_company_id')
                ->where('attendance.deleted_at', null)
                ->where('user_company.deleted_at', null)
                ->where('user.deleted_at', null)
                ->orderBy('attendance.user_company_id', 'desc')
                ->get();

            $top5AttendanceArray = array();
            if ($AttendanceList != null) {
                $counter = 0;

                for ($index = 0; $index < count($AttendanceList); $index++) {
                    $timeFirst = strtotime($AttendanceList[$index]->start_date_time);
                    $timeSecond = strtotime($AttendanceList[$index]->end_date_time);
                    if($AttendanceList[$index]->start_date_time!=null && $AttendanceList[$index]->end_date_time!= null && $timeSecond>$timeFirst){

                        $differenceInSeconds = $timeSecond - $timeFirst;
                        if (!array_key_exists($AttendanceList[$index]->user_id, $top5AttendanceArray)) {
                            $top5AttendanceArray[$AttendanceList[$index]->user_id] = $differenceInSeconds;
                        } else {
                            $top5AttendanceArray[$AttendanceList[$index]->user_id] += $differenceInSeconds;
                        }
                    }
                }
            }
            arsort($top5AttendanceArray);


            $counter = 0;
            foreach ($top5AttendanceArray as $key => $value) {

                if ($counter >= 5) break;
                for ($index = 0; $index < count($AttendanceList); $index++) {
                    if ($AttendanceList[$index]->user_id == $key) {
                        session(['top5attendaceName' . $counter => $AttendanceList[$index]->name]);
                        session(['top5attendaceFamily' . $counter => $AttendanceList[$index]->family]);
                        break;
                    }
                }
                session(['top5attendaceCount' . $counter => $value]);
                $counter++;
            }
            if ($counter >= 5) session(['top5attendaceCount' => 5]);
            else session(['top5attendaceCount' => count($top5AttendanceArray)]);

            log::info('$top5AttendanceArray : '.json_encode($top5AttendanceArray));


            return $top5AttendanceArray;

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

    public function countOfMission($company_id, $company_guid)
    {
        try
        {
            $top5mission = Db::table('user')
                ->select(array('user.name', 'user.family', DB::raw('COUNT(user_mission.user_company_id) as followers')))
                ->where('company.company_id', '=', $company_id)
                ->where('company.company_guid', '=', $company_guid)
                ->where('user_mission.deleted_at', '=', null)
                ->join('user_company', 'user_company.user_id', '=', 'user.user_id')
                ->join('company', 'company.company_id', '=', 'user_company.company_id')
                ->join('user_mission', 'user_mission.user_company_id', '=', 'user_company.user_company_id')
                ->where('company.deleted_at', null)
                ->where('user_mission.deleted_at', null)
                ->where('user_company.deleted_at', null)
                ->where('user.deleted_at', null)
                ->groupBy('user.name', 'user.family')
                ->orderBy('followers', 'desc')
                ->get();


            if ($top5mission != null) {
                $counter = 0;
                foreach ($top5mission as $mission) {
                    if ($counter >= 5) break;
                    session(['top5missionName' . $counter => $mission->name]);
                    session(['top5missionFamily' . $counter => $mission->family]);
                    session(['top5missionCount' . $counter => $mission->followers]);
                    $counter++;
                }
                if ($counter >= 5) session(['top5missionCount' => 5]);
                else session(['top5missionCount' => count($top5mission)]);
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


}
