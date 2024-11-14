<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ViewComponents\Eloquent\EloquentDataProvider;
use Log;
use DB;

class UserCompany extends Model
{

    use Notifiable;
    use SoftDeletes;
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $dates = ['deleted_at'];
    protected $primaryKey = 'user_company_id';
    protected $table = 'user_company';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','company_id'];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function userMission()
    {
        return $this->hasMany(UserMision::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($userCompany) {
            $userCompany->userMission()->delete();
            $userCompany->attendance()->delete();

        });
    }
}
