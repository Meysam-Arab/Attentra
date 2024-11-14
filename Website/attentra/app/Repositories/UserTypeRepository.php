<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 12/10/2016
 * Time: 10:24 AM
 */
namespace App\Repositories;

use App\UserType;
use ViewComponents\Eloquent\EloquentDataProvider;
use App\Repositories\Contracts\UserTypeRepositoryInterface;
use Log;

class UserTypeRepository implements UserTypeRepositoryInterface
{
    protected $userType;

    const  None = 4;         // کاربر عمومی
    const Admin = 0;     // مدیر سیستم
    const CEO = 1;   // مدیر شرکت
    const MiddleCEO = 2; // مدیر میانی شرکت
    const Employee = 3;//کارمند
    const Device = 5;//دستگاه


    public function __construct(UserType $userType)
    {

        $this->userType = $userType;
        ///////////////////////
    }

    public static function getNoneCode(){
        return self::None;
    }
    public static function getAdminCode(){
        return self::Admin;
    }
    public static function getCEOCode(){
        return self::CEO;
    }
    public static function getMiddleCEOCode(){
        return self::MiddleCEO;
    }
    public static function getEmployeeCode(){
        return self::Employee;
    }

    public function initialize(){

    }
    public function initializeByRequest($request){

    }
    public function select(){

    }

    public function all(){

    }
    public function paginate(){

    }
    public function store(){

    }
    public function update($request){

    }
    public function delete(){

    }

    public function findBy($field,$value){

    }


//
    public function exist($id, $guid){

    }



    public function find($id){

    }

    public function findByIdAndGuid($id, $guid){

    }

/////////////static methods////////////////

    public static function getCodeByCodeString($typeString)
    {
        switch($typeString)
        {
            case strval(self::Admin):
                return self::Admin;
                break;
            case strval(self::CEO):
                return self::CEO;
                break;
            case strval(self::Employee):
                return self::Employee;
                break;
            case strval(self::MiddleCEO):
                return self::MiddleCEO;
                break;
            default:
                return self::None;
                break;
        }
    }


}