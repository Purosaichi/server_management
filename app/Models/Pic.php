<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    protected $table = 'pic';
    protected $primaryKey = 'id_pic';
    public $timestamps = false;
}
