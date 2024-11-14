<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ViewComponents\Eloquent\EloquentDataProvider;
use Log;
use DB;

class Attendance extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'attendance';
    protected $primaryKey = 'attendance_id';

    protected $fillable = ['start_date_time','end_date_time','is_mission'];

    public function user_Company()
    {
        return $this->belongsTo(UserCompany::class);
    }
}
