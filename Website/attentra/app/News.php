<?php

//Meysam

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Log;
use DB;

class News extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'news';
    protected $primaryKey = 'news_id';

    protected $fillable = ['title', 'description'];

}
