<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Log;
use DB;



class Mission extends Model
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'mission';
    protected $primaryKey = 'mission_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'start_date_time','end_date_time',
    ];

    //any column here wont show in select queries
    protected $hidden = [
         'created_at', 'updated_at',  'deleted_at',
    ];



    public function userMission()
    {
        return $this->hasMany(UserMision::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($mission) {
            $mission->userMission()->delete();

        });
    }
}
