<?php

//MEysam Arab////
///139601311530

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use Notifiable;
    use SoftDeletes;

protected $dates = ['deleted_at'];
protected $table = 'payment';
protected $primaryKey = 'payment_id';
public $timestamps = true;

protected $fillable = ['description','email'];
}
