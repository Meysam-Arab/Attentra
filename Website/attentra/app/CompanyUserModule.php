<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Log;
use DB;

class CompanyUserModule extends Model
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
    protected $primaryKey = 'company_user_module_id';
    protected $table = 'company_user_module';
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
    protected $fillable = ['user_id','company_id','module_id','cost','end_date','is_active'];

    /**
     * Get the user that owns the task.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($mission) {

        });
    }

}
