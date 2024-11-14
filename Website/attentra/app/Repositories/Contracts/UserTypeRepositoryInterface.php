<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 12/10/2016
 * Time: 10:25 AM
 */

namespace App\Repositories\Contracts;

//use App\Repositories\Contracts\BaseInterface;


interface UserTypeRepositoryInterface extends BaseInterface
{
    public static function getNoneCode();
    public static function getAdminCode();
    public static function getCEOCode();
    public static function getMiddleCEOCode();
    public static function getEmployeeCode();

}

?>