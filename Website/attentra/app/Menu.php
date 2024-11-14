<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 2/2/2017
 * Time: 10:39 AM
 */

namespace App;

use App\Link;
use App\Repositories\UserTypeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Log;


class Menu
{
    public $links=[];
    function __construct($user_type,$for_doshboard)
    {

        if($for_doshboard == true)
        {
            ////////////////////////////////////////////
            if ($user_type == UserTypeRepository::CEO || $user_type == UserTypeRepository::Admin)
            {
                //company managment link////////////////////////////
                $link = new Link(trans('messages.tlt_ManageCompanies'),null,null,'#',null);
                /////create/////////////////////////////////////////
                $subLink = new Link(trans('messages.tlt_CreateCompany'),null,null,'/company/create',null);
                $link->sub_links[] = $subLink;
                ////////////////////////////////////////////////////
                /////list/////////////////////////////////////////
                $subLink = new Link(trans('messages.tlt_LostOfCompanies'),null,null,'/companyList',null);
                $link->sub_links[] = $subLink;
                ////////////////////////////////////////////////////
                $this->links[] = $link;
                ////////////////////////////////////////////
            }
            if($user_type == UserTypeRepository::Admin)
            {
                ////////////////////ManageModules///////////////////////
                ////////////////////ManageModules///////////////////////
                $link = new Link(trans('messages.tlt_ManageModules'),null,null,'#',null);
                /////create/////////////////////////////////////////
                $subLink = new Link(trans('messages.tlt_CreateModules'),null,null,'/module/create',null);
                $link->sub_links[] = $subLink;
                /////list/////////////////////////////////////////
                $subLink = new Link(trans('messages.tlt_ListOfModules'),null,null,'/module/index',null);
                $link->sub_links[] = $subLink;
                ////////////////////////////////////////////////////
                $this->links[] = $link;
                //////////////////manage language //////////////////////////
                //////////////////manage language //////////////////////////downloadsList
                $link = new Link(trans('messages.tlt_ManageLanguages'),null,null,'#',null);
                /////create/////////////////////////////////////////
                $subLink = new Link(trans('messages.tlt_CreateLanguages'),null,null,'/language/create',null);
                $link->sub_links[] = $subLink;
                $this->links[] = $link;
                //////////////////manage download //////////////////////////
                //////////////////manage download //////////////////////////
                $link = new Link(trans('messages.tlt_ManageDownloads'),null,null,'#',null);
                /////create/////////////////////////////////////////
                $subLink = new Link(trans('messages.tlt_ListOfDownloads'),null,null,'/downloadsList',null);
                $link->sub_links[] = $subLink;
                $subLink = new Link(trans('messages.tlt_CreateDownload'),null,null,'/uploadCreate',null);
                $link->sub_links[] = $subLink;
                $this->links[] = $link;
            }

            /////////////////////////For All Users///////////////////////////////////////////
            /////////////////////////For All Users///////////////////////////////////////////
            /////////////////////mission

            if(session('CompanyCount')==0 && $user_type == UserTypeRepository::CEO)
            {
                $link=new Link(trans('messages.tlt_ManageMissions'),null,null,'/company/create',null);
            }
            else $link = new Link(trans('messages.tlt_ManageMissions'),null,null,'#',null);

            if($user_type == UserTypeRepository::CEO){
                for($i=0;$i<session('CompanyCount');$i++){
                    $subLink = new Link(session('companiesName'.$i),null,null,'/missionList/'.session('companiesId'.$i).'/'.session('companiesGuid'.$i).'/null/null',null);
                    $link->sub_links[] = $subLink;
                }
            }
            else
            {
                $subLink = new Link(trans('messages.tlt_ListOfMissions'),null,null,'/missionList/null/null/null/null',null);
                $link->sub_links[] = $subLink;
            }
            $this->links[] = $link;

            /////////////////////attendance
            if(session('CompanyCount')==0 && $user_type == UserTypeRepository::CEO)
                $link=new Link(trans('messages.tlt_ManageAttendances'),null,null,'/company/create',null);
            else
                $link = new Link(trans('messages.tlt_ManageAttendances'),null,null,'#',null);
            if($user_type == UserTypeRepository::CEO){
                for($i=0;$i<session('CompanyCount');$i++){
                    $subLink = new Link(session('companiesName'.$i),null,null,'/attendaceList/'.session('companiesId'.$i).'/'.session('companiesGuid'.$i),null);
                    $link->sub_links[] = $subLink;
                }
            }
            else
            {
                $subLink = new Link(trans('messages.tlt_ListOfAttendances'),null,null,'/attendaceList/null/null',null);
                $link->sub_links[] = $subLink;
            }

            $this->links[] = $link;




            /////////////////////attendance
            if(session('CompanyCount')==0 && $user_type == UserTypeRepository::CEO)
                $link=new Link('گزارشات',null,null,'/company/create',null);
            else
                $link = new Link('گزارشات',null,null,'#',null);
            if($user_type == UserTypeRepository::CEO){
                for($i=0;$i<session('CompanyCount');$i++){
                    $subLink = new Link(session('companiesName'.$i),null,null,'/reports/'.session('companiesId'.$i).'/'.session('companiesGuid'.$i),null);
                    $link->sub_links[] = $subLink;
                }
                $this->links[] = $link;
            }




            //QR Code link////////////////////////////
            $link = new Link(trans('messages.tlt_PrintCard'),null,null,'/qrcode/'.Auth::user()->user_id.'/'.Auth::user()->user_guid,null);
            $this->links[] = $link;
            //user edit link////////////////////////////
            $link = new Link(trans('messages.tlt_EditUser'),null,null,'/user/edit/'.Auth::user()->user_id.'/'.Auth::user()->user_guid,null);
            $this->links[] = $link;

            if($user_type == UserTypeRepository::CEO)
            {
                ////////////////////ManagePyment///////////////////////
                ////////////////////ManagePyment///////////////////////
                $link=new Link(trans('messages.tlt_ManagePayments'),null,null,'/payment/index',null);
                $this->links[] = $link;
                ////////////////////ManageModules///////////////////////
                ////////////////////ManageModules///////////////////////
                if(session('CompanyCount')==0)
                    $link=new Link(trans('messages.tlt_ManageModules'),null,null,'/company/create',null);
                else
                    $link = new Link(trans('messages.tlt_ManageModules'),null,null,'#',null);
                //////////////////public module link
                $subLink = new Link(trans('messages.lbl_PaymentList'),null,null,'/module/purchases',null);
                $link->sub_links[] = $subLink;
                $subLink = new Link(trans('messages.lbl_PublicModule'),null,null,'/module/publicindex/'.session('companiesId0').'/'.session('companiesGuid0'),null);
                $link->sub_links[] = $subLink;
                /////list/////////////////////////////////////////
                for($i=0;$i<session('CompanyCount');$i++){
                    $subLink = new Link(session('companiesName'.$i),null,null,'/module/index/'.session('companiesId'.$i).'/'.session('companiesGuid'.$i),null);
                    $link->sub_links[] = $subLink;
                }
//
                ////////////////////////////////////////////////////
                $this->links[] = $link;
            }


            if ($user_type == UserTypeRepository::Employee)
            {

            }
            if ($user_type == UserTypeRepository::MiddleCEO)
            {

            }

            $link = new Link(trans('messages.lbl_ٍExit'),null,null,'/logout',null);
            $this->links[] = $link;
        }



    }

}