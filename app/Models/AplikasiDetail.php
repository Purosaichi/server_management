<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplikasiDetail extends Model
{
    protected $table = 'vw_aplikasi_detail';
    protected $primaryKey = 'id_aplikasi';
    public $timestamps = false;
}