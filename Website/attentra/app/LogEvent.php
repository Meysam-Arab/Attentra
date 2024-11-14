<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 2/2/2017
 * Time: 10:19 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Log;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;



class LogEvent extends Model
{

    use Notifiable;
    use SoftDeletes;

    protected $table = 'log_event';
    protected $primaryKey = 'log_event_id';
    protected $dates = ['deleted_at', 'updated_at', 'created_at'];

    /* The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'controller_and_action_name','error_message'
    ];

//    public function setUpdatedAtAttribute($value)
//    {
//        // to Disable updated_at
//    }

}