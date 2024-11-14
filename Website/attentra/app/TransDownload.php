<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ViewComponents\Eloquent\EloquentDataProvider;
use Log;
use DB;

class TransDownload extends Model
{

    use Notifiable;
    use SoftDeletes;

    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transe_download';
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
    protected $fillable = ['title','description'];

    /**
     * Get the user that owns the task.
     */
    public function download()
    {
        return $this->belongsTo('App\Download');
    }
}
