<?php
//Meysam Arab - 13950829

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\ModuleRepository;
use App\UserCompany;
use Illuminate\Http\Request;
use Auth;
use App\OperationMessage;
use DB;
use App\User;
use Redirect;
use Illuminate\Support\Facades\Session;
use Validator;
use Illuminate\Http\Response;
use App\Repositories\UserRepository;
use Exception;
use App\Repositories\LogEventRepository;
use Route;
use Log;
use App\Utility;

class UserController extends Controller
{
    /**
     * @var User
     */
//    public function __construct()
//    {
//        //this is other way for calling auth apart from writing in rout file...
////        $this->middleware('auth');
//    }

    protected $userRepo;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->userRepo = $user;
    }

    public function index() {
        try
        {
            $this->userRepo->initialize(null);
            $users = $this->userRepo->select($this->userRepo);
            $users = $this-> userRepo->all();
            return view('user.index', ['users' => $users]);

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
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {


        return view('user.create');
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
       // log::info('salam   '.json_encode($request->all()));
//        sleep(5);

    //check captcha
   /////////////////////////////
        $gcaptcha = $request->input('captcha');
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => '6LeHzyEUAAAAABXg8x6Eetw4uI24fe0Dz49xtc7j', 'response' => $gcaptcha);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $temp=json_decode($result);
        if($temp->{'success'} ==false)
        {
            $message = trans('messages.msg_ErrorCaptcha');
            return array('success' => false, 'message' => [$message]);
        }
//        ////////////////////////////
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'family' => 'required|max:30',
            'user_name' => 'required|regex:/^[\w-]*$/',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
//        log::info(json_encode($request->all()));
        if ($request->input('licenseRead')=='no') {
            $errors = [
                'license.readed' => 'لطفا موافقت نامه کاربری را علامت بزنید',
            ];
            return response()->json([
                'success' => false,
                'message' => $errors
            ]);
        }
        if (UserRepository::existByEmail($request->input('email'),null,null)) {
            $errors = [
                'email.unique' => 'ایمیل قبلا ثبت شده',
            ];
            return response()->json([
                'success' => false,
                'message' => $errors
            ]);
        }
        if (UserRepository::existByEmail($request->input('user_name'),null,null)) {
            $errors = [
                'user_name.unique' => 'این نام کاربری قبلا ثبت شده',
            ];
            return response()->json([
                'success' => false,
                'message' => $errors
            ]);
        }
        $pass = $request['password'];
        if($validation->passes()){
            $request['password'] = bcrypt($request['password']);
            $this->userRepo->initializeByRequest($request);
            $this->userRepo->store();
//            Utility::sendMail($request->input('email'), $pass);
            $message = trans('messages.txt_OperationSuccess');
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }else{
            $errors = $validation->errors()->all();
            return response()->json([
                'success' => false,
                'message' => $errors
            ]);
        }


    }

    public function AddMembers($company_id,$company_guid)
    {
        try
        {
            //checkModule($modouleId,$company_id,$user_id, $destinationSuccessUrl,$toggleCreateOrStore,$ModuleCount,$company_id_For_destination_Error_Url)
            $moduleCounts=app('App\Http\Controllers\ModuleController')->sums_of_module_purchase_count();

            $count_of_user_registered=app('App\Http\Controllers\ModuleController')->count_Of_users();

            $count_of_company_pourches=0;
            foreach ($moduleCounts as $m)
            {
                if($m->module_id == ModuleRepository::newEmployeeModule)
                    $count_of_company_pourches=$m->sum;
            }

            if($count_of_company_pourches>count($count_of_user_registered))
            {
                Session::set('company_id', $company_id);
                Session::set('company_guid', $company_guid);
                return view('user.AddMembersOfCompany')->with(['company_id'=>$company_id,'company_guid'=>$company_guid]);

            }else{
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

    public function storeAddMembers(Request $request)
    {
        try
        {
            $moduleCounts=app('App\Http\Controllers\ModuleController')->sums_of_module_purchase_count();

            $count_of_user_registered=app('App\Http\Controllers\ModuleController')->count_Of_users();

            $count_of_users_pourches=0;
            foreach ($moduleCounts as $m)
            {
                if($m->module_id == ModuleRepository::newEmployeeModule)
                    $count_of_company_pourches=$m->sum;
            }
            $flag=false;
            if($count_of_company_pourches>count($count_of_user_registered))
                $flag=true;
        }
        catch (Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }

        $this->validate($request, [
            'name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'family' => 'required|regex:/^[\pL\s\-]+$/u',
            'user_name' => 'required|unique:user|regex:/^[A-Za-z-.]+$/',
            'email' => 'required|email|unique:user',
            'password' => 'required|confirmed',
            'fileLogo' => 'mimes:jpeg,png,bmp,gif,svg',
            'company_id'=>'required',
            'code'=>'required'
        ]);
        try
        {
            if($flag) {
                $pass = $request['password'];
                $request['password'] = bcrypt($request['password']);

                $this->userRepo->initializeByRequest($request);
//        $user = new UserRepositoryInterface($request);



//        $user->name = $request->name;
                $this->userRepo->storeForeEmployment($request);

                //get user id from db
                $UserRow = DB::table('user')
                    ->where('user_name',$request['user_name'])
                    ->where('deleted_at',null)
                    ->get();

                $file = $request->file('fileLogo');
                if ($file!=null && $file->isValid() ) {
                    $fileName = $UserRow[0]->user_guid . '.' . $file->guessClientExtension();
                    $destinationPath = storage_path() . '/app/avatars';
//            Image::make($file)->resize(100, 100);
                    $file->move($destinationPath, $fileName);

                }
                //fill one usercompany row
                $UserCompanyRow=new UserCompany();
                $UserCompanyRow->user_company_guid=uniqid('',true);
                $UserCompanyRow->user_id=$UserRow[0]->user_id;
                $UserCompanyRow->company_id=$request['company_id'];
                $UserCompanyRow->save();
//                Utility::sendMail($request->input('email'), $pass);
                $message='اطلاعات با موفقیت ثبت شد';
                //return redirect()->back()->with(['message'=>$message]);
                return Redirect::to('/companyList');
            }
            else{
                $message = new OperationMessage();
                $message->initializeByCode(OperationMessage::NotActiveThisModule);
                return redirect('module/publicindex/'.session('companiesId0').'/'.session('companiesGuid0'))->with('message', $message);
            }
        }
        catch(Exception $e)
        {
            $logEvent = new LogEventRepository((Auth::check() == true?Auth::user()->user_id:-1),Route::getCurrentRoute()->getActionName(),"Main Message: ".$e->getMessage()." Stack Trace: ".$e->getTraceAsString());
            $logEvent->store();
            $message = new OperationMessage();
            $message->initializeByCode(OperationMessage::OperationErrorCode);
            return redirect()->back()->with('message', $message);
        }
    }

    public function ListMembers($company_id,$company_guid)
    {
        try
        {
            $userRepository=$this->userRepo->GetListUsersOfCompany($company_id,$company_guid);

            $destinationPath = storage_path().'/app/company';
            $files1 = scandir($destinationPath);
            $nameOfFile="";
            $search =$company_guid;
            $search_length = strlen($search);
            foreach ($files1 as $key => $value) {
                if (substr($value, 0, $search_length) == $search) {
                    $nameOfFile=$value;
                    break;
                }
            }

            return view('company.listOfThisCompanyMembers', ['UserRepositories' => $userRepository,'logoPath'=>$nameOfFile,'company_id'=>$company_id,'company_guid'=>$company_guid]);
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
     * Display the specified resource.
     * @param  int  $id
     * @return Response
     */
    public function show($user_id,$user_guid){
        try
        {
            $this->userRepo->set($user_id,$user_guid);
//        $this->userRepo->user_id = $user_id;
//        $this->userRepo->user_guid = $user_guid;
//        $currentUser -> user_id = 1;
//        list($provider, $users) = User::select($currentUser);
            $users = $this->userRepo->select($this->userRepo);

            $user = $users[0];

//        $provider = new EloquentDataProvider(user::class);

            return view('user.details', ['user' => $user]);
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
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return Response
     */
    public function edit($user_id,$user_guid)
    {
        try
        {
            $this->userRepo->set($user_id,$user_guid);
            $users = $this->userRepo->select($this->userRepo);
            $user = $users[0];
            return view('user.edit', ['user' => $user]);
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
     * Update the specified user in storage.
     *
     *
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|regex:/^[\pL\s\-]+$/u',
            'family' => 'required|regex:/^[\pL\s\-]+$/u',
            'user_name' => 'unique:user|regex:/^[A-Za-z-.]+$/',
            'email' => 'required|email',
            'password' => 'confirmed',
            'fileLogo' => 'mimes:jpeg,png,bmp,gif,svg',
            'code'=>'required'
        ]);


        try
        {



            $company=$this->userRepo->getCompany($request ->input('user_id'),$request ->input('user_guid'));
            //Company::get
            $this->userRepo->Update($request);
            if ($request->hasFile('fileLogo')) {
                $this->userRepo->UpdateAvatar($request);
            }

               return Redirect('/company/ListMembers/'.$company[0]->company_id.'/'.$company[0]->company_guid);
            return Redirect::back();
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



    public function updateForEmployer(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'family' => 'required',
            'email'=>'email',
//            'user_name' => 'required|unique:user',
        ]);
        try
        {

            $this->userRepo->setUserTypeId($request);
            $this->userRepo->initializeByRequest($request);
            $this->userRepo -> Update($request);
            return Redirect::back();
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
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return Response
     */
    public function destroy($user_id,$user_guid){

        try
        {
                if (!$this->userRepo->exist($user_id,$user_guid)){
                    return  redirect()->action('HomeController@error');
                }

        //        $this->userRepo->user_id = $user_id;
        //        $this->userRepo->user_guid = $user_guid;
                $this->userRepo->set($user_id,$user_guid);
        //        $currentUser -> user_id = 1;
                 $users = $this->userRepo->select($this->userRepo);
                 $user = $users[0];
        //        $provider = new EloquentDataProvider(user::class);
                $result = $this->userRepo->delete();

                $this->userRepo->deleteAvatar($user_guid);
                return redirect()->back();
        //        return  redirect()->action('UserController@index');
        //        return view('user.delete', ['user' => $user]);

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

    public function userList() {
        try
        {
            $this->userRepo->initialize(null);
            $userss = $this->userRepo->select($this->userRepo);
            $users = $this-> userRepo->all();
            return view('user.index', ['users' => $users, 'provider'=>$userss]);
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

    public function UserOfCompanyEdit($user_id,$user_guid)
    {
        try
        {
            $this->userRepo->set($user_id,$user_guid);
            $users = $this->userRepo->select($this->userRepo);
            $user = $users[0];
            return view('user.editForUsersOfCompany', ['user' => $user]);
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

    public function QR_Code($user_id,$user_guid)
    {
        $users = DB::table('user')
            ->where('user_id', $user_id)
            ->where('user_guid', $user_guid)
            ->first();
        log::info($user_id.'sss'.$user_guid);
            return view('user.qrcode', ['cur_user' => $users]);
    }

}