<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ViewComponents\Eloquent\EloquentDataProvider;
use Log;
use DB;

class TransModule extends Model
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
    protected $table = 'trans_module';
    protected $primaryKey = 'trans_module_id';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['module_id','language_id','title','description'];

    /**
     * Get the user that owns the task.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
