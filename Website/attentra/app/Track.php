<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 3/12/2017
 * Time: 1:37 PM
 */

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Log;
use DB;

class Track extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at', 'updated_at'];
    protected $table = 'track';
    protected $primaryKey = 'track_id';

    protected $fillable = ['track', 'user_id'];


    //get the user that owns the track
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}