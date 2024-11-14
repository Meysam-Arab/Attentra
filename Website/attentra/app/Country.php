<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/20/2017
 * Time: 1:43 PM
 */


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Log;
use DB;

class Country extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'country';
    protected $primaryKey = 'country_id';

    protected $fillable = ['name'];

    public function User()
    {
        return $this->hasMany(User::class);
    }
}
