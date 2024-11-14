<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Log;
use DB;

class UserMision extends Model
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
    protected $primaryKey = 'user_mission_id';
    protected $table = 'user_mission';
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
    protected $fillable = ['mission_id','user_company_id'];


    public function userCompany()
    {
        return $this->belongsTo(UserCompany::class);
    }
    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }
}
