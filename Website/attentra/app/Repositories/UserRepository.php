<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 12/6/2016
 * Time: 1:32 PM
 */
// app/Repositories/PostRepository.php

namespace App\Repositories;

use App\Company;
use App\User;
use App\UserCompany;
use ViewComponents\Eloquent\EloquentDataProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Log;
use DB;
use File;
use Carbon\Carbon;

class UserRepository implements UserRepositoryInterface
{
    protected $User;

    const Female= 1;
    const Male= 0;


    public function __construct(User $user)
    {

        $this->user = $user;
        ///////////////////////

    }

    public function initialize()
    {
//    $instance = new self();
    $this->user  -> user_id = null;
            $this->user -> user_guid = null;
            $this->user -> name = null;
            $this->user->family = null;
            $this->user->user_name = null;
            $this->user->password = null;
            $this->user->user_type_id = null;
            $this->user->report_row_limit = null;
            $this->user->code = null;
            $this->user->email = null;
            $this->user->payment = null;
            $this->user->start_time = null;
            $this->user->end_time = null;
            $this->user->end_sound = null;
            $this->user->cloud = null;
            $this->user->gender = null;
            $this->user->phone_code = null;
            $this->user->remember_token = null;
            $this->user->is_active = null;
        $this->user->country_id =  null;

//    return $instance;
}

    public function initializeByRequest($request = null)
    {
        $this->user-> user_id = $request ->input('user_id');
        $this ->user-> user_guid = $request ->input( 'user_guid');
        $this ->user-> name = $request -> input('name');
        $this->user->family = $request -> input('family');
        $this->user->user_name = $request ->input( 'user_name');
        $this->user->password = $request ->input( 'password');
        $this->user->user_type_id = $request ->input('user_type_id');
        $this->user->report_row_limit = $request ->input( 'report_row_limit');
        $this->user->code = $request ->input( 'code');
        $this->user->payment = $request ->input( 'payment');
        $this->user->start_time = $request ->input( 'start_time');
        $this->user->end_time = $request ->input( 'end_time');
        $this->user->end_sound = $request ->input( 'end_sound');
        $this->user->cloud = $request ->input( 'cloud');
        $this->user->email = $request ->input( 'email');
        $this->user->gender = ($request ->input('gender'));
        $this->user->phone_code = ($request ->input('phone_code'));
        $this->user->remember_token = $request ->input( 'remember_token');
        $this->user->is_active = $request ->input( 'is_active');
        $this->user->country_id = $request ->input( 'country_id');

        $this->user->deleted_at = null;
    }

    public function GetListUsersOfCompany($company_id,$company_guid)
    {
        $paramsObj1=array(
            array("st","user"),
            array("se", "user_company", "self_roll_call")
        );

        //join
        $paramsObj2=array(
            array("join",
                "user_company",
                array("user_company.user_id","=","user.user_id")
            )
        );
        //conditions
        $paramsObj3=array(
            array("whereRaw",
                "user_company.company_id='".$company_id."'"
            ),
            array("whereRaw",
                "user.user_type_id !='0'"
            ),
            array("whereRaw",
                "user_company.deleted_at is null"
            ),
        );

        //ini_set('error_reporting', E_STRICT);
        $this->initialize();
        //fetch advertisments
        return $this->getFullDetailUser($paramsObj1,$paramsObj2,$paramsObj3);
    }

    public function set($id,$guid)
    {
        $this->user->user_id = $id;
        $this->user->user_guid = $guid;

    }

    public function setUserName($user_name)
    {
        $this->user->user_name = $user_name;
    }

    public function find($id)
    {
        return $this->user->find($id);
    }

    public function findByIdAndGuid($id, $guid)
    {
        $query = $this->user->newQuery();
        $query->where('user_id', '=', $id);
        $query->where('user_guid', 'like', $guid);
        $users = $query->get();
        if (count($users) == 0){
            return null;
        }
        else{
            return $users[0];
        }

    }

    public function all(){
        $className = $this->user;
        return $className::all(); // This will work.
    }

    public function select()
    {
        $query = $this->user->newQuery();
        if($this->user->user_id != null){
            $query->where('user_id', '=', $this->user->user_id);
        }
        if($this->user->user_guid != null){
            $query->where('user_guid', '=', $this->user->user_guid);
        }
        if($this->user->user_name != null){
            $query->where('user_name', '=', $this->user->user_name);
        }
        $query->where('deleted_at', null);
        $users = $query->get();

        return $users;
    }

    public function exist($Id,$guid)
    {
        $query = $this->user->newQuery();
        $query->where('user_id', '=', $Id);
        $query->where('user_guid', '=', $guid);
        $users = $query->get()->first();
        if (count($users) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public static function existByEmail($email, $forEdit, $userId)
    {
        $user = new User();
        $query = $user->newQuery();
        $query->where('email', 'like', $email);
        if($forEdit)
        {
            $query->where('user_id', '<>', $userId);
        }

        $users = $query->get()->first();
        if (count($users) == 0){
            return false;
        }
        else{
            return true;
        }
    }
    public static function existByUserName($userName, $forEdit, $userId)
    {
        $user = new User();
        $query = $user->newQuery();
        $query->where('user_name', 'like', $userName);
        if($forEdit)
        {
            $query->where('user_id', '<>', $userId);
        }
        $users = $query->get()->first();
        if (count($users) == 0){
            return false;
        }
        else{
            return true;
        }
    }

    public function paginate(){

    }

    public function store(){
//        $className = $this->user;
//        return $className->save(); // This will work.
        $this->user->user_guid = uniqid('',true);
        $this->user->report_row_limit = 1;
        $this->user->is_active = 1;
        $this->user->cloud = 1;
        $this->user->payment=0;
        $this->user->user_type_id = 1;
        $this->user->save();
        $user_id = DB::table('user')
            ->where('user_guid', $this->user->user_guid)
            ->where('deleted_at',null)
            ->value('user_id');
        app('App\Http\Controllers\CompanyUserModuleController')->register_default_free_moduals($user_id);

    }

    public function api_Store(){
//        $className = $this->user;
//        return $className->save(); // This will work.
        $this->user->user_guid = uniqid('',true);
        $this->user->report_row_limit = 1;
        $this->user->is_active = 1;
        $this->user->cloud = 1;
        $this->user->payment=0;
        $this->user->user_type_id = 1;
        $this->user->save();
        $user_id = DB::table('user')
            ->where('user_guid', $this->user->user_guid)
            ->where('deleted_at',null)
            ->value('user_id');

    }

    public function storeForeEmployment($request){
        $this->user->user_guid = uniqid('',true);
        $this->user->report_row_limit = 1;
        $this->user->is_active = 1;
        $this->user->cloud = 1;
        $this->user->payment=0;
        //$this->user->user_type_id = 3;
//        $user->api_token=str_random(60);

//        $this->user->user_type_id=2;
        $this->setUserTypeId($request);
       $this->setUserSex($request);

        $this->user->save();

    }

    public function API_storeForeEmployment($request){
        $this->user->user_guid = uniqid('',true);
        $this->user->report_row_limit = 1;
        $this->user->is_active = 1;
        $this->user->cloud = 1;
        $this->user->payment=0;
        //$this->setUserSex($request);
        if($request -> input('gender')=='male' || $request -> input('gender')=='آقا')
            $this->user->gender=0;
        else
            $this->user->gender=1;
        $this->user->save();

    }

    public function delete(){
//        $this->user->find($this->user -> user_id)->softDeletes();

        //throw new \Exception();
//        log::info("qqqqqqqqqqqqqqqqqqqqqqqq");
        $this->findByIdAndGuid($this->user -> user_id,$this->user -> user_guid)->delete();


        $user_companies = DB::select( DB::raw('SELECT * from user_company where user_company.company_id IN(select MIN(user_company.company_id) from user_company where user_company.user_id = :muser_id) and user_company.user_id = :muser_id2'), array(
            'muser_id' => $this->user -> user_id,
            'muser_id2' => $this->user -> user_id,
        ));
//        log::info("qqqqqqqqqqqqqqqqqqqqqqqq".json_encode($user_companies[0]->user_company_id));
        DB::table('attendance')
            ->where('user_company_id', $user_companies[0]->user_company_id)
            ->update(['deleted_at' => Carbon::now()]);

        DB::table('user_mission')
            ->where('user_company_id', $user_companies[0]->user_company_id)
            ->update(['deleted_at' => Carbon::now()]);


    }

    public static function deleteByIdAndGuid($user_id, $user_guid){

        $userrep = new UserRepository(new User());
        $userrep->findByIdAndGuid($user_id,$user_guid)->delete();


    }

    public function findBy($field,$value){

    }

    public function update($request)
    {
        self::initializeByRequest($request);
        $oldUser = $this->findByIdAndGuid($this->user -> user_id,$this->user -> user_guid);

        $oldUser->name=$request -> input('name');
        $oldUser->family=$request -> input('family');
        $oldUser->code=$request -> input('code');
        $oldUser->country_id=$request -> input('country_id');

        if($request -> has('phone_code'))
        {
            $oldUser->phone_code=$request -> input('phone_code');
            if($request -> input('phone_code')=="null")
                $oldUser->phone_code=null;
        }


        if($request['password']!=null){
            $request['password'] = bcrypt($request['password']);
            $oldUser->password=$request -> input('password');
        }
        $oldUser->email=$request -> input('email');

        if($request -> input('user_Sex')=='male')
            $oldUser->gender=0;
        else
            $oldUser->gender=1;

        if($request -> input('user_type_id')=='employer')
            $oldUser->user_type_id=3;
        else if($request -> input('user_type_id')=='midleManager')
            $oldUser->user_type_id=2;

        $oldUser->save();
    }
    public function apiUpdate($request)
    {
        self::initializeByRequest($request);
        $oldUser = $this->findByIdAndGuid($this->user -> user_id,$this->user -> user_guid);

        $oldUser->name=$request -> input('name');
        $oldUser->family=$request -> input('family');
        $oldUser->code=$request -> input('code');
        $oldUser->country_id=$request -> input('country_id');

        if($request -> has('phone_code'))
        {
            $oldUser->phone_code=$request -> input('phone_code');
            if($request -> input('phone_code')=="null")
                $oldUser->phone_code=null;
        }


        if($request['password']!=null){
            $request['password'] = bcrypt($request['password']);
            $oldUser->password=$request -> input('password');
        }
        $oldUser->email=$request -> input('email');

        if($request -> input('gender')=='male' || $request -> input('gender')=='آقا')
            $oldUser->gender=0;
        else
            $oldUser->gender=1;

        if($request -> input('user_type_id')=='employer')
            $oldUser->user_type_id=3;
        else if($request -> input('user_type_id')=='midleManager')
            $oldUser->user_type_id=2;

        $oldUser->save();
    }
    public function getFullDetailUser( $params1,$params2,$params3)
    {

        $query = $this->user->newQuery();
        //
        if($params1!=null) {

            $query=\App\Utility::fillQueryAlias($query,$params1);
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

        $users = $query->get();
//        return array($provider,$users);
        return $users;
//        return $query->get();
    }

    ////simplest query
    public function makeWhere($query){
        if($this->user->user_id != null){
            $query->where('	user.'.'user_id', '=', $this->user->user_id);
        }
        if($this->user->user_guid != null){
            $query->where('	user.'.'user_guid', '=', $this->user->user_guid);
        }
        if($this->user->name != null){
            $query->where('	user.'.'name', '=', $this->user->name);
        }
        if( $this->user->family != null){
            $query->where('	user.'.'family', '=', $this->user->family);
        }

        if($this->user->user_id != null){
            $query->where('	user.'.'user_id', '=', $this->user->user_id);
        }
        if($this->user->password != null){
            $query->where('	user.'.'password', '=', $this->user->password);
        }
        if($this->user->user_type_id != null){
            $query->where('	user.'.'user_type_id', '=', $this->user->user_type_id);
        }
        if( $this->user->report_row_limit != null){
            $query->where('	user.'.'report_row_limit', '=', $this->user->report_row_limit);
        }

        if($this->user->code != null){
            $query->where('	user.'.'code', '=', $this->user->code);
        }
        if($this->user->phone_code != null){
            $query->where('	user.'.'phone_code', '=', $this->user->phone_code);
        }
        if($this->user->payment != null){
            $query->where('	user.'.'payment', '=', $this->user->payment);
        }
        if($this->user->start_time != null){
            $query->where('	user.'.'start_time', '=', $this->user->start_time);
        }
        if( $this->user->end_time != null){
            $query->where('	user.'.'end_time', '=', $this->user->end_time);
        }

        if($this->user->end_sound != null){
            $query->where('	user.'.'end_sound', '=', $this->user->end_sound);
        }
        if($this->user->cloud != null){
            $query->where('	user.'.'cloud', '=', $this->user->cloud);
        }
        if($this->user->remember_token != null){
            $query->where('	user.'.'remember_token', '=', $this->user->remember_token);
        }
        if( $this->user->is_active != null){
            $query->where('	user.'.'is_active', '=', $this->user->is_active);
        }
        if($this->user->email != null){
            $query->where('	user.'.'email', '=', $this->user->email);
        }
        if($this->user->country_id != null){
            $query->where('	user.'.'country_id', '=', $this->user->country_id);
        }

        return $query;
    }

    public function setUserTypeId($request)
    {
        if($request['user_type_id']=='employer'){
            $request['user_type_id']=3;
            $this->user->user_type_id = 3;
        }
        elseif ($request['user_type_id']=='midleManager'){
            $request['user_type_id']=2;
            $this->user->user_type_id = 2;

        }

    }

    public function setUserSex($request)
    {

        if($request['user_Sex']=='male'){
            $request['user_Sex']=0;
            $this->user->gender = 0;
        }
        elseif ($request['user_Sex']=='female'){
            $request['user_Sex']=1;
            $this->user->gender = 1;

        }
        else
        {
            if($request['gender']=='male'){
                $request['user_Sex']=0;
                $this->user->gender = 0;
            }
            elseif ($request['gender']=='female'){
                $request['user_Sex']=1;
                $this->user->gender = 1;

            }
            else
            {
                $request['user_Sex']=0;
                $this->user->gender = 0;
            }
        }

    }

    public function set_id($user_id)
    {
        $this->user->user_id = $user_id;
    }

    public function set_user_name($user_name)
    {
        $this->user->user_name = $user_name;
    }

    public function set_image($image)
    {
        $this->user->image = $image;
    }

    public function get_id()
    {
        return $this->user->user_id;
    }

    public function get_guid()
    {
        return $this->user->user_guid;
    }

    public function get_user_type_id()
    {
        return $this->user->user_type_id;
    }

    public function get_user()
    {
        return $this->user;
    }

    public function get_phone_code()
    {
        return $this->user->phone_code;
    }

    public function set_phone_code($phone_code)
    {
        $this->user->phone_code = $phone_code;
    }

    public function get_email()
    {
        return $this->user->email;
    }
    public function EmployeeCount()
    {
        $user_company=new UserCompany();
        $query = $user_company->newQuery();


        for($index=0;$index<session('CompanyCount');$index++){
            $query->orWhere('company_id','=',session('companiesId'.$index));
        }
        $query->Where('user_id','<>',Auth::user()->user_id);
        $result = $query->get();
        return count($result);
    }

    public function deleteAvatar($user_guid){
        //retern previous name of companylogo
        $destinationPath = storage_path().'/app/avatars';
        $files1 = scandir($destinationPath);
        $nameOfFile="";
        $search =$user_guid;
        $search_length = strlen($search);
        foreach ($files1 as $key => $value) {
            if (substr($value, 0, $search_length) == $search) {
                $nameOfFile=$value;
                break;
            }
        }

        //delete previou logo
        if($nameOfFile!=null)
            File::delete(storage_path().'/app/avatars/'.$nameOfFile);
    }

    public function UpdateAvatar($request)
    {
        self::deleteAvatar($request->input('user_guid'));

        //insert new logo of company to this path    =>  storage_path().'/app/company
        $file = $request->file('fileLogo');
        $fileName = $request->input('user_guid'). '.' .$request->file('fileLogo')->getClientOriginalExtension();
        $destinationPath = storage_path().'/app/avatars';
        $file->move($destinationPath, $fileName);

    }

    public function API_UpdateAvatarOfUser($user_guid,$file)
    {
        self::deleteAvatar($user_guid);

        $f = finfo_open();

        $mime = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
        $split = explode( '/', $mime );
        $ext = $split[1];
        //insert new logo of company to this path    =>  storage_path().'/app/company
        $fileName = $user_guid. '.' .$ext;
        $destinationPath = storage_path().'/app/avatars';
        file_put_contents($destinationPath."/".$fileName, $file);
    }

    public static function getCompany($user_id,$user_guid)
    {
        $company = DB::table('user_company')
            ->join('company', 'company.company_id', '=', 'user_company.company_id')
            ->where('user_company.user_id','=',$user_id)
            ->where('company.deleted_at',null)
            ->where('user_company.deleted_at',null)
            ->select('company.*')
            ->get();
        return $company;
    }

    public static function getManager($Employee_User_id)
    {
        $user = DB::table('user')
            ->join('user_company', 'user.user_id', '=', 'user_company.user_id')
            ->where('user_company.company_id','=',session('companiesId0'))
            ->where('user.user_type_id','=',UserTypeRepository::CEO)
            ->where('user_company.deleted_at',null)
            ->where('user.deleted_at',null)
            ->get();
        return $user;
    }
    public static function API_GetManager($Employee_User_id)
    {
        $user = User::find($Employee_User_id);

        if($user-> user_type_id == UserTypeRepository::CEO)
            return $user;
        $company_id = DB::table('user_company')
            ->where('user_company.user_id','=',$Employee_User_id)
            ->where('deleted_at',null)
            ->select('user_company.company_id')
            ->first();
        if($company_id->company_id != null )
        {
            $company_id = $company_id->company_id;
        }
        $user = DB::table('user')
            ->join('user_company', 'user.user_id', '=', 'user_company.user_id')
            ->where('user_company.company_id','=',$company_id)
            ->where('user.user_type_id','=',UserTypeRepository::CEO)
            ->where('user_company.deleted_at',null)
            ->where('user.deleted_at',null)
            ->first();

        return $user;
    }
    public function API_UpdateAvatar($user_guid,$file)
{
    self::deleteAvatar($user_guid);

    $f = finfo_open();

    $mime = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
    $split = explode( '/', $mime );
    $ext = $split[1];
    //insert new logo of user to this path    =>  storage_path().'/app/avatars
    $fileName = $user_guid. '.' .$ext;
    $destinationPath = storage_path().'/app/avatars';
    file_put_contents($destinationPath."/".$fileName, $file);
}

    public function updatePassword($request)
    {
        $oldUser = $this->findByIdAndGuid($request['user_id'],$request['user_guid'] );

        if($request['password']!=null){
            $password = bcrypt($request['password']);
            $oldUser->password=$password;
        }

        $oldUser->save();
    }
    public function findByUserName($userName)
    {
        $query = $this->user->newQuery();
        $query->where('user_name', 'like', $userName);
        $users = $query->get();

        $this->user = $users[0];
    }

    public function findByEmail($email)
    {
        $query = $this->user->newQuery();
        $query->where('email', 'like', $email);
        $users = $query->get();

        $this->user = $users[0];
    }

    public static function getCeoCompanies($ceoId)
    {
        //select company data with
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

        $paramsObj3 = array(
            array("whereRaw",
                "user.user_id='" . $ceoId . "'"
            ),
            array("whereRaw",
                "company.deleted_at is null"
            )

        );

        $CompanyRepository = new CompanyRepository(new Company());
        $CompanyRepository->initialize();

        $companies = $CompanyRepository->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);
        return $companies;
    }

    public static function getCeoEmployess($ceoId)
    {
        $employess = DB::select('SELECT * from user where deleted_at is null and user_id in (select user_id from user_company 
        where deleted_at is null and company_id in (select company_id from user_company where deleted_at is null and user_id = '.$ceoId.'))');
        return $employess;
    }

    public static function DecreaseCharge($ceoId,$ceoGuid, $amount)
    {
        $tempUser = new UserRepository(new User());
        $user = $tempUser->findByIdAndGuid($ceoId,$ceoGuid);

        $newPayment = $user->balance - $amount;
        if($newPayment < 0)
            return false;
        else
            $user->balance=$newPayment;
        $user->save();
        return true;
    }

    public static function IncreaseCharge($ceoId,$ceoGuid, $amount)
    {
        $tempUser = new UserRepository(new User());
        $user = $tempUser->findByIdAndGuid($ceoId,$ceoGuid);

        $newPayment = $user->balance + $amount;
        $user->balance=$newPayment;
        $user->save();
        return true;
    }

    public static function updatePhoneCode($user_id,$user_guid, $phone_code)
    {
        $tempUser = new UserRepository(new User());
        $user = $tempUser->findByIdAndGuid($user_id,$user_guid);

        $user->phone_code=$phone_code;
        $user->save();
        return true;
    }

    public static function removePhoneCode($user_id,$user_guid)
    {
        $tempUser = new UserRepository(new User());
        $user = $tempUser->findByIdAndGuid($user_id,$user_guid);

        if($user != null)
        {
            $user->phone_code=null;
            $user->save();

        }


        return true;
    }

}
?>