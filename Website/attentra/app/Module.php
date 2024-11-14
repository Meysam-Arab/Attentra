<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Log;
use DB;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'module';
    protected $primaryKey = 'module_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_active'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //these will not be in seassion...
    protected $hidden = [
        'module_guid', 'created_at', 'updated_at', 'deleted_at',
    ];

//    protected $guarded = array('user_id','user_guid','is_active', 'password');

    // Other Eloquent Properties...

    /**
     * Get all of the tasks for the user.
     */
    public function CompanyUserModule()
    {
        return $this->hasMany(CompanyUserModule::class);
    }
}
