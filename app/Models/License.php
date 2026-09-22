<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $table = 'lisensi';
    protected $primaryKey = 'id_lisensi';
    public $timestamps = false;
}
