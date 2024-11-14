<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Attendance;
use Log;
use DB;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'user';
    protected $primaryKey = 'user_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','family', 'code', 'payment','user_type_id',
        'start_time', 'end_time', 'end_sound', 'country_id', 'phone_code',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //these will not be in seassion...
    protected $hidden = [
        'remember_token','password',
         'report_row_limit',
        'start_time', 'end_time', 'end_sound', 'cloud',
        'api_token', 'created_at', 'updated_at', 'is_active', 'deleted_at',
    ];

//    protected $guarded = array('user_id','user_guid','is_active', 'password');
	
	 // Other Eloquent Properties...

    /**
     * Get all of the tasks for the user.
//     */
//    public function userTypes()
//    {
//        return $this->hasMany(UserType::class);
//    }

    /**
     * Get the tracks for the user.
     */
    public function tracks()
    {
        return $this->hasMany(Track::class);
    }


    /**
     * Get the tracks for the user.
     */
    public function userCompanies()
    {
        return $this->hasMany(UserCompany::class);
    }

    /**
     * Get the tracks for the user.
     */
    public function companyUserModules()
    {
        return $this->hasMany(CompanyUserModule::class);
    }


    protected static function boot() {
        parent::boot();

        static::deleting(function($user) {
            $user->tracks()->delete();
            $user->companyUserModules()->delete();
            $user->userCompanies()->delete();
        });
    }

}
