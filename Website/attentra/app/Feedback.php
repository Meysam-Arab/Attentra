<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class feedback extends Model
{
    use Notifiable;
    use SoftDeletes;

protected $dates = ['deleted_at'];
protected $table = 'feedback';
protected $primaryKey = 'feedback_id';
public $timestamps = true;

protected $fillable = ['title','description','email','tel','mobile'];
}
