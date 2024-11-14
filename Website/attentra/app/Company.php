<?php

namespace App;
use App\UserCompany;
use App\CompanyUserModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Log;
use DB;

class Company extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'company';
    protected $primaryKey = 'company_id';

    protected $fillable = ['name'];

    public function UserCompany()
    {
        return $this->hasMany(UserCompany::class);
    }

    public function CompanyUserModule()
    {
        return $this->hasMany(CompanyUserModule::class);
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($company) {
            $company->UserCompany()->delete();
            $company->CompanyUserModule()->delete();
        });

    }

}
