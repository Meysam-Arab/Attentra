<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ViewComponents\Eloquent\EloquentDataProvider;
use Log;
use DB;

class download extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'download';
    protected $primaryKey = 'download_id';
    public $timestamps = true;

    protected $fillable = ['extention','size'];

    public function TransDownload()
    {
        return $this->hasMany(TransDownload::class);
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($downloads) {
            $downloads->TransDownload()->delete();

        });
    }
    public function store()
    {

    }

}
