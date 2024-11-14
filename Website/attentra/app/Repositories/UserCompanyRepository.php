<?php
/**
 * Created by PhpStorm.
 * User: hooman-pc
 * Date: 12/03/2017
 * Time: 03:50 PM
 */

namespace App\Repositories;
use App\UserCompany;
use Log;
use App;
use App\Repositories\Contracts\UserCompanyRepositoryInterface;

class UserCompanyRepository implements UserCompanyRepositoryInterface
{
    protected $user_company;

    public function __construct(UserCompany $user_company=null)
    {
        if($user_company==null)
            $user_company=new UserCompany();
        $this->user_company = $user_company;
        ///////////////////////

    }

    public function initialize()
    {
        $this->user_company  -> user_company_id = null;
        $this->user_company -> user_company_guid = null;
        $this->user_company -> user_id = null;
        $this->user_company->company_id = null;
        $this->user_company->self_roll_call = null;

    }

    public function initializeByRequest($request)
    {
        $this->user_company  -> user_company_id = $request ->input('user_company_id');
        $this->user_company -> user_company_guid = $request ->input('user_company_guid');
        $this->user_company -> user_id = $request ->input('user_id');
        $this->user_company->company_id = $request ->input('company_id');
        if(!is_null($request ->input('self_roll_call')))
            $this->user_company->self_roll_call = $request ->input('self_roll_call');
    }

    public function select()
    {
        // no code select() method.
    }

    public function all()
    {
        // no code all() method.
    }

    public function paginate()
    {
        // no code paginate() method.
    }

    public function store()
    {
        $this->user_company->user_company_guid = uniqid('', true);
        $this->user_company->save();
    }

    public function update($request)
    {
        // no code update() method.
    }

    public function delete()
    {
        // no code delete() method.
    }

    public function findBy($field, $value)
    {
        // no code findBy() method.
    }

    public function exist($id, $guid)
    {
        // no code exist() method.
    }

    public function find($id)
    {
        $user_company = UserCompany::find($id);
        return $user_company;
    }

    public function findByIdAndGuid($id, $guid)
    {
        // no code findByIdAndGuid() method.
    }
    public function set_user_and_company_id($user_id,$company_id)
    {
        $this->user_company -> user_id = $user_id;
        $this->user_company->company_id = $company_id;
    }
    public function set_self_roll_call($allow)
    {
        $this->user_company -> self_roll_call = $allow;
    }

    public function getCompanyId()
    {
        return $this->user_company->company_id;
    }

    public static function getUsers($userCompanyId)
    {
        $userCompany = App\UserCompany::find($userCompanyId);

        return $userCompany->user;

    }
}