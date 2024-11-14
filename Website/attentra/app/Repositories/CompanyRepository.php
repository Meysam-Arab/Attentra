<?php
/**
 * Created by PhpStorm.
 * User: Parsian
 * Date: 12/13/2016
 * Time: 03:08 PM
 */

namespace App\Repositories;

use App\Company;
use App\compony;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use ViewComponents\Eloquent\EloquentDataProvider;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Repositories\LogEventRepository;
use Route;

use App\Repositories\Contracts\FeedbackRepositoryInterface;
use Log;
use DB;
use File;

class CompanyRepository implements CompanyRepositoryInterface
{
    protected  $compony;
    public function __construct(Company $company)
    {

        $this->compony = $company;

    }

    public function initialize()
    {
        $this->compony->company_id=null;
        $this->compony->company_guid=null;
        $this->compony->name=null;
        $this->compony->is_active=null;
        $this->compony->deleted_at=null;
        $this->compony->time_zone=null;
        $this->compony->zone=null;

//        $this->compony->image=null;
    }

    public function initializeByRequest($request)
    {
        $this->compony->company_id=$request->input('company_id');
        $this->compony->company_guid=$request->input('company_guid');
        $this->compony->name=$request->input('name');
        $this->compony->is_active=$request->input('is_active');
        $this->compony->time_zone=$request->input('time_zone');
        if(!is_null($request->input('zone'))){
            log::info('in ifffffffffffffffffffffffffff');
            $this->compony->zone=$request->input('zone');
        }


//        $this->compony->image = $request->input('image');
    }

    public function select()
    {
        $query = $this->compony->newQuery();
        if($this->compony->company_id != null){
            $query->where('company_id', '=', $this->compony->company_id);
        }
        if($this->compony->name != null){
            $query->where('name', '=', $this->compony->name);
        }
        $query->where('deleted_at', null);
        $company = $query->get();

        return $company;
    }

    public function getFullDetailCompany( $params1,$params2,$params3)
    {

        $query = $this->compony->newQuery();
        //
        if($params1!=null) {
            if(Auth::user()->user_type_id == UserTypeRepository::Admin)
            {
                $query=\App\Utility::fillQueryAlias($query,$params1,true);
            }
            else
            {
                $query=\App\Utility::fillQueryAlias($query,$params1);
            }

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

        $companies = $query->get();

        return $companies;

    }

    public function getUsersOfThisCompany($company_id, $company_guid)
    {
        $paramsObj1 = array(
            array("st", "user"),
            array("st", "user_company")
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
        $paramsObj3 = array(
            array("whereRaw",
                "company.company_id='" . $company_id. "'"
            ),
            array("whereRaw",
                "company.company_guid='" . $company_guid. "'"
            )
                ,
            array("whereRaw",
                "user.deleted_at is NULL"
            ),

        );

        $this->initialize();

        return $this->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);
    }

    ////simplest query
    public function makeWhere($query){
        if($this->compony->company_id != null){
            $query->where('	company.'.'company_id', '=', $this->compony->company_id);
        }
        if($this->compony->company_guid != null){
            $query->where('	company.'.'company_guid', '=', $this->compony->company_guid);
        }
        if($this->compony->name != null){
            $query->where('	company.'.'name', '=', $this->compony->name);
        }
        if( $this->compony->is_active != null){
            $query->where('	company.'.'is_active', '=', $this->compony->is_active);
        }
        if( $this->compony->zone != null){
            $query->where('	company.'.'zone', '=', $this->compony->zone);
        }

        return $query;
    }

    public function all()
    {
        return DB::table('company')
            ->where('deleted_at',null)
            ->get();
    }

    public function paginate()
    {
        // no code
    }

    public function store(){
        $this->compony->company_guid = uniqid('', true);
        $this->compony->is_active = 1;
        $this->compony->save();
        return $this->compony->company_guid;
    }

    public function update($request)
    {
        self::initializeByRequest($request);
        if($this->compony->zone=='')
            DB::table('company')
                ->where('company_id', $this->compony->company_id)
                ->where('company_guid', 'like', $this->compony->company_guid)
                ->where('deleted_at',null)
                ->update([
                    'company.time_zone' => $this->compony->time_zone,
                    'company.name' => $this->compony->name
                ]);
        else
            DB::table('company')
            ->where('company_id', $this->compony->company_id)
            ->where('company_guid', 'like', $this->compony->company_guid)
            ->where('deleted_at',null)
            ->update([
                'company.time_zone' => $this->compony->time_zone,
                'company.name' => $this->compony->name,
                'company.zone' => $this->compony->zone
            ]);

    }

    public function delete()
    {
        try
        {
            $this->findByIdAndGuid($this->compony -> company_id,$this->compony->company_guid)->delete();
            app('App\Http\Controllers\CompanyUserModuleController')->delete_default_free_moduals_for_company($this->compony -> company_id);
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function findBy($field, $value)
    {
        // no code
    }

    public function exist($id, $guid)
    {
        $query = $this->compony->newQuery();
        $query->where('company_id', '=', $id);
        $query->where('company_guid', 'like', $guid);
        $companies = $query->get();

        if(count($companies) > 0)
            return true;
        return false;
    }

    public function findByIdAndGuid($id, $guid)
    {
        try
        {
            $query = $this->compony->newQuery();
            $query->where('company_id', '=', $id);
            $query->where('company_guid', 'like', $guid);
            $companies = $query->get();
            return $companies[0];
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    public function set($id,$guid)
    {
        $this->compony->company_id = $id;
        $this->compony->company_guid = $guid;

    }

    public function find($id)
    {
        return $this->compony->find($id);
    }

    public function deleteLogo($company_guid){
        //retern previous name of companylogo
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


        //delete previou logo
        File::delete(storage_path().'/app/company/'.$nameOfFile);
    }

    public function UpdateLogoOfCompany($request)
    {
        self::deleteLogo($request->input('company_guid'));

        //insert new logo of company to this path    =>  storage_path().'/app/company
        $file = $request->file('fileLogo');
        $fileName = $request->input('company_guid'). '.' .$request->file('fileLogo')->getClientOriginalExtension();
        $destinationPath = storage_path().'/app/company';
        $file->move($destinationPath, $fileName);
    }

    public function API_UpdateLogoOfCompany($company_guid,$file)
    {
        self::deleteLogo($company_guid);

        $f = finfo_open();

        $mime = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
        $split = explode( '/', $mime );
        $ext = $split[1];
        //insert new logo of company to this path    =>  storage_path().'/app/company
        $fileName = $company_guid. '.' .$ext;
        $destinationPath = storage_path().'/app/company';
//        $file->move($destinationPath, $fileName);

        file_put_contents($destinationPath."/".$fileName, $file);
    }

    public function set_name($name)
    {
        $this->compony->name = $name;
    }

    public function get_name()
    {
        return $this->compony->name;
    }

    public function set_image($binary_image)
    {
        $this->compony->image = $binary_image;
    }

    public function get_image()
    {
        return $this->compony->image;
    }

    public function get_guid()
    {
        return $this->compony->company_guid;
    }

    public function get_time_zone()
    {
        return $this->compony->time_zone;
    }

    public static function getCeoOfThisCompany($company_id, $company_guid)
    {
        $paramsObj1 = array(
            array("st", "user")
        );

        //join
        $paramsObj2 = array(
            array("join",
                "user_company",
                array("company.company_id", "=", "user_company.company_id")
            ),
            array("join",
                "user",
                array("user_company.user_id", "=", "user.user_id"),
                array("user.user_type_id", "=", UserTypeRepository::CEO)
            )
        );
        //conditions
        $paramsObj3 = array(
            array("whereRaw",
                "company.company_id='" . $company_id. "'"
            ),
            array("whereRaw",
                "company.company_guid='" . $company_guid. "'"
            )
        ,
            array("whereRaw",
                "user.deleted_at is NULL"
            ),

        );

        $cp = new CompanyRepository(new Company());


        return $cp->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);
    }
    public static function getCeoOfThisCompanyById($company_id)
    {
        $paramsObj1 = array(
            array("st", "user")
        );

        //join
        $paramsObj2 = array(
            array("join",
                "user_company",
                array("company.company_id", "=", "user_company.company_id")
            ),
            array("join",
                "user",
                array("user_company.user_id", "=", "user.user_id"),
                array("user.user_type_id", "=", UserTypeRepository::CEO)
            )
        );
        //conditions
        $paramsObj3 = array(
            array("whereRaw",
                "company.company_id='" . $company_id. "'"
            )
        ,
            array("whereRaw",
                "user.deleted_at is NULL"
            ),

        );

        $cp = new CompanyRepository(new Company());


        return $cp->getFullDetailCompany($paramsObj1, $paramsObj2, $paramsObj3);
    }
}